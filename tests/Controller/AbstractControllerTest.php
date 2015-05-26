<?php 

namespace Moulino\Tests\Framework\Controller;

use Moulino\Framework\DependencyInjection\DIContainer as Dic;
	
abstract class AbstractControllerTest
{
	protected $dic;
	public $modelParameters = array();

	public function __construct() {
		$this->dic = Dic::getInstance();
	}

	public function getModel($modelName) {
		return $this->dic->getModel($modelName);
	}

	public function getService($serviceName) {
		return $this->dic->getService($serviceName);
	}

	public function generateSetParameters($value) {
		$parameters = array();
		foreach($this->modelParameters as $modelParameter) {
			$parameters[$modelParameter] = 'test_'.$value.'_'.$modelParameter;
		}
		return $parameters;
	}

	public function setRequest($request, $parameters, $method = 'GET') {
		foreach ($parameters as $key => $value) {
			$request->setParameter($key, $value, $method);
		}
	}
}
?>