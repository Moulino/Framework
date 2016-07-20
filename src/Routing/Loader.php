<?php 

namespace Moulino\Framework\Routing;

use Moulino\Framework\Config\Config;
use Moulino\Framework\Translation\TranslatorInterface;
use Moulino\Framework\Routing\Exception\RoutingException;

/**
* Loads the routes from the configuration file 'Parameters.php'
*/
class Loader implements LoaderInterface
{

	private $router;
	private $routes;

	private $routeDefinition;
	private $routeValidator;

	private $methods = array('GET', 'POST', 'PUT', 'DELETE');

	
	function __construct(RouterInterface $router, RouteValidator $routeValidator, array $routes) {
		$this->router = $router;
		$this->routes = $routes;

		$this->routeDefinition = require(dirname(__FILE__).DS.'Definition'.DS.'RouteDefinition.php');
		$this->routeValidator = $routeValidator;

	}

	public function load() {
		foreach ($this->routes as $index => $route) {

			extract($this->getRouteParameters($index+1, $route));

			$controller = $this->parseController($callable);
			$action = $this->parseAction($callable);

			$this->checkMethod($method);
			$this->checkCallable($controller, $action);

			$this->router->addRoute($method, $path, $controller, $action, $requirements, $ajax);
		}
	}

	private function getRouteParameters($id, $route) {
		$parameters = $this->getDefaultParameters();
		
		foreach ($route as $key => $value) {
			$parameters[$key] = $value;
		}
		
		$this->routeValidator->validate($this->routeDefinition, $id, $parameters);
		return $parameters;
	}

	private function getDefaultParameters() {
		return array(
			'requirements' => array(),
			'ajax' => false
		);
	}

	private function checkMethod($method) {
		$methods = explode('|', $method);
		foreach ($methods as $m) {
			if(!in_array($m, $this->methods)) {
				throw new RoutingException("The method '$m' is not configured");
			}
		}
	}

	private function checkCallable($controller, $action) {
		$reflector = new \ReflectionClass($controller);
		if(!$reflector->hasMethod($action)) {
			throw new RoutingException("The action '$action' is undefined in the controller '$controller'.");			
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