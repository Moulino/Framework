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
		$this->class = $class;
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