<?php

require_once(DIR_SYSTEM . 'services/ProductService.php');

class ControllerProductDisplay extends Controller {

    /**
     * Shows all active products
     */
    public function all()
    {
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $this->data['breadcrumbs'] = array(
            array(
                'text'      => $this->language->get('text_home'),
                'href'      => $this->url->link('common/home'),
                'separator' => false
            ),
            array(
                'text' => 'Product List',
                'href' => '',
                'separator' => $this->language->get('text_separator')
            ),
        );

        $productService = ProductService::getInstance($this->config, $this->currency, $this->model_tool_image, $this->tax, $this->url);
        $products = $this->model_catalog_product->getProducts();

        $this->data['products'] = $productService->getProductsThumbnailInfo($products, $this->customer, $this->url);

        $this->document->setTitle('OpenTech-Collaborative | Product List');
        $this->data['heading_title'] = 'All Products';

        $this->template =  $this->config->get('config_template').'/template/product/product_list.tpl';
        
        $this->children = array(
            'common/footer',
            'common/header'
        );
                                        
        $this->response->setOutput($this->render());
    }
}