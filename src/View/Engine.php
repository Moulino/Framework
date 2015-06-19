<?php 

namespace Moulino\Framework\View;

use Moulino\Framework\Auth\AuthenticatorInterface;
use Moulino\Framework\Config\ConfigInterface;
use Moulino\Framework\Core\Exception\TemplateNotFoundException;

class Engine implements EngineInterface
{
	private $authenticator;
	private $mode;

	const NOT_FOUND_MESSAGE = "The template file %s was not found.";

	public function __construct(AuthenticatorInterface $authenticator, $mode) {

		$this->authenticator = $authenticator;
		$this->mode = $mode;
	}

	public function render($filepath, $vars = array()) {
		if(!file_exists($filepath)) {
			$message = sprintf(self::NOT_FOUND_MESSAGE, $filepath);
			throw new TemplateNotFoundException($message);
		}

		$mode = $this->mode;
		extract($this->authenticator->getAuthInfo());
		extract($vars);

		ob_start();
		require($filepath);
		return ob_get_clean();
	}
}

?>