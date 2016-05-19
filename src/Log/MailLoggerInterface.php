<?php

namespace Moulino\Framework\Log;

interface MailLoggerInterface
{
	public function info($sender, $receiver, $subject, $message);
	public function error($sender, $receiver, $subject, $message);
}