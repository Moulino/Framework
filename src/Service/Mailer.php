<?php 

namespace Moulino\Framework\Service;

use Moulino\Framework\Service\Logger;
use Moulino\Framework\Exception\MailerException;

/**
* 
*/
class Mailer
{
	private $logger;
	
	function __construct(Logger $logger) {
		$this->logger = $logger;
	}

	public function send($sender, $receiver, $subject, $message) {
		$headers = "From:$sender";
		if(!mail($receiver, $subject, $message, $headers)) {
			throw new MailerException("Erreur lors de l'envoi du mail.");
			$this->logError($sender, $receiver, $subject);
		}
		$this->logInfo($sender, $receiver, $subject);
	}

	private function logError($sender, $receiver, $subject) {
		$text = "Error when sending mail : [Sender='$sender'] [RECEIVER='$receiver'] [SUBJECT='$subject'].";
		$this->logger->error($text);
	}

	private function logInfo($sender, $receiver, $subject) {
		$text = "Mail sent : [Sender='$sender'] [RECEIVER='$receiver'] [SUBJECT='$subject'].";
		$this->logger->info($text);
	}
}

?>