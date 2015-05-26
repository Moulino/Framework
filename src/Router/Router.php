<?php 

namespace Moulino\Framework\Router;

use Moulino\Framework\Http\Request;
use Moulino\Framework\Router\Route;

use Moulino\Framework\Exception\RouterException;

/**
* 
*/
class Router
{

	private $routes = array();

	/**
	 * Resolve the route from the request uri
	 * @param $request Moulino\Framework\Http\Request request http
	 * @return Moulino\Framework\Http\Response
	 */
	public function resolve(Request $request) {
		$method = $request->getMethod();

		foreach ($this->routes[$method] as $route) {
			if($route->match($request->getUri())) {
				return $route;
			}
		}

		throw new RouterException("No routes matches the uri '$request->getUri()'.");
	}


	public function addRoute($method, $path, $controller, $action, $requirements = array()) {
		$this->routes[$method][] = new Route($path, $controller, $action, $requirements);
	}
}

?>