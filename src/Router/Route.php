<?php 

namespace Moulino\Framework\Router;


use Moulino\Framework\Http\Request;

/**
* Represents the route corresponding to the uri
*/
class Route
{

	private $path;
	private $controller;
	private $action;
	private $requirements;
	private $arguments;
	
	function __construct($path, $controller, $action, $requirements = array()) {
		$this->path = $path;
		$this->controller = $controller;
		$this->action = $action;
		$this->requirements = $requirements;
	}

	/**
	 * Checks if the route match the uri
	 * @param $uri Uri
	 */
	public function match($uri) {
		$path = preg_replace_callback('#:([\w]+)#', array($this, 'paramToSequence'), $this->path);
		$regex = '#^'.$path.'$#';

		if(preg_match($regex, $uri, $matches)) {
			array_shift($matches);
			$this->arguments = $matches;
			return true;
		}
		return false;
	}

	/**
	 * Converts a param of the path (:param) to a regex sequence
	 * @param $param Param of the path
	 * @return Regex sequence
	 */
	private function paramToSequence($param) {
		$param = $param[1];
		if(array_key_exists($param, $this->requirements)) {
			return '('.$this->requirements[$param].')';
		}
		return '([\w]+)';
	}

	/**
	 * Call the function associated with this route
	 * @return returns the function result
	 */
	public function call(Request $request) {
		$arguments = $this->arguments;
		array_unshift($arguments, $request);
		return call_user_func_array(array(new $this->controller(), $this->action), $arguments);
	}
} 

?>