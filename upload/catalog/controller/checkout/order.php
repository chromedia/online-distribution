<?php

require_once(DIR_SYSTEM . 'services/StripeService.php');
require_once(DIR_SYSTEM . 'services/CartService.php');
require_once(DIR_SYSTEM . 'services/ShippoService.php');

/**
 * Handles order process
 */
class ControllerCheckoutOrder extends Controller {

    // TODO: Transfer to order controller
    // 1. Pay
    // 2. Ship
    // 3. Send email with transaction #, etc.

    /**
     * Handles order
     */
    public function processOrder()
    {
        // Process Order
        try {
            $cartService = CartService::getInstance();
            $shippingAmount = $cartService->getAmountOfShippingServiceRate($this->request->post['service_name']);
            $amount = $this->cart->getTotal() + $shippingAmount;
            $email = $this->request->post['customer_email'];

            $response = array();

            /*$stripeService = StripeService::getInstance();
            $charge = $stripeService->processPayment(array(
                'amount'   => (int)($amount * 100),
                'currency' => 'usd',
                'card'     => $this->request->post['token'],
                'description' => $email
            ));

            if ($charge['paid'] === true) {*/

                $shippoService = ShippoService::getInstance();
                $shippoService->requestShipping($this->request->post['service_name']);
                //TODO:  Save to database if necessary

                $cartService->emailCustomerForConfirmation($email);
                $response = array('success' => true);
            /*} else {
                $response = array('success' => false, 'errorMsg' => 'Payment System Error!');
            }*/
        } catch (Exception $e) {
            $response = array('success' => false, 'errorMsg' => $e->getMessage());
        }

        echo json_encode($response);
        exit;   
    }
}