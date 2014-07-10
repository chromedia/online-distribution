2<?php

require_once(DIR_SYSTEM . 'utilities/CurlUtil.php');


class ShippoService
{
    const END_POINT = 'https://api.goshippo.com/v1/';

    private static $instance;

    private $curlUtil;

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
     * Gets shipment info
     */
    public function getShipmentInfo($packages, $addressFrom, $addressTo)
    {
        $parcels = $this->makeParcelsForPackages($packages);
        $shipments = $this->makeShipmentsForParcels($parcels, $addressFrom, $addressTo);
        sleep(3);

        $rates = $this->__sortRates($this->checkShipmentRates($shipments, $packages));

        return $rates;
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
    public function makeShipmentsForParcels($parcels, $addressFrom, $addressTo)
    {
        $shipments = array();

        foreach ($parcels as $key => $parcel) {
            $shipments[$key] = $this->makeShipmentCall($parcel, $addressFrom, $addressTo);
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
        $response = $this->curlUtil->call($url, 'POST', SHIPPO_AUTHORIZATION, $data);
        $parcel = json_decode($response, true);

        return $parcel;
    }

    /**
     * Make shipment call
     */
    public function makeShipmentCall($parcel, $addressFrom, $addressTo)
    {
        // Shipment Data
        $data = array(
            "object_purpose" => "PURCHASE",
            "address_from" => $addressFrom['object_id'],
            "address_to" => $addressTo['object_id'],
            "parcel" => $parcel['object_id'],
            "submission_type" => "PICKUP",
            "insurance_currency" => "USD",
            "extra" => json_encode(array(
                "signature_confirmation" => true
            ))
        );      

        // Call Data
        $url = self::END_POINT.'shipments/';

        // Run call
        $response = $this->curlUtil->call($url, 'POST', SHIPPO_AUTHORIZATION, $data);
        $shipment = json_decode($response, true);

        return $shipment;
    }

    /**
     * Check rates
     */
    public function checkRates($ratesUrl, $carriers = array())
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
                //if ($provider == "UPS") {
                    $serviceName = $rate['servicelevel_name'];
                    $rateId = $rate['object_id'];

                    $ratesOptions[] = array(
                        'service' => $serviceName,
                        'rate_id' => $rateId
                    );

                    if (isset($carriers[$serviceName]['total'])) {
                        $total = $carriers[$serviceName]['total'] + $rate['amount'];
                    } else {
                        $total = $rate['amount'];
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
                //}
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
        if(isset($_SESSION['packages'])) {
            $packages = $_SESSION['packages'];
            $newPackages = array();

            foreach ($packages as $key => $package) {
                $possibleRates = $package['rates'];
                $rateId = $this->__getRateIdOfSelectedSpeed($serviceName, $possibleRates);

                if ($rateId) {
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
                    $response = $this->curlUtil->call($url, 'POST', SHIPPO_AUTHORIZATION, $data);
                    $object = json_decode($response, true);
    
                    // Verify transaction request and save
                    $package['shipping_transaction'] = $this->__verifyTransaction($object['object_id']);
                    $newPackages[$key] = $package;
                }
            }
            
            $_SESSION['packages'] = $newPackages;

            return true;
        }

        throw new Exception('Session timeout while processing shipping due to inactivity. Please repeat process.');
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
                break;
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
        }

        return $sorted;
    }
}
