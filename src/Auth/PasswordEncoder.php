<?php 

namespace Moulino\Framework\Auth;

use Moulino\Framework\Auth\Exception\SaltIsEmpty;

/**
 * This class handles the password encoding.
 */
class PasswordEncoder implements PasswordEncoderInterface
{
	private $salt;

	/**
	 * Constructor
	 */
	public function __construct($salt) {
		$this->salt = $salt;
	}

	/**
	 * Return the password encoded
	 * @param string ununcrypted password
	 * @return string encrypted password
	 */
	public function encode($password) {
		if(!count($this->salt)) {
			throw new SaltIsEmpty("You must define the salt parameter in config file.");
		}

		return sha1(sha1($password).$this->salt);
	}
	
} ?>