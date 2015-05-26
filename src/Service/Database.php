<?php

namespace Moulino\Framework\Service;

use Moulino\Framework\Service\Config;

class Database 
{
	protected $connexion;

	public function __construct(Config $config) {
		$this->config = $config;

		$this->connect();
	}

	private function connect() {
		$db_name = $this->config->get('database', 'name');
		$db_host = $this->config->get('database', 'host');
		$db_user = $this->config->get('database', 'user');
		$db_pwd  = $this->config->get('database', 'password');

		$this->connexion = new \PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pwd);
	}

	public function getConnexion() {
		return $this->connexion;
	}
}

?>