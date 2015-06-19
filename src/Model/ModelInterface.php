<?php 

namespace Moulino\Framework\Model;

Interface ModelInterface
{	
	public function add($parameters);
	public function get($criteria);
	public function set($criteria, $parameters);
	public function cget();
	public function remove($criteria);
}

?>