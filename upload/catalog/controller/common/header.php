<?php
class ControllerCommonHeader extends Controller {
    protected function index() {
        // $this->data['title'] = $this->document->getTitle();

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->data['logo'] = 'catalog/view/theme/chromedia/image/ICON_LOGO.png';

        $this->data['sticky_header'] = true;
        $currentRoute = isset($this->request->get['route']) ? $this->request->get['route'] : '';

        $this->load->model('catalog/product');
        $inDevelopmentProducts = $this->model_catalog_product->getInDevelopmentProducts();

        $this->data['no_collaborate_link'] = false;

        if (count($inDevelopmentProducts) == 0) {
            $this->data['no_collaborate_link'] = true;
        }

        /* if ("information/learnmore" == $currentRoute) {
            $this->data['sticky_header'] = false;
        } */

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/header.tpl';
        } else {
            $this->template = 'default/template/common/header.tpl';
        }

        $this->render();
    }
}
