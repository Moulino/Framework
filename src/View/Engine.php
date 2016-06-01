<?php 

namespace Moulino\Framework\View;

use Moulino\Framework\Auth\AuthenticatorInterface;
use Moulino\Framework\Config\ConfigInterface;
use Moulino\Framework\Core\Exception\TemplateNotFoundException;

class Engine implements EngineInterface
{
	private $authenticator;
	private $mode;
	private $twig; // can be used if the twig module has been installed

	const NOT_FOUND_MESSAGE = "The template file %s was not found.";

	public function __construct(AuthenticatorInterface $authenticator, $mode) {

		$this->authenticator = $authenticator;
		$this->mode = $mode;

		$this->twig = (file_exists(VENDOR.DS."twig/twig")) ? true : false;
	}

	public function render($filepath, $vars = array()) {
		$vars = array_merge($vars, [$this->mode], $this->authenticator->getAuthInfo());

		if(true === $this->twig) {
			$loader = new \Twig_Loader_Filesystem(dirname($filepath));
			$twig = new \Twig_Environment($loader, ['cache' => CACHE.DS.'twig']);
			return $twig->render(basename($filepath), $vars);
		}

		if(!file_exists($filepath)) {
			$message = sprintf(self::NOT_FOUND_MESSAGE, $filepath);
			throw new TemplateNotFoundException($message);
		}

		extract($vars);

		ob_start();
		require($filepath);
		return ob_get_clean();
	}
}

?>