<?php 

namespace Moulino\Framework\Service;

use Moulino\Framework\Utils\Container\ContainerInterface;
use Moulino\Framework\Utils\Container\ContainerHandler;

class Config implements ContainerInterface
{
	private $container;

	public function __construct($filepath) {
		$this->loadConfigFile($filepath);
	}

	public function loadConfigFile($filepath) {
		$this->container = require $filepath;
	}

	public function isDefined($key) {
		return ContainerHandler::isDefined($this->container, $key);
	}

	public function get($key) {
		return ContainerHandler::get($this->container, $key);
	}

	public function set($key, $value) {
		return ContainerHandler::set($this->container, $key, $value);
	}
}

?>