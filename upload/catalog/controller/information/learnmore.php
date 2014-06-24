<?php

class ControllerInformationLearnMore extends Controller {

    public function index()
    {
        $this->template =  $this->config->get('config_template').'/template/information/learn_more.tpl';
        $this->children = array(
            'common/footer',
            'common/header'
        );
                                        
        $this->response->setOutput($this->render());
    }
}