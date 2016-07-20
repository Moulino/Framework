<?php 

namespace Moulino\Framework\Auth;

use Moulino\Framework\Service\Container;
use Moulino\Framework\Model\ModelInterface;

interface AuthenticatorInterface
{
	public function __construct(Container $container, ModelInterface $model);

	public function isAuthenticated($remoteAddr);

	public function login($remoteAddr, $username, $password);
	
	public function getAuthInfo();

} ?>