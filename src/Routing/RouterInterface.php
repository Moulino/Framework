<?php 

namespace Moulino\Framework\Routing;

use Moulino\Framework\Http\Request;

/**
* Interface for router class
*/
interface RouterInterface
{
	/**
	 * Resolve the route from the request uri
	 * @param $request Moulino\Framework\Http\Request request http
	 * @return Moulino\Framework\Http\Response
	 */
	public function resolve(Request $request);

	public function addRoute($method, $path, $controller, $action, $requirements = array());
}

?>