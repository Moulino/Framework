<?php 

namespace Moulino\Framework\Auth;

use Moulino\Framework\Auth\Exception\SaltIsEmpty;

class PasswordHasher
{
	private $salt;

	public function __construct($salt) {
		$this->salt = $salt;
	}

	public function hash($clearPassword) {
		if(!count($this->salt)) {
			throw new SaltIsEmpty("You must define the salt parameter in config file.");
		}
		return sha1(sha1($clearPassword).$this->salt);
	}
}

?>