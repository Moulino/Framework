<?php 

namespace Moulino\Framework\Translation;

class Loader
{
	private $container;
	private $language;

	public function __construct(ContainerInterface $container, $language) {
		$this->container = $container;
		$this->language = $language;
	} 

	public function load() {
		$list = $this->getFileList();

		foreach ($list as $file) {
			$strings = require $file;
			foreach ($strings as $key => $value) {
				$this->container->set($key, $value);
			}
		}
	}

	public function getFileList() {
		return array(
			FRAMEWORK.DS.'Resources'.DS.'translations'.DS.'messages.'.$this->language.'.php',
			APP.DS.'Resources'.DS.'translations'.DS.'messages.'.$this->language.'.php'
		);
	}
}

?>