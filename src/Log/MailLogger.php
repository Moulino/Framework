<?php

namespace Moulino\Framework\Log;

class MailLogger implements MailLoggerInterface
{
	private $handle;
	private $dateFormat;

	function __construct($dateFormat) {
		$this->dateFormat = $dateFormat;
		$filepath = LOGS.DS."mail.log";

		$this->handle = fopen($filepath, 'a+');
	}

	function __destruct() {
		fflush($this->handle);
		fclose($this->handle);
	}

	public function info($sender, $receiver, $subject, $message) {
		$text = "Mail sent : [Sender='$sender'] [RECEIVER='$receiver'] [SUBJECT='$subject'] [MESSAGE='$message'].";
		fwrite($this->handle, $text);
	}

	public function error($sender, $receiver, $subject, $message) {
		$text = "Error when sending mail : [Sender='$sender'] [RECEIVER='$receiver'] [SUBJECT='$subject'] [MESSAGE='$message'].";
		fwrite($this->handle, $text);
	}
}