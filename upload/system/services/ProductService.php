<?php

require_once(DIR_SYSTEM . 'utilities/StringUtil.php');

/**
 * Provides services for product
 */
class ProductService
{
    private static $instance;
    
    /**
     * Returns instance
     */
    public static function getInstance($config, $currency, $imageTool = null, $tax = null)
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductService($config, $currency, $imageTool, $tax);
        }

        return self::$instance;
    }

    /**
     * Instantiates this service class
     */
    public function __construct($config, $currency, $imageTool = null, $tax = null)
    {
        $this->config = $config;
        $this->imageTool = $imageTool;
        $this->currency = $currency;
        $this->tax = $tax;
    }

    /**
     * Get product thumbnail info
     */
    public function getProductsThumbnailInfo($products, $customer, $url)
    {
        $data = array();

        foreach($products as $product) {
            if ($product['image']) {
                $image = $this->imageTool->resize($product['image'], 301, 170);
            }

            if (!isset($image) || empty($image) || is_null($image)) {
                $image = $this->imageTool->resize('no_image.jpg', 301, 170);
            }

            if (($this->config->get('config_customer_price') && $customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            $stringUtil = StringUtil::getInstance();
                
            $data[] = array(
                'product_id'  => $product['product_id'],
                'description' => $stringUtil->truncateString(strip_tags(html_entity_decode($product['description'])), 100),
                'thumb'       => $image,
                'name'        => $product['name'],
                'price'       => $price,
                'href'        => $url->link('product/product', 'product_id=' . $product['product_id'])
            );
        }

        return $data;
    }

    /**
     * Returns product cart info
     */
    public function getProductCheckoutInfo($products)
    {
        $data = array();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['image']) {
                $image = $this->imageTool->resize($product['image'], 55, 55/*$this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')*/);
            }

            if (!isset($image) || empty($image) || is_null($image)) {
                $image = $this->imageTool->resize('no_image.jpg', 55, 55);
            }

            $price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
            $total = $price * $product['quantity'];//$product['price'] * $product['quantity'];
            // $cartTotalPrice += $total;

            $data[] = array(
                'id'                  => $product['product_id'],
                'key'                 => $product['key'],
                'thumb'               => $image,
                'name'                => $product['name'],
                'quantity'            => $product['quantity'],
                'price'               => $this->currency->format($price),
                'total'               => $this->currency->format($total)
            );
        }

        return $data;
    }
}
