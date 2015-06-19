<?php 

namespace Moulino\Framework\Firewall;

use Moulino\Framework\Firewall\Rule;
use Moulino\Framework\Config\ConfigInterface;

/**
* 
*/
class Loader
{
	private $accessControl;
	private $rules;
	
	function __construct(AccessControlInterface $accessControl, $rules) {
		$this->accessControl = $accessControl;
		$this->rules = $rules;
	}

	public function load() {

		foreach ($this->rules as $rule) {
			$path    = $rule['path'];
			$roles   = explode('|', $rule['roles']);
			$methods = (array_key_exists('method', $rule)) ? explode('|', $rule['methods']) : array();

			$rule = new Rule($path, $roles, $methods);

			$this->accessControl->addRule($rule);
		}
	}
}

?>