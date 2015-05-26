<?php 

namespace Moulino\Framework\Model;

use Moulino\Framework\Database;
use Moulino\Framework\Service\Config;

Interface ModelInterface
{	
	public function add($parameters);
	public function get($criteria);
	public function set($criteria, $parameters);
	public function cget();
	public function remove($criteria);
}

?>