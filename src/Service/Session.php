<?php

namespace Moulino\Framework\Service;

/**
* Session handler
*/
class Session
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