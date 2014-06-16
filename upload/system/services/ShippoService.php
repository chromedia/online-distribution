<?php

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
     * Make parcels call
     * Make shipment call
     * Retrieve rates
     *
     * @param array $packages
     * @param array $addressFrom
     * @param array $addressTo
     */
    public function getShipmentInfo($packages, $addressFrom, $addressTo)
    {
        $ratesInfo = array('carriers' => array(), 'ratesOptionPerPackage' => array());
        $newPackages = array();

        foreach ($packages as $key => $package) {
            $parcelInfoArray = $this->makeParcelCall($package);

            if (isset($parcelInfoArray['object_id'])) {
                $shipmentInfoArray = $this->makeShipmentCall($parcelInfoArray, $addressFrom, $addressTo);
            }

            $ratesInfo = $this->checkRates($shipmentInfoArray['rates_url'], $ratesInfo['carriers']);
            $package['rates'] = $ratesInfo['ratesOptionPerPackage'];

            $newPackages[$key] = $package;
        }

        $_SESSION['packages'] = $newPackages;
        
        return $ratesInfo['carriers'];
    }

    /**
     * Update 
     */

    /**
     * Make parcels call
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
            'distance_unit' => 'mm',
            'weight'    => number_format($package['weight'], 2, '.', ''),
            'mass_unit' => 'kg',
            'metadata'  => $package['content']['product_id']                  
        );        

        // Call Data
        $url = self::END_POINT.'parcels/';

        // Run call
        $response = $this->curlUtil->call($url, 'POST', SHIPPO_AUTHORIZATION, $data);
        $parcel = json_decode($response, true);

        // TODO: check for errors/invalidities
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

        // TODO: check for errors/invalidities
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
                if ($provider == "UPS") {
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
                    );
                }
            }
        }

        return array(
            'carriers'              => $carriers,
            'ratesOptionPerPackage' => $ratesOptions
        );
        //$ratesInfo;
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

                    $package['transaction'] = $response;
                    $newPackages[$key] = $package;
                }
            }
            
            $_SESSION['packages'] = $newPackages;

            return true;
        }

        throw new Exception('Session timeout while processing shipping due to inactivity. Please repeat process.');
    }

    /**
     * Request shipping 
     */

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
}
