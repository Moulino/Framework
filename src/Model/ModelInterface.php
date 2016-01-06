<?php 

namespace Moulino\Framework\Model;

Interface ModelInterface
{	
	public function add($parameters);
	public function get($criteria, $filters);
	public function set($criteria, $parameters);
	public function cget($criteria, $filters);
	public function remove($criteria);
}

?>