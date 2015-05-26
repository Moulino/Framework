<?php 

namespace Moulino\Framework\Templating;

interface EngineInterface 
{
	public function render($view, $vars = array());
}

?>