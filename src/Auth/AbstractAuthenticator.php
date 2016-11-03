<?php

namespace Moulino\Framework\Auth;

use Moulino\Framework\Service\Container;
use Moulino\Framework\Model\ModelInterface;
use Moulino\Framework\Auth\Exception\BadCredentialsException;

abstract class AbstractAuthenticator implements AuthenticatorInterface
{
	protected $translator;
	protected $model;
	protected $passwordEncoder;
	protected $salt;

	const LOCKED_USER_MESSAGE  = "Account locked";
	const UNKNOWN_USER_MESSAGE = "Unknown username";
	const WRONG_PASSWORD       = "Wrong password";
	const USER_NOT_LOGGED      = "User is not logged";

	public function __construct(Container $container, ModelInterface $model, $salt) {
		$this->translator = $container->get('translator');
		$this->passwordEncoder = $container->get('password_encoder');
		$this->model = $model;
		$this->salt = $salt;
	}

	protected function fetchUser($user_id) {
		$user = $this->model->get(array('user_id' => $user_id));

		if(!$user) {
			throw new BadCredentialsException($this->translator->tr(self::UNKNOWN_USER_MESSAGE), 403);
		}

		if($user['errors'] >= 10) {
			throw new BadCredentialsException($this->translator->tr(self::LOCKED_USER_MESSAGE), 403);
		}

		return $user;
	}
	
	/**
	 * Return the password encoded
	 * @param string ununcrypted password
	 * @return string encrypted password
	 */
	public function encodePassword($password) {
		return $this->passwordEncoder->encode($password);
	}

}