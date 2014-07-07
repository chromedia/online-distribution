<?php

require_once(DIR_SYSTEM . 'utilities/VideoUtilTypeFactory.php');
require_once(DIR_SYSTEM . 'services/ProductService.php');


class ControllerCollaborateProducts extends Controller {


    public function index() {
        $this->language->load('product/product');
        $this->load->model('tool/image');

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->load->model('catalog/product');

        if (isset($this->request->get['product_id'])) {
            $product_id = (int)$this->request->get['product_id'];

            $this->displayProduct($product_id);

            if (!isset($this->data['header_img']) || empty($this->data['header_img'])) {
                $this->data['header_img'] = $this->model_tool_image->resize('no_image.jpg', 300, 100);
            }
        } else {
            $this->inDevelopmentProductList();
        }        

        $this->children = array(
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }

    /**
     * Displays in-development product information
     */ 
    protected function displayProduct($product_id)
    {
        if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == $this->url->link('collaborate/products')) {
            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_active_projects'),
                'href'      => $this->url->link('collaborate/products'),
                'separator' => $this->language->get('text_separator')
            );
        }

        $product_info = $this->model_catalog_product->getInDevelopmentProductInfo($product_id);
        // In development
        if ($product_info && $product_info['status'] == ModelCatalogProduct::IN_DEVELOPMENT) {
            $url = '';

            $this->data['breadcrumbs'][] = array(
                'text'      => $product_info['name'],
                'href'      => '',
                'separator' => $this->language->get('text_separator')
            );

            if ($product_info['image']) {
                $this->data['header_img'] = $this->model_tool_image->resize($product_info['image'], 700, 200);
            } else {
                $this->data['header_img'] = '';
            }

            $this->data['heading_title'] = $product_info['name'];
            
            $this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
            $this->data['details'] = html_entity_decode($product_info['details'], ENT_QUOTES, 'UTF-8');
            $this->data['documentation'] = html_entity_decode($product_info['documentation'], ENT_QUOTES, 'UTF-8');

            $this->data['video_tag'] = '';

            if (isset($product_info['video']['videoKey']) && $product_info['video']['videoKey']) {
                $factory = VideoUtilTypeFactory::getInstance($product_info['video']['url']);
                $videoUtil = $factory->getVideoUtility();

                $this->data['video_tag'] = $videoUtil->getVideoEmbedTag($product_info['video']['videoKey']);
            }

            $this->model_catalog_product->updateViewed($this->request->get['product_id']);

            $this->template = $this->config->get('config_template') . '/template/collaborate/product.tpl';
        } else {
            return $this->redirect($this->url->link('error/not_found', '', 'SSL'));
        }
    }

    /**
     * Displays in-development product list
     */
    protected function inDevelopmentProductList()
    {
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_active_projects'),
            'href'      => '',
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = 'Active Projects';

        $productService = ProductService::getInstance($this->config, $this->currency, $this->model_tool_image, $this->tax, $this->url);
        $products = $this->model_catalog_product->getInDevelopmentProducts();

        $this->data['products'] = $productService->getProductsThumbnailInfo($products, $this->customer);

        $this->template = $this->config->get('config_template') . '/template/collaborate/product_list.tpl';
    }

}
