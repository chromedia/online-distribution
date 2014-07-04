<?php

require_once(DIR_SYSTEM . 'utilities/StringUtil.php');
require_once(DIR_SYSTEM . 'utilities/UrlUtil.php');
require_once(DIR_SYSTEM . 'utilities/CurlUtil.php');


class ControllerInformationNews extends Controller {

    /**
     * Shows all active products
     */
    public function index()
    {
        $this->load->model('news/news');

        $this->data['breadcrumbs'] = array(
            array(
                'text'      => $this->language->get('text_home'),
                'href'      => $this->url->link('common/home'),
                'separator' => false
            ),
            array(
                'text' => 'News List',
                'href' => '',
                'separator' => $this->language->get('text_separator')
            ),
        );

        $this->data['news'] = $this->model_news_news->getPublishedPosts();
        $this->data['string_util'] = StringUtil::getInstance();
        $this->data['url_util'] = UrlUtil::getInstance(CurlUtil::getInstance());

        $this->document->setTitle('OpenTech-Collaborative | News List');
                                                
        $this->children = array(
            'common/footer',
            'common/header'
        );

        $this->template =  $this->config->get('config_template').'/template/information/news_list.tpl';
                                        
        $this->response->setOutput($this->render());
    }
}