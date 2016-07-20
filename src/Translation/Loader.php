<?php 

namespace Moulino\Framework\Translation;

use Moulino\Framework\Http\RequestInterface;

class Loader
{
	private $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	} 

	public function load() {
		$list = $this->getFileList();

		foreach ($list as $file) {
			$strings = require $file;
			$locale = $this->getLocaleFromFile($file);
			foreach ($strings as $key => $value) {
				$this->container->set($key, $value, $locale);
			}
		}
	}

	public function getFileList() {
		$frkdir = FRAMEWORK.DS.'Resources'.DS.'translations';
		$appdir = APP.DS.'Resources'.DS.'translations';

		$frkdirs = scandir($frkdir);
		$appdirs = scandir($appdir);
		$files = array();

		foreach ($frkdirs as $file) {
			if(!is_dir($frkdir.DS.$file) && preg_match('#messages.\w{2}\.php$#', $file)) {
				$files[] = $frkdir.DS.$file;
			}
		}

		foreach ($appdirs as $file) {
			if(!is_dir($frkdir.DS.$file) && preg_match('#messages.\w{2}\.php$#', $file)) {
				$files[] = $appdir.DS.$file;
			}
		}

		return $files;
	}

	private function getLocaleFromFile($filepath) {
		if(preg_match('#messages.(\w{2}).php$#', $filepath, $matches)) {
			return $matches[1];
		}

		throw new \Exception("The locale file is incorrect : $filepath.");
	}
}

?>