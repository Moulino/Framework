<?php

namespace Moulino\Framework\Auth;

use Moulino\Framework\Config\Config as AppConfig;
use Moulino\Framework\Auth\Exception\AuthenticatorUnknownException;

class AuthenticatorLoader
{
	const JWT = "Moulino\\Framework\\Auth\\JwtAuthenticator";
	const SESSION = "Moulino\\Framework\\Auth\\SessionAuthenticator";

	public static function getClass() {
		if(AppConfig::isDefined('security.authentication')) {
			$auth = AppConfig::get('security.authentication');

			if('jwt' === $auth) {
				return self::JWT;
			} elseif('session' === $auth) {
				return self::SESSION;
			}

			throw new AuthenticatorUnknownException("The authenticator \"$auth\" is unknown.");
		} else {
			return self::SESSION;
		}
	}
}