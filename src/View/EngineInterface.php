<?php 

namespace Moulino\Framework\View;

interface EngineInterface 
{
	public function render($view, $vars = array());
}

?>