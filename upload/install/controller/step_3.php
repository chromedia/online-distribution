<?php
class ControllerStep3 extends Controller {
	public function index() {
		$this->template = 'step_3.tpl';
		$this->children = array(
			'header',
			'footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>