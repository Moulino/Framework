<?php 

namespace Moulino\Framework\Auth;

use Moulino\Framework\Session\SessionInterface;
use Moulino\Framework\Model\ModelInterface;
use Moulino\Framework\Config\ConfigInterface;

use Moulino\Framework\Auth\Exception\BadCredentialsException;

use Moulino\Framework\Translation\TranslatorInterface;

/**
 * This class handles the authentication steps.
 */
class Authenticator implements AuthenticatorInterface
{
	private $session;
	private $model;
	private $translator;
	private $salt;

	const LOCKED_USER_MESSAGE  = "Account locked";
	const UNKNOWN_USER_MESSAGE = "Unknown username";
	const WRONG_PASSWORD       = "Wrong password";

	/**
	 * Constructor
	 */
	public function __construct(SessionInterface $session, ModelInterface $model, TranslatorInterface $translator, $salt) {
		$this->session 		= $session;
		$this->model   		= $model;
		$this->translator = $translator;
		$this->salt  			= $salt;
	}

	/**
	 * Returns true if the user is authenticated and if his ip is correct
	 * @param $remoteAddr Ip adress of the remote machine
	 * @return boolean
	 */
	public function isAuthenticated($remoteAddr) {
		$auth = $this->session->read('auth');

		if(isset($auth['user_id']) && $auth['remoteAddr'] == $remoteAddr) {
			return true;
		}
		return false;
	}

	/**
	 * Handles authentication of the user
	 * @param $remoteAddr Ip adress of the remote machine
	 * @param $user_id Name of the user
	 * @param $password Not hashed password
	 * @return boolean Returns true if the user is authenticated
	 * @throws AuthException If the authentication has failed
	 */
	public function login($remoteAddr, $user_id, $password) {		
		$user = $this->model->get(array('user_id' => $user_id));

		if(!$user) {
			throw new BadCredentialsException($this->translator->tr(self::UNKNOWN_USER_MESSAGE), 403);
		}

		if($user['errors'] >= 10) {
			throw new BadCredentialsException($this->translator->tr(self::LOCKED_USER_MESSAGE), 403);
		}

		$passEnc = sha1(sha1($password).$this->salt);

		if($user['password'] == $passEnc) {
			$this->session->write('auth', array(
				'remoteAddr' => $remoteAddr,
				'user_id'    => $user['user_id'],
				'roles'      => (is_null($user['roles'])) ? array() : explode(',', $user['roles'])
				));

			$this->model->set($user['id'], array('errors' => 0));
			return true;
		}
		$this->model->set($user['id'], array('errors' => intval($user['errors']) +1));
		throw new BadCredentialsException($this->translator->tr(self::WRONG_PASSWORD), 403);
	}

	/**
	 * Handles the deconnexion of the user
	 */
	public function logout() {
		$this->session->write('auth', '');
	}

	/**
	 * Returns the user informations
	 * @return array Informations
	 */
	public function getAuthInfo() {
		$auth = $this->session->read('auth');

		return array(
			'authenticated' => isset($auth['user_id']),
			'user_id'       => isset($auth['user_id']) ? $auth['user_id'] : '',
			'roles' 				=> isset($auth['roles']) ? $auth['roles'] : array()
		);
	}
	
} ?>