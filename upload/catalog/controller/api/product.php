<?php

require_once(DIR_SYSTEM . 'services/ProductService.php');

class ControllerApiProduct extends Controller
{

    /**
     * Returns latest products
     */
    public function getLatestProducts()
    {
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $productService = ProductService::getInstance($this->config, $this->currency, $this->model_tool_image, $this->tax, $this->url);
       
        $limit = isset($this->request->post['limit']) ? $this->request->post['limit'] : 4; 

        $products = $this->model_catalog_product->getFeaturedProducts($limit);
        $products = $productService->getProductsThumbnailInfo($products, $this->customer);

        echo json_encode(array('products' => $products));
        exit;
    }

}
