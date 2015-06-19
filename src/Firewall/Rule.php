<?php 

namespace Moulino\Framework\Firewall;

/**
* 
*/
class Rule
{

	private $path;
	private $roles;
	private $methods;
	
	function __construct($path, $roles, $methods = array())
	{
		$this->path = $path;
		$this->roles = $roles;
		$this->methods = $methods;
	}

	public function getPath() {
		return $this->path;
	}

	public function getRoles() {
		return $this->roles;
	}

	public function getMethods() {
		return $this->methods;
	}

	public function hasRole($role) {
		return (in_array($role, $this->roles)) ? true : false;
	}
}

?>