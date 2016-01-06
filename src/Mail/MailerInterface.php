<?php 

namespace Moulino\Framework\Mail;

/**
* 
*/
interface MailerInterface
{
	public function send($sender, $receiver, $subject, $message, $boundary);
}

?>