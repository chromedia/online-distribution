<?php  

require_once(DIR_SYSTEM . 'services/ProductService.php');

class ControllerCommonHome extends Controller {

    public function index() {
        /**load needed models**/
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));

        $this->data['heading_title'] = $this->config->get('config_title');

        $this->template =  $this->config->get('config_template').'/template/home/home.tpl';
        
        // setting of products data
        $this->setFeaturedProductsInfo();
        $this->data['button_cart'] = $this->language->get('button_cart');
        
        $this->children = array(
            'common/footer',
            'common/header'
        );
                                        
        $this->response->setOutput($this->render());
    }

    /**
     * Setting of featured products info
     */
    private function setFeaturedProductsInfo()
    {
        $productService = ProductService::getInstance($this->config, $this->currency, $this->model_tool_image, $this->tax);
        $products = $this->model_catalog_product->getFeaturedProducts(6);

        $this->data['products'] = $productService->getProductsThumbnailInfo($products, $this->customer, $this->url);

        /*foreach($products as $product) {
            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], 301, 170);
            } else {
                $image = false;
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            // echo $product['product_id'].": ".strip_tags(html_entity_decode($product['description']))."<br/><br/><br/><br/>";
                
            $this->data['products'][] = array(
                'product_id'  => $product['product_id'],
                'description' => StringUtil::truncateString(strip_tags(html_entity_decode($product['description'])), 100),
                'thumb'       => $image,
                'name'        => $product['name'],
                'price'       => $price,
                'href'        => $this->url->link('product/product', 'product_id=' . $product['product_id'])
            );
        }*/
    }
}