<?php 

namespace Moulino\Framework\Routing;

use Moulino\Framework\Http\Request;

use Moulino\Framework\Routing\Exception\NoRouteFoundException;

/**
* 
*/
class Router implements RouterInterface
{

	private $routes = array();

	/**
	 * Resolve the route from the request path
	 * @param $request Moulino\Framework\Http\Request request http
	 * @return Moulino\Framework\Http\Response
	 */
	public function resolve(Request $request) {
		$method = $request->getMethod();
		$path = $request->getPath();

		foreach ($this->routes[$method] as $route) {
			if($route->match($path)) {
				return $route;
			}
		}

		throw new NoRouteFoundException("No routes matches the request path '$path'.");
	}


	public function addRoute($method, $path, $controller, $action, $requirements = array(), $ajax = false) {
		$this->routes[$method][] = new Route($path, $controller, $action, $requirements, $ajax);
	}
}

?>