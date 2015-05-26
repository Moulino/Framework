<?php 

namespace Moulino\Framework\Controller;

use Moulino\Framework\DependencyInjection\DIContainer as Dic;
use Moulino\Framework\Authenticator;
use Moulino\Framework\Http\Response;
	
Interface ControllerInterface 
{
	public function render($view, $vars = array());
}
?>