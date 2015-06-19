<?php 

namespace Moulino\Framework\Mailer;

/**
* 
*/
interface MailerInterface
{
	public function send($sender, $receiver, $subject, $message);
}

?>