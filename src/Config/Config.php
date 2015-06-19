<?php 

namespace Moulino\Framework\Config;

use Moulino\Framework\Utils\Container\ContainerInterface;
use Moulino\Framework\Utils\Container\ContainerHandler;

class Config
{
	static private $container;

	static public function loadConfigFile($filepath) {
		self::$container = require $filepath;
	}

	static public function isDefined($key) {
		return ContainerHandler::isDefined(self::$container, $key);
	}

	static public function get($key) {
		return ContainerHandler::get(self::$container, $key);
	}

	static public function set($key, $value) {
		return ContainerHandler::set(self::$container, $key);
	}
}

?>