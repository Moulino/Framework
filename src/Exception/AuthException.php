<?php 

namespace Moulino\Framework\Exception;

class AuthException extends HttpException
{
	public function __construct($message = null, \Exception $previous = null, $headers = array(), $code = 0) {
		parent::__construct(403, $message, $previous, $headers, $code);
	}
} ?>
