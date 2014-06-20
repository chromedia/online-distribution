<?php  

require_once(DIR_SYSTEM . 'services/ProductService.php');
require_once(DIR_SYSTEM . 'utilities/StringUtil.php');
require_once(DIR_SYSTEM . 'utilities/UrlUtil.php');
require_once(DIR_SYSTEM . 'utilities/CurlUtil.php');

class ControllerCommonHome extends Controller {

    public function index() {
        /**load needed models**/
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->model('news/news');

        $this->data['news'] = $this->model_news_news->getPublishedPosts(0, 4);
        $this->data['string_util'] = StringUtil::getInstance();
        $this->data['url_util'] = UrlUtil::getInstance(CurlUtil::getInstance());

        //UrlUtil::getInstance(CurlUtil::getInstance())->shortenUrl('http://www.google.com/');exit;

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
        $productService = ProductService::getInstance($this->config, $this->currency, $this->model_tool_image, $this->tax, $this->url);
        $products = $this->model_catalog_product->getFeaturedProducts(6);

        $this->data['products'] = $productService->getProductsThumbnailInfo($products, $this->customer);
    }
}