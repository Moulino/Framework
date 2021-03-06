<?php 

namespace Moulino\Framework\View;

use Moulino\Framework\Service\Container;
use Moulino\Framework\Service\Loader;
use Moulino\Framework\Config\ConfigInterface;
use Moulino\Framework\Core\Exception\TemplateNotFoundException;
use Moulino\Framework\Twig\Extension;

class Engine implements EngineInterface
{
	private $container;
	private $authenticator;
	private $mode;
	private $twig; // can be used if the twig module has been installed
	private $locale;
	private $viewParameters;

	const NOT_FOUND_MESSAGE = "The template file %s was not found.";

	public function __construct(Container $container, $viewParameters, $mode) {

		$this->container = $container;
		$this->authenticator = $container->get('authenticator');
		$this->mode = $mode;
		$this->locale = $container->get('request')->getLocale();
		$this->viewParameters = $viewParameters;

		$this->twig = (file_exists(VENDOR.DS."twig/twig")) ? true : false;
	}

	public function render($filepath, $vars = array()) {
		$vars = array_merge($vars, ['mode' => $this->mode, 'locale' => $this->locale ], $this->authenticator->getAuthInfo());

		if('php' === $this->getExtension($filepath)) {
			extract($vars);

			ob_start();
			require($filepath);
			return ob_get_clean();
		}

		if(true === $this->twig) {
			$loader = new \Twig_Loader_Filesystem(dirname($filepath));
			$options = array();

			if('prod' === $this->mode) {
				$options['cache'] = CACHE.DS.'twig';
			} else {
				$options['debug'] = true;
			}

			$twig = new \Twig_Environment($loader, $options);
			$twig->addExtension(new Extension\UrlExtension($this->container));
			$twig->addExtension(new Extension\AssetExtension($this->container));
			$twig->addExtension(new Extension\TranslateExtension($this->container->get('translator')));
			$twig->addExtension(new Extension\LangExtension($this->container->get('request')));

			// load the user extensions
			foreach ($this->viewParameters['twig_extensions'] as $alias => $def) {
				$definition = Loader::getDefinitionObject($def);
				$extension = $this->container->instantiate($definition);
				$twig->addExtension($extension);
			}

			if('dev' == $this->mode) {
				$twig->addExtension(new \Twig_Extension_Debug());
			}
			
			return $twig->render(basename($filepath), $vars);
		}

		$message = sprintf(self::NOT_FOUND_MESSAGE, $filepath);
		throw new TemplateNotFoundException($message);	
	}

	private function getExtension($filepath) {
		return pathinfo($filepath, PATHINFO_EXTENSION);
	}
}

?>