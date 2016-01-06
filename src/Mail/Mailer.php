<?php 

namespace Moulino\Framework\Mail;

use Moulino\Framework\Mail\Exception\MailNoSentException;
use Moulino\Framework\Log\LoggerInterface;

/**
* 
*/
class Mailer implements MailerInterface
{
	private $logger;
	
	function __construct(LoggerInterface $logger) {
		$this->logger = $logger;
	}

	public function send($sender, $receiver, $subject, $message, $boundary) {
		$header = $this->composeHeader($sender, $receiver, $boundary);

		if(!mail($receiver, $subject, $message, $header)) {
			throw new MailNoSentException("Erreur lors de l'envoi du mail.");
			$this->logError($sender, $receiver, $subject, $message);
		}
		$this->logInfo($sender, $receiver, $subject, $message);
	}

	private function logError($sender, $receiver, $subject, $message) {
		$text = "Error when sending mail : [Sender='$sender'] [RECEIVER='$receiver'] [SUBJECT='$subject'] [MESSAGE='$message'].";
		$this->logger->error($text);
	}

	private function logInfo($sender, $receiver, $subject, $message) {
		$text = "Mail sent : [Sender='$sender'] [RECEIVER='$receiver'] [SUBJECT='$subject'] [MESSAGE='$message'].";
		$this->logger->info($text);
	}

	public function generateBoundary() {
		return md5(rand());
	}

	private function composeHeader($sender, $receiver, $boundary) {
		$headers = "From: $sender\n";
		$headers .= "To: $receiver\n";
		$headers .= "Reply-to: $sender\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: multipart/alternative; boundary=$boundary\n";
		return $headers;
	}


}

?>