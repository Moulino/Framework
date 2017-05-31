<?php 

namespace Moulino\Framework\Mail;

use Moulino\Framework\Mail\Exception\MailNoSentException;
use Moulino\Framework\Log\MailLoggerInterface;

/**
* 
*/
class Mailer implements MailerInterface
{
	private $logger;
	
	function __construct(MailLoggerInterface $logger) {
		$this->logger = $logger;
	}

	public function send($sender, $receiver, $subject, $message, $boundary) {
		$header = $this->composeHeader($sender, $receiver, $boundary);
		$subject = mb_encode_mimeheader($subject, "UTF-8", "B");

		if(!mail($receiver, $subject, $message, $header)) {
			$htmlError = "<b>Une erreur est survenue lors de l'envoi du courriel.</b><br /><br />
							Vous pouvez nous contacter soit <br />
							<ul><li>par téléphone au 02 35 37 57 72</li><li>par courriel : <a href='mailto:fch@fch-capoulade.fr'>fch@fch-capoulade.fr</a></li></ul>";

			throw new MailNoSentException($htmlError);
			$this->logger->error($sender, $receiver, $subject, $message);
		}
		$this->logger->info($sender, $receiver, $subject, $message);
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