<?php 

namespace Moulino\Framework\Translation;

use Moulino\Framework\Log\LoggerInterface;

use Moulino\Framework\Translation\ContainerInterface as TranslationContainerInterface;

class Container implements TranslationContainerInterface
{
	private $logger;
	private $container;

	public function __construct(LoggerInterface $logger) {
		$this->logger = $logger;
	}

	public function translate($string, $locale) {
		if(!$this->isDefined($string, $locale)) {
			//$this->logger->warning("Translation unfound : ".$string);
			return $string;
		}
		return $this->get($string, $locale);
	}

	public function isDefined($key, $locale) {
		return isset($this->container[$locale][$key]);
	}

	public function set($key, $value, $locale) {
		$this->container[$locale][$key] = $value;
	}

	public function get($key, $locale) {
		return $this->container[$locale][$key];
	}
}

?>