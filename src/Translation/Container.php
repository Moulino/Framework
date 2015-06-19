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

	public function translate($string) {
		if(!$this->isDefined($string)) {
			$this->logger->warning("Translation unfound : ".$string);
			return $string;
		}
		return $this->get($string);
	}

	public function isDefined($key) {
		return isset($this->container[$key]);
	}

	public function set($key, $value) {
		$this->container[$key] = $value;
	}

	public function get($key) {
		return $this->container[$key];
	}
}

?>