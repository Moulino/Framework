<?php 

namespace Moulino\Framework\Router;

use Moulino\Framework\Exception\RouterException;
use Moulino\Framework\Service\Config;

/**
* Loads the routes from the configuration file 'Parameters.php'
*/
class RouteLoader
{

	private $router;
	private $config;

	private $methods = array('GET', 'POST', 'DELETE');

	
	function __construct(Router $router, Config $config) {
		$this->router = $router;
		$this->config = $config;
	}

	public function load() {
		$routes = $this->config->get('routes');

		foreach ($routes as $route) {
			$path = $route['path'];
			$method = strtoupper($route['method']);
			$controller = $this->parseController($route['callable']);
			$action = $this->parseAction($route['callable']);
			$requirements = isset($route['requirements']) ? $route['requirements'] : array();

			$this->checkMethod($method);
			$this->checkCallable($controller, $action);

			$this->router->addRoute($method, $path, $controller, $action, $requirements);
		}
	}

	private function checkMethod($method) {
		if(!in_array($method, $this->methods)) {
			throw new RouterException("The method '$method' is not configured");
		}
	}

	private function checkCallable($controller, $action) {
		$reflector = new \ReflectionClass($controller);
		if(!$reflector->hasMethod($action)) {
			throw new RouterException("The action '$action' is undefined in the controller '$controller'.");			
		}
	}

	private function parseController($callable) {
		$controllerName = ucfirst(strstr($callable, ':', true)).'Controller';
		return 'App\\Controller\\'.$controllerName;
	}

	private function parseAction($callable) {
		return strtolower(ltrim(strstr($callable, ':'), ':')).'Action';
	}
}

?>