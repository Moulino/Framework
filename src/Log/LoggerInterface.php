<?php 

namespace Moulino\Framework\Log;

interface LoggerInterface
{
	public function info($text);
	public function warning($text);
	public function error($text);
}

 ?>