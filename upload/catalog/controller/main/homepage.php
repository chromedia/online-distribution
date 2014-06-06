<?php  

class ControllerMainHomepage extends Controller {

    public function index() {
        /**load needed models**/
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('tool/image');

        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));

        $this->data['heading_title'] = $this->config->get('config_title');
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/home.tpl';
        } else {
            $this->template = 'default/template/common/home.tpl';
        }

        // setting of products data
        $this->setLatestProducts();
        
        $this->children = array(
            'main/footer',
            'main/header'
        );
                                        
        $this->response->setOutput($this->render());
    }

    /**
     * temporary only
     */
    private function setLatestProducts()
    {
        $products = $this->model_catalog_product->getLatestProducts(10);

        foreach ($products as $product_id => $product) {
            $product_info = $this->model_catalog_product->getProduct($product_id);

            if ($product_info) {
                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'], 100, 100);
                } else {
                    $image = false;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }
                        
                if ((float)$product_info['special']) {
                    $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }
                
                if ($this->config->get('config_review_status')) {
                    $rating = $product_info['rating'];
                } else {
                    $rating = false;
                }
                    
                $this->data['products'][] = array(
                    'product_id' => $product_info['product_id'],
                    'thumb'      => $image,
                    'name'       => $product_info['name'],
                    'price'      => $price,
                    'special'    => $special,
                    'rating'     => $rating,
                    'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
                    'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                );
            }
        }
    }
}