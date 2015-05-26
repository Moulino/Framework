<?php 

namespace Moulino\Framework\Service;

use Moulino\Framework\DependencyInjection\DIContainer as Dip;
use Moulino\Framework\Service\Session;
use Moulino\Framework\Exception\AuthException;
use Moulino\Framework\Service\Config;


class Authenticator
{
	private $session;
	private $config;
	private $model;

	public function __construct(Session $session, Config $config) {
		$this->session = $session;
		$this->config = $config;
		$dip = Dip::getInstance();

		$modelName = $this->config->get('security', 'entity');
		$this->model = $dip->getModel($modelName);
	}

	public function isAuthenticated($remoteAddr) {
		$auth = $this->session->read('auth');

		if(isset($auth['id']) && $auth['remoteAddr'] == $remoteAddr) {
			return true;
		}
		return false;
	}

	public function login($remoteAddr, $id, $password) {
		$salt = $this->config->get('security', 'salt');
		
		$user = $this->model->get(array('user_id' => $id));

		if(!$user) {
			throw new AuthException("L'utilisateur est inconnu.");
		}

		if($user['errors'] >= 10) {
			throw new AuthException("Ce compte utilisateur est vérouillé.");
		}

		$passEnc = sha1(sha1($password).$salt);

		if($user['password'] == $passEnc) {
			$this->session->write('auth', array(
				'remoteAddr'  => $remoteAddr,
				'id' => $user['user_id']
				));

			$this->model->set($user['id'], array('errors' => 0));
			return true;
		}
		$this->model->set($user['id'], array('errors' => intval($user['errors']) +1));
		throw new AuthException("Le mot de passe est incorrect.");
	}

	public function logout() {
		$this->session->write('auth', '');
	}

	public function getAuthInfo() {
		$auth = $this->session->read('auth');

		return array(
			'authenticated' => isset($auth['id']),
			'id'            => isset($auth['user_id']) ? $auth['user_id'] : ''
		);
	}

} ?>