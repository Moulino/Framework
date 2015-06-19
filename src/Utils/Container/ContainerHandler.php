<?php 

namespace Moulino\Framework\Utils\Container;

class ContainerHandler
{
	static public function isDefined($container, $key) {
		$args = explode('.', $key);

		$data = $container;
		foreach ($args as $arg) {
			if(isset($data[$arg])) {
				$data = $data[$arg];
			} else {
				return false;
			}
		}
		return true;
	}

	static public function get($container, $key) {
		if(!strlen($key)) {
			return $container;
		}

		$args = explode('.', $key);

		$data = $container;
		foreach ($args as $arg) {
			$data = $data[$arg];
		}

		return $data;
	}

	static public function set($container, $key, $value) {
		if(!strlen($key)) {
			$container = $value;
		}
		
		$args = explode('.', $key);

		$data = & $container;
		foreach ($args as $arg) {

			// check if the argument is undefined
			if(!isset($data[$arg])) {
				// checks if the argument of array is a container or a attribut name
				if(array_slice($container, -1, 1) === $arg) {
					$data[$arg] = '';
				} else {
					$data[$arg] = array();
				}
			}

			$data = & $data[$arg];
		}

		$data = $value;
	}
}

?>