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
	 * Resolve the route from the request uri
	 * @param $request Moulino\Framework\Http\Request request http
	 * @return Moulino\Framework\Http\Response
	 */
	public function resolve(Request $request) {
		$method = $request->getMethod();
		$uri = $request->getUri();

		foreach ($this->routes[$method] as $route) {
			if($route->match($uri)) {
				return $route;
			}
		}

		throw new NoRouteFoundException("No routes matches the uri '$uri'.");
	}


	public function addRoute($method, $path, $controller, $action, $requirements = array(), $ajax = false) {
		$this->routes[$method][] = new Route($path, $controller, $action, $requirements, $ajax);
	}
}

?>