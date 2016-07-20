<?php 

namespace Moulino\Framework\Service;

/**
* Dependency definition class
*/
class Definition
{
	private $class;
	private $dependencies;

	public function __construct($class, $dependencies = array()) {
		if(is_array($class)) {
			$className = $class[0];
			$method = $class[1];
			$params = (isset($class[2]) && is_array($class[2])) ? $class[2] : [];

			$this->class = call_user_func_array(array($class[0], $class[1]), $params);
		} else {
			$this->class = $class;
		}

		$this->dependencies = $dependencies;
	}

	public function getClass() {
		return $this->class;
	}

	public function getDependencies() {
		return $this->dependencies;
	}
}

?>