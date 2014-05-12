<?php
final class Front {
	protected $registry;
	protected $pre_action = array();
	protected $error;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function addPreAction($pre_action) {
		$this->pre_action[] = $pre_action;
	}

	public function dispatch($action, $error) {
		$this->error = $error;

		// Execute each pre_action
		foreach ($this->pre_action as $pre_action) {
			$result = $this->execute($pre_action);

			if ($result) {
				$action = $result;

				break;
			}
		}

		while ($action) {
			$action = $this->execute($action);
		}
	}

	private function execute($action) {

		// Load action file
		if (file_exists($action->getFile())) {
			require_once($action->getFile());

			// Get action class
			$class = $action->getClass();

			// Create action object with registry
			$object = new $class($this->registry);

			// Store action method with arguments
			if (is_callable(array($object, $action->getMethod()))) {
				$action = call_user_func_array(array($object, $action->getMethod()), $action->getArgs());
			} else {
				$action = $this->error;

				$this->error = '';
			}

		// Error if file not found	
		} else {
			$action = $this->error;

			$this->error = '';
		}

		return $action;
	}
}
?>