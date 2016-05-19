<?php 

namespace Moulino\Framework\Log;

/**
* 
*/
class Logger implements LoggerInterface
{
	private $mailer;
	private $config;
	private $handle;
	private $dateFormat;
	
	function __construct($mailer, $config, $mode, $dateFormat) {
		$this->mailer = $mailer;
		$this->config = $config;
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
		$this->sendMail($text);
	}

	public function warning($text) {
		$text = sprintf("WARNING [%s] : %s\n", date($this->dateFormat), $text);
		fwrite($this->handle, $text);
		$this->sendMail($text);
	}

	public function error($text) {
		$text = sprintf("ERROR [%s] : %s\n", date($this->dateFormat), $text);
		fwrite($this->handle, $text);
		$this->sendMail($text);
	}

	private function sendMail($text) {
		$sender   = $this->config['sender'];
		$receiver = $this->config['receiver'];
		$subject  = $this->config['subject'];
		$boundary = $this->mailer->generateBoundary();

		$this->mailer->send($sender, $receiver, $subject, $text, $boundary);

	}
}

?>