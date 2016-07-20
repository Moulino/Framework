<?php

namespace Moulino\Framework\Auth;

use Moulino\Framework\Service\Container;
use Moulino\Framework\Model\ModelInterface;
use Moulino\Framework\Config\Config as AppConfig;

use Moulino\Framework\Auth\Exception\JwtKeyNotConfiguredException;
use Moulino\Framework\Auth\Exception\BadCredentialsException;
use Firebase\JWT\JWT;

class JwtAuthenticator extends AbstractAuthenticator
{
	const JWT_KEY_NOT_CONFIGURED = "The key for jwt is not configured";

	private $request = null;

	public function __construct(Container $container, ModelInterface $model) {
		parent::__construct($container, $model);
		$this->request = $container->get('request');
	}

	public function isAuthenticated($remoteAddr) {
		$headers = $this->request->getHeaders();

		if(isset($headers['Authorization'])) {
			$authorization = $headers['Authorization'];
			$regex = '#^Bearer ([A-Za-z0-9\-\._~\+\/]+=*)#';
			$key = $this->getKey();

			if(preg_match($regex, $authorization, $matches)) {
				$decoded = (array) JWT::decode($matches[1], $key, array('HS256'));
				if(isset($decoded['user_id']) && isset($decoded['remoteAddr'])) {
					if($decoded['remoteAddr'] === $remoteAddr) return true;
				}
			}
		}
		return false;
	}

	public function login($remoteAddr, $user_id, $password) {
		$user = $this->fetchUser($user_id);
		$pwdEnc = $this->encodePassword($password);

		if($user['password'] == $pwdEnc) {
			$data = array(
				'remoteAddr' => $remoteAddr,
				'user_id' => $user['user_id'],
				'name' => $user['name'],
				'roles' => (is_null($user['roles'])) ? array() : explode(',', $user['roles'])
			);

			$key = $this->getKey();
			$jwt = JWT::encode($data, $key);
			return $jwt;
		}
		$this->model->set($user['id'], array('errors' => intval($user['errors']) +1));
		throw new BadCredentialsException($this->translator->tr(self::WRONG_PASSWORD), 403);
	}

	public function getAuthInfo() {
		$headers = $this->request->getHeaders();
		$decoded = array();

		if(isset($headers['Authorization'])) {
			$authorization = $headers['Authorization'];
			$regex = '#^Bearer ([A-Za-z0-9\-\._~\+\/]+=*)#';
			$key = $this->getKey();

			if(preg_match($regex, $authorization, $matches)) {
				$decoded = (array) JWT::decode($matches[1], $key, array('HS256'));
			}
		}

		$authInfo = array(
			'authenticated' => isset($decoded['user_id']),
			'user_id' => isset($decoded['user_id']) ? $decoded['user_id'] : '',
			'name' => isset($decoded['name']) ? $decoded['name'] : '',
			'roles' => isset($decoded['roles']) ? $decoded['roles'] : array()
		);

		return $authInfo;
	}

	private function getKey() {
		if(!AppConfig::isDefined('security.jwt_key')) {
			throw new JwtKeyNotConfiguredException($this->translator->tr(self::JWT_KEY_NOT_CONFIGURED));
		}
		return AppConfig::get('security.jwt_key');
	}
}