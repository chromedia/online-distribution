<?php

class ControllerApiCart extends Controller {

    public function countProducts()
    {
        echo json_encode(array('productsCount' => $this->cart->countProducts()));
        exit;
    }

}
