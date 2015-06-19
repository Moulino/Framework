<?php 

namespace Moulino\Framework\Log;

/**
* 
*/
class Logger implements LoggerInterface
{

	private $handle;
	private $dateFormat;
	
	function __construct($mode, $dateFormat) {
		$this->dateFormat = $dateFormat;
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

	public function warning($text) {
		$text = sprintf("WARNING [%s] : %s\n", date($this->dateFormat), $text);
		fwrite($this->handle, $text);
	}

	public function error($text) {
		$text = sprintf("ERROR [%s] : %s\n", date($this->dateFormat), $text);
		fwrite($this->handle, $text);
	}
}

?>