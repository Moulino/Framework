<?php 

namespace Moulino\Framework\Auth;

interface AuthenticatorInterface
{
	public function isAuthenticated($remoteAddr);

	public function login($remoteAddr, $id, $password);

	public function logout();
	
	public function getAuthInfo();

} ?>