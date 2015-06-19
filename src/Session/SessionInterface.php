<?php

namespace Moulino\Framework\Session;

/**
* Session handler
*/
interface SessionInterface
{
	public function write($key, $value);
	public function read($key = null);
}

?>