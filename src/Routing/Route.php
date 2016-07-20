<?php 

namespace Moulino\Framework\Routing;

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
	private $ajax;
	
	function __construct($path, $controller, $action, $requirements = array(), $ajax = false) {
		$this->path = $path;
		$this->controller = $controller;
		$this->action = $action;
		$this->requirements = $requirements;
		$this->ajax = $ajax;
	}

	/**
	 * Checks if the route match the request path
	 * @param $path Uri
	 */
	public function match($requestPath) {
		$path = preg_replace_callback('#:([\w]+)#', array($this, 'paramToSequence'), $this->path);
		$regex = '#'.$path.'#';

		if(preg_match($regex, $requestPath, $matches)) {
			array_shift($matches);
			$argKeys = $this->argumentKeys();

			$this->arguments = array_combine($argKeys, $matches);
			return true;
		}
		return false;
	}

	private function argumentKeys() {
		$regex = "/:([\w]+)/";
		$keys = array();

		if(preg_match_all($regex, $this->path, $matches)) {
			$keys = $matches[1];
		}
		return $keys;
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
		if(array_key_exists('locale', $this->arguments)) {
			$request->setLocale($this->arguments['locale']);
			unset($this->arguments['locale']);
		}

		$arguments = array_values($this->arguments);
		array_unshift($arguments, $request);

		return call_user_func_array(array(new $this->controller(), $this->action), $arguments);
	}

	/**
	 * Returns true if the request is in ajax.
	 */
	public function isAjax() {
		return $this->ajax;
	}
} 

?>