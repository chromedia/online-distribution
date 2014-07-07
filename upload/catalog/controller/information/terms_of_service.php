<?php

class ControllerInformationTermsOfService extends Controller {

    public function index()
    {
        $this->template =  $this->config->get('config_template').'/template/information/terms_of_service.tpl';
        $this->children = array(
            'common/footer',
            'common/header'
        );
                                        
        $this->response->setOutput($this->render());
    }
}