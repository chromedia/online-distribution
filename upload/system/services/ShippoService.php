<?php

require_once(DIR_SYSTEM . 'utilities/CurlUtil.php');


class ShippoService
{
    const END_POINT = 'https://api.goshippo.com/v1/';

    private static $instance;

    private $curlUtil;

    private $carriers;

    /**
     * Returns instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShippoService();
        }

        return self::$instance;
    }

    /**
     * Instantiates shippo service class
     */
    public function __construct()
    {
        $this->curlUtil = new CurlUtil();

        if (!defined('CARRIERS')) {
            $this->carriers = array('USPS', 'UPS');
        } else {
            $this->carriers = explode(',', CARRIERS);
        }
    }

    /**
     * Confirms address
     */
    public function confirmAddress($data)
    {
        $data['object_purpose'] = 'PURCHASE';
        $url = self::END_POINT.'addresses/';

        // Run call
        $response = $this->curlUtil->call($url, 'POST', SHIPPO_AUTHORIZATION, $data);
        $address = json_decode($response, true);

        return $address;
    }

    /**
     * Gets shipment info using nested call in Shippo but filtering unique products only
     */
    // public function getShipmentInfoNestedlyUsingUniqueProducts($packages, $fromAddressData, $toAddressData, $enableSignatureConfirmation = true)
    // {

    // }

    /**
     * Gets shipment info using nested shipment call in Shippo
     */
    public function getShipmentInfoNestedly($packages, $fromAddressData, $toAddressData, $enableSignatureConfirmation = true)
    {
        try {
            // $packages
            foreach ($packages as $key => $package) {
                $fromAddressData['object_purpose'] = 'PURCHASE';
                $toAddressData['object_purpose'] = 'PURCHASE';

                $parcelData = array(
                    'length'    => number_format($package['length'], 2, '.', ''),
                    'width'     => number_format($package['width'], 2, '.', ''),
                    'height'    => number_format($package['height'], 2, '.', ''),
                    'distance_unit' => $package['length_unit'],
                    'weight'    => number_format($package['weight'], 2, '.', ''),
                    'mass_unit' => $package['weight_unit'],
                    'metadata'  => $package['content']['product_id']
                );

                $data = array(
                    "object_purpose" => "PURCHASE",
                    "address_from" => $fromAddressData,
                    "address_to"   => $toAddressData,
                    "parcel"       => $parcelData,
                    "submission_type" => "PICKUP",
                    "insurance_currency" => ""
                );

                if ($enableSignatureConfirmation) {
                    $data['extra'] =   json_encode(array(
                        "signature_confirmation" => true
                    ));  
                }

                $url = self::END_POINT.'shipments/';

                // Run call
                $response = $this->curlUtil->call($url, 'POST', SHIPPO_AUTHORIZATION, $data, 'application/json');
                $shipment = json_decode($response, true);

                $shipments[$key] = $shipment;
            }

            sleep(3);

            $rates = $this->checkRatesUsingShipmentId($shipments, $packages);
            
            return $this->__sortRates($rates);

        } catch(Exception $e) {
            throw $e->getMessage();
        }

    }

    /**
     * Gets shipment info
     */
    public function getShipmentInfo($packages, $addressFrom, $addressTo, $enableSignatureConfirmation = true)
    {
        try {
            $parcels = $this->makeParcelsForPackages($packages);
            $shipments = $this->makeShipmentsForParcels($parcels, $addressFrom, $addressTo, $enableSignatureConfirmation);

            sleep(3);

            $rates = $this->checkShipmentRates($shipments, $packages);

            return $this->__sortRates($rates);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Make parcels for packages
     */
    public function makeParcelsForPackages($packages)
    {
        $parcels = array();

        // Parcel Call and Shipment Call
        foreach ($packages as $key => $package) {
            $parcels[$key] = $this->makeParcelCall($package);
        }

        return $parcels;
    }

    /**
     * Make shipments for packages
     */
    public function makeShipmentsForParcels($parcels, $addressFrom, $addressTo, $enableSignatureConfirmation = true)
    {
        $shipments = array();

        foreach ($parcels as $key => $parcel) {
            $shipments[$key] = $this->makeShipmentCall($parcel, $addressFrom, $addressTo, $enableSignatureConfirmation);
        }

        return $shipments;
    }

    /**
     * Checking rates for parcel shipments
     */
    public function checkShipmentRates($shipments, $packages)
    {

        $ratesInfo = array('carriers' => array(), 'options' => array());

        foreach ($shipments as $key => $shipment) {
            $ratesInfo = $this->checkRates($shipment['rates_url'], $ratesInfo['carriers']);
            $packages[$key]['rates'] = $ratesInfo['options'];
        }

        $_SESSION['packages'] = $packages;

        return $ratesInfo['carriers'];
    }

    /**
     * Checking rates for parcel shipments using shipment id
     */
    public function checkRatesUsingShipmentId($shipments, $packages)
    {
        // $ratesInfo = array('carriers' => array(), 'options' => array());

        // foreach ($shipments as $key => $shipment) {
        //     $url = self::END_POINT.'shipments/'.$shipment['object_id'].'/rates/USD';

        //     $ratesInfo = $this->checkRates($url, $ratesInfo['carriers']);

        //     if (empty($ratesInfo['options'])) {
        //         // call rates one more time
        //         $ratesInfo = $this->checkRates($url, $ratesInfo['carriers']);
        //     }

        //     $packages[$key]['rates'] = $ratesInfo['options'];
        // }

        // $_SESSION['packages'] = $packages;

        $ratesInfo = array('carriers' => array(), 'options' => array());

        foreach ($shipments as $key => $shipment) {
            $url = self::END_POINT.'shipments/'.$shipment['object_id'].'/rates/USD';
            $quantity = $packages[$key]['quantity'];

            $ratesInfo = $this->checkRates($url, $ratesInfo['carriers'], $quantity);

            // if (empty($ratesInfo['options'])) {
            //     // call rates one more time
            //     $ratesInfo = $this->checkRates($url, $ratesInfo['carriers']);
            // }

            $packages[$key]['rates'] = $ratesInfo['options'];
        }

        $_SESSION['packages'] = $packages;

        return $ratesInfo['carriers'];
    }

    /**
     * Make parcel call
     *
     * @return array
     */
    public function makeParcelCall($package)
    {
        // Parcel Data
        $data = array(
            'length'    => number_format($package['length'], 2, '.', ''),
            'width'     => number_format($package['width'], 2, '.', ''),
            'height'    => number_format($package['height'], 2, '.', ''),
            'distance_unit' => $package['length_unit'],
            'weight'    => number_format($package['weight'], 2, '.', ''),
            'mass_unit' => $package['weight_unit'],
            'metadata'  => $package['content']['product_id']
        );

        // Call Data
        $url = self::END_POINT.'parcels/';

        // Run call
        $response = $this->curlUtil->call($url, 'POST', SHIPPO_AUTHORIZATION, $data, 'application/json');
        $parcel = json_decode($response, true);

        return $parcel;
    }

    /**
     * Make shipment call
     */
    public function makeShipmentCall($parcel, $addressFrom, $addressTo, $enableSignatureConfirmation = true)
    {

        // Shipment Data
        $data = array(
            "object_purpose" => "PURCHASE",
            "address_from" => $addressFrom['object_id'],
            "address_to" => $addressTo['object_id'],
            "parcel" => $parcel['object_id'],
            "submission_type" => "PICKUP",
            "insurance_currency" => "USD"
        );

        if ($enableSignatureConfirmation) {
            $data['extra'] =   json_encode(array(
                "signature_confirmation" => true
            ));  
        }


        // Call Data
        $url = self::END_POINT.'shipments/';

        // Run call
        $response = $this->curlUtil->call($url, 'POST', SHIPPO_AUTHORIZATION, $data, 'application/json');
        $shipment = json_decode($response, true);

        return $shipment;
    }

    /**
     * Check rates
     */
    public function checkRates($ratesUrl, $carriers = array(), $quantity = 1)
    {
        $response = $this->curlUtil->call($ratesUrl, 'GET', SHIPPO_AUTHORIZATION);
        $response = json_decode($response, true);

        $ratesOptions = array();
        $rates = $response['results'];
        $total = 0;

        foreach($rates as $rate) {
            if (!empty($rate) && !is_null($rate)) {
                // Get rate provider
                $provider = $rate['provider'];
                // Filter by rate provider
                if (in_array($provider, $this->carriers)) {
                    $serviceName = $rate['servicelevel_name'];
                    $rateId = $rate['object_id'];

                    $ratesOptions[] = array(
                        'service' => $serviceName,
                        'rate_id' => $rateId
                    );

                    if (isset($carriers[$serviceName]['total'])) {
                        $total = $carriers[$serviceName]['total'] + $rate['amount'] * $quantity;
                    } else {
                        $total = $rate['amount'] * $quantity;
                    }

                    $carriers[$serviceName] = array(
                        'shipment_id' => $rate['shipment'],
                        'provider'   => $provider,
                        'service'    => $serviceName,
                        'amount'     => $rate['amount'],
                        'total'      => $total,
                        'days'       => $rate['days'],
                        'duration_terms' => $rate['duration_terms']
                    );
                }
            }
        }

        return array(
            'carriers' => $carriers,
            'options'  => $ratesOptions
        );
    }

    /**
     * Request shipping
     */
    public function requestShipping($serviceName)
    {
        // if(isset($_SESSION['packages'])) {
        //     $packages = $_SESSION['packages'];
        //     $transactionIds = array();

        //     foreach ($packages as $key => $package) {
        //         $possibleRates = $package['rates'];
        //         $rateId = $this->__getRateIdOfSelectedSpeed($serviceName, $possibleRates);

        //         if ($rateId) {
        //             // Shipping Label Data
        //             $data = array(
        //                 "rate" => $rateId,
        //                 "notification_email_from" => false,
        //                 "notification_email_to" => false,
        //                 "notification_email_other" => "",
        //                 "metadata" => $package['content']['product_name']
        //             );

        //             // Call Data
        //             $url = self::END_POINT.'transactions/';

        //             // Run call (label purchase request)
        //             $response = $this->curlUtil->call($url, 'POST', SHIPPO_AUTHORIZATION, $data, 'application/json');
        //             $object = json_decode($response, true);

        //             $transactionIds[$key] = $object['object_id'];
        //         }
        //     }

        //     $this->__getTransactionData($packages, $transactionIds)
        //     return true;
        // }

        // throw new Exception('Session timeout while processing shipping due to inactivity. Please repeat process.');

        if(isset($_SESSION['packages'])) {
            $packages = $_SESSION['packages'];
            $transactionIds = array();

            foreach ($packages as $key => $package) {
                $possibleRates = $package['rates'];
                $rateId = $this->__getRateIdOfSelectedSpeed($serviceName, $possibleRates);

                if ($rateId) {
                    $quantity = $package['quantity'];

                    for ($ctr = 0; $ctr < $quantity; $ctr++) {
                        // Shipping Label Data
                        $data = array(
                            "rate" => $rateId,
                            "notification_email_from" => false,
                            "notification_email_to" => false,
                            "notification_email_other" => "",
                            "metadata" => $package['content']['product_name']
                        );

                        // Call Data
                        $url = self::END_POINT.'transactions/';

                        // Run call (label purchase request)
                        $response = $this->curlUtil->call($url, 'POST', SHIPPO_AUTHORIZATION, $data, 'application/json');
                        $object = json_decode($response, true);

                        $transactionIds[$key][$ctr] = $object['object_id'];
                    }
                }
            }

            $this->__getTransactionData($packages, $transactionIds);

            return true;
        }

        throw new Exception('Session timeout while processing shipping due to inactivity. Please repeat process.');


    }

    /**
     * Get transaction data
     */
    private function __getTransactionData($packages, $transactionIds)
    {
        // $packagesWithAdditionalInfo = array();

        // foreach ($transactionIds as $packageKey => $transactionId) {
        //     $package = $packages[$packageKey];
        //     $package['shipping_transaction'] = $this->__verifyTransaction($transactionId);

        //     $packagesWithAdditionalInfo[$packageKey] = $package;
        // }

        // $_SESSION['packages'] = $packagesWithAdditionalInfo;

        $packagesWithAdditionalInfo = array();

        foreach ($transactionIds as $packageKey => $ids) {
            $package = $packages[$packageKey];

            foreach ($ids as $ctr => $id) {
                $package['shipping_transaction'][$ctr] = $this->__verifyTransaction($id);
            }

            $packagesWithAdditionalInfo[$packageKey] = $package;
        }

        $_SESSION['packages'] = $packagesWithAdditionalInfo;
    }


    /**
     * Verify shipping transaction status
     */
    private function __verifyTransaction($transactionId)
    {
        $wait = true;

        while ($wait) {
            $url = 'https://api.goshippo.com/v1/transactions/' . $transactionId;

            // Run call and decode json response into php object
            $response = $this->curlUtil->call($url, 'GET', SHIPPO_AUTHORIZATION, false);
            $object = json_decode($response, true);

            // Get status
            $status = $object['object_status'];

            if ($status == "WAITING" || $status == "QUEUED") {
                sleep(1);
                $wait = true;
            } else if ($status == "ERROR") {
                $wait = false;
            } else if ($status == "SUCCESS") {
                $wait = false;
            }
        }

        return $response;
    }

    /**
     * Get rate id of selected speed
     */
    private function __getRateIdOfSelectedSpeed($serviceName, $possibleRates)
    {
        $rateId = 0;

        foreach ($possibleRates as $each) {
            if ($each['service'] == $serviceName) {
                return $each['rate_id'];
            }
        }

        return $rateId;
    }

    /**
     * Sorts carriers
     */
    private function __sortRates($rates)
    {
        $sorted = array();
        $groupedByProvider = array();

        $keys = array_keys($rates);
        $length = sizeof($keys);

        for ($i = 0; $i < $length - 1; ++$i) {
            for ($j = 0; $j < $length - $i - 1; ++$j) {
                $rate1 = $rates[$keys[$j]];
                $rate2 = $rates[$keys[$j + 1]];

                if ($rate1['total'] > $rate2['total']) {
                    $temp = $keys[$j];
                    $keys[$j] = $keys[$j + 1];
                    $keys[$j + 1] = $temp;
                }
            }
        }

        foreach ($keys as $key) {
            $sorted[$key] = $rates[$key];
            $groupedByProvider[$rates[$key]['provider']][] = $rates[$key];
        }

        return array(
            'sorted_by_amount' => $sorted,
            'group_by_provider' => $groupedByProvider
        );
    }
}
