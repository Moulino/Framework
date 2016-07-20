<?php 

namespace Moulino\Framework\Core;

use Moulino\Framework\Log\LoggerInterface;
use Moulino\Framework\Core\Exception\InternalErrorException;

class ErrorHandler
{
	private $logger;

	public function __construct(LoggerInterface $logger) {
		$this->logger = $logger;
	}

	public function register() {
		set_error_handler(array($this, 'handler'));
	}

	public function handler($type, $message, $file, $line, $context) {

		if($type === E_WARNING) {
			$this->logger->warning("$message $file:$line");
		} else {
			throw new InternalErrorException("$message $file:$line", $type);
		}
	}
}

?>