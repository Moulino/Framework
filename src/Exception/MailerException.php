<?php 

namespace Moulino\Framework\Exception;

class MailerException extends HttpException
{
	public function __construct($message = null, \Exception $previous = null, $headers = array(), $code = 0) {
		parent::__construct(400, $message, $previous, $headers, $code);
	}
} 

?>
