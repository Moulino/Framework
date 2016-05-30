<?php 

namespace Moulino\Framework\Auth;

interface PasswordEncoderInterface
{
	public function encode($password);
}