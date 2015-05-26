<?php 

namespace Moulino\Framework\Exception;

class ConfigException extends HttpException
{
	public function __construct($message = null, \Exception $previous = null, $headers = array(), $code = 0) {
		parent::__construct(500, $message, $previous, $headers, $code);
	}
} 

?>
