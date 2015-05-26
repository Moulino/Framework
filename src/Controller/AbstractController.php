<?php 

namespace Moulino\Framework\Controller;

use Moulino\Framework\DependencyInjection\DIContainer as Dic;
use Moulino\Framework\Service\Authenticator;
use Moulino\Framework\Http\Response;
use Moulino\Framework\Templating\EngineInterface;
	
abstract class AbstractController implements ControllerInterface
{
	protected $dic;
	protected $templating;

	public function __construct() {
		$this->dic = Dic::getInstance();
		$this->templating = $this->dic->getTemplating();
	}

	public function getModel($modelName) {
		return $this->dic->getModel($modelName);
	}

	public function getService($serviceName) {
		return $this->dic->getService($serviceName);
	}

	public function render($view, $vars = array()) {
		return $this->templating->render($view, $vars);
	}
}
?>