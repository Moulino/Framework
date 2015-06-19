<?php 

namespace Moulino\Framework\Utils\Container;

interface ContainerInterface
{
	public function isDefined($key);
	public function get($key);
	public function set($key, $value);
}

?>