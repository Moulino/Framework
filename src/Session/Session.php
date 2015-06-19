<?php

namespace Moulino\Framework\Session;

use Moulino\Framework\Session\SessionInterface;

/**
* Session handler
*/
class Session implements SessionInterface
{

	public function __construct() {
		session_start();
	}

	public function write($key, $value) {
		$_SESSION[$key] = $value;
	}

	public function read($key = null) {
		return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
	}
}

?>