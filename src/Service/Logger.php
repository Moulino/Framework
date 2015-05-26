<?php 

namespace Moulino\Framework\Service;

use Moulino\Framework\Service\Config;

/**
* 
*/
class Logger
{

	private $handle;
	private $dateFormat;
	
	function __construct(Config $config) {
		$mode = $config->get('app', 'mode');
		$this->dateFormat = $config->get('app', 'date_format');
		$filepath = LOGS.DS.$mode.'.log';

		$this->handle = fopen($filepath, 'a+');
	}

	function __destruct() {
		fflush($this->handle);
		fclose($this->handle);
	}

	public function info($text) {
		$text = sprintf("INFO [%s] : %s\n", date($this->dateFormat), $text);
		fwrite($this->handle, $text);
	}

	public function error($text) {
		$text = sprintf("ERROR [%s] : %s\n", date($this->dateFormat), $text);
		fwrite($this->handle, $text);
	}
}

 ?>