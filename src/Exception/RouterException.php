<?php 

namespace Moulino\Framework\Exception;

class RouterException extends HttpException
{
	public function __construct($message = null, \Exception $previous = null, $headers = array(), $code = 0) {
		parent::__construct(404, $message, $previous, $headers, $code);
	}
} 

?>
