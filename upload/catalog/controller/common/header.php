<?php   
class ControllerCommonHeader extends Controller {
    protected function index() {
        // $this->data['title'] = $this->document->getTitle();

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        // if (isset($this->session->data['error']) && !empty($this->session->data['error'])) {
        //     $this->data['error'] = $this->session->data['error'];

        //     unset($this->session->data['error']);
        // } else {
        //     $this->data['error'] = '';
        // }

        // $this->data['base'] = $server;
        // $this->data['description'] = $this->document->getDescription();
        // $this->data['keywords'] = $this->document->getKeywords();
        // $this->data['links'] = $this->document->getLinks();  
        // $this->data['styles'] = $this->document->getStyles();
        // $this->data['scripts'] = $this->document->getScripts();
        // $this->data['lang'] = $this->language->get('code');
        // $this->data['direction'] = $this->language->get('direction');
        // $this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        // $this->data['name'] = $this->config->get('config_name');

        // if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
        //     $this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
        // } else {
        //     $this->data['icon'] = '';
        // }

        // if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
        //     $this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        // } else {
        //     $this->data['logo'] = '';
        // }       

        // $this->language->load('common/header');


        // // Daniel's robot detector
        // $status = true;

        // if (isset($this->request->server['HTTP_USER_AGENT'])) {
        //     $robots = explode("\n", trim($this->config->get('config_robots')));

        //     foreach ($robots as $robot) {
        //         if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
        //             $status = false;

        //             break;
        //         }
        //     }
        // }

        // // A dirty hack to try to set a cookie for the multi-store feature
        // $this->load->model('setting/store');

        // $this->data['stores'] = array();

        // if ($this->config->get('config_shared') && $status) {
        //     $this->data['stores'][] = $server . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();

        //     $stores = $this->model_setting_store->getStores();

        //     foreach ($stores as $store) {
        //         $this->data['stores'][] = $store['url'] . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
        //     }
        // }

        // // Search       
        // if (isset($this->request->get['search'])) {
        //     $this->data['search'] = $this->request->get['search'];
        // } else {
        //     $this->data['search'] = '';
        // }

        // // Menu
        // $this->load->model('catalog/category');

        // $this->load->model('catalog/product');

        // $this->data['categories'] = array();

        // $categories = $this->model_catalog_category->getCategories(0);

        // foreach ($categories as $category) {
        //     if ($category['top']) {
        //         // Level 2
        //         $children_data = array();

        //         $children = $this->model_catalog_category->getCategories($category['category_id']);

        //         foreach ($children as $child) {
        //             $data = array(
        //                 'filter_category_id'  => $child['category_id'],
        //                 'filter_sub_category' => true
        //             );

        //             $product_total = $this->model_catalog_product->getTotalProducts($data);

        //             $children_data[] = array(
        //                 'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
        //                 'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
        //             );                      
        //         }

        //         // Level 1
        //         $this->data['categories'][] = array(
        //             'name'     => $category['name'],
        //             'children' => $children_data,
        //             'column'   => $category['column'] ? $category['column'] : 1,
        //             'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
        //         );
        //     }
        // }

        // if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
        //     $this->data['logo'] = $server  . 'image/' . $this->config->get('config_logo');
        // } else {
        //     $this->data['logo'] = 'catalog/view/theme/chromedia/image/ICON-LOGO.png';
        // }

        $this->data['logo'] = 'catalog/view/theme/chromedia/image/ICON-LOGO.png';

        $this->data['sticky_header'] = true;

        if ("information/learnmore" == $this->request->get['route']) {
            $this->data['sticky_header'] = false;
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/header.tpl';
        } else {
            $this->template = 'default/template/common/header.tpl';
        }

        $this->render();
    }   
}
