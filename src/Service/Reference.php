<?php 

namespace Moulino\Framework\Service;

/**
* Dependency service reference class
*/
class Reference
{
	private $alias;

	public function __construct($alias) {
		$this->alias = $alias;
	}

	public function getAlias() {
		return $this->alias;
	}
}

?>