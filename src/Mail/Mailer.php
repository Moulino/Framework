<?php 

namespace Moulino\Framework\Mail;

use Moulino\Framework\Mail\Exception\MailNoSentException;
use Moulino\Framework\Logger\Logger;

/**
* 
*/
class Mailer implements MailerInterface
{
	private $logger;
	
	function __construct(Logger $logger) {
		$this->logger = $logger;
	}

	public function send($sender, $receiver, $subject, $message) {
		$headers = "From:$sender";
		if(!mail($receiver, $subject, $message, $headers)) {
			throw new MailNoSentException("Erreur lors de l'envoi du mail.");
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