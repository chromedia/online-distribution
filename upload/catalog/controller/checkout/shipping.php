<?php

require_once(DIR_SYSTEM . 'services/ShippoService.php');
require_once(DIR_SYSTEM . 'services/CartService.php');

class ControllerCheckoutShipping extends Controller {
    
    /**
     * Checks shipping info/rates
     */
    public function checkShippingInfo()
    {
        // 1. Confirm Address
        // 2. Prepare packages
        // 3. Get shipping speeds
        // 4. Select shipping speeds

        /*** Confirming address **/
        try {
            $toAddressData = array(
                'name' => $this->request->post['name'],
                'street1' => $this->request->post['address'],
                'city'    => $this->request->post['city'],
                'state'   => $this->request->post['state'],
                'zip'     => $this->request->post['postcode'],
                'country' => $this->request->post['country'],
                'email'   => $this->request->post['email']
            );

            $fromAddressData = array(
                'name'      => 'Laura Behrens Wu',
                'street1'   => 'Clayton St.',
                'street_no' => '220',
                'city'      => 'San Francisco',
                'state'     => 'CA',
                'zip'       => '94117',
                'country'   => 'US',
                'phone'     => '+1 555 341 9393',
                'email'     => 'floricel.colibao@gmail.com'
            );

            $shippoService = ShippoService::getInstance();

            $toAddress = $shippoService->confirmAddress($toAddressData);
            $fromAddress = $shippoService->confirmAddress($fromAddressData);
            
            $cartService = CartService::getInstance();
            $packages = $cartService->preparePackages($this->cart);

            $info = $shippoService->getShipmentInfo($packages, $fromAddress, $toAddress);

            //var_dump($info);

            $rates = array('rates' => $info);
            $_SESSION['rates'] = $info;

            echo json_encode($rates);
            exit;

        } catch(Exception $e) {
            throw $e;
        }
    }
}
