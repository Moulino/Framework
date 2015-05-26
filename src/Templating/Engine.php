<?php 

namespace Moulino\Framework\Templating;

use Moulino\Framework\Service\Authenticator;
use Moulino\Framework\Http\Response;

class Engine implements EngineInterface
{
	private $authenticator;

	public function __construct(Authenticator $authenticator) {
		$this->authenticator = $authenticator;
	}

	public function render($view, $vars = array()) {
		$content = $this->getViewContent($view, $vars);
		return new Response($content);
	}

	private function getViewContent($view, $vars = array()) {
		extract($this->authenticator->getAuthInfo());
		
		$folder = ucfirst(strstr($view, ':', true));
		$file = substr(strstr($view, ':'), 1).'.php';

		extract($vars);

		ob_start();
		require(VIEW.DS.$folder.DS.$file);
		$content = ob_get_clean();

		return $content;
		return '';
	}
}

?>