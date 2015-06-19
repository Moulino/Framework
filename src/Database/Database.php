<?php

namespace Moulino\Framework\Database;

use Moulino\Framework\Database\Exception\DatabaseException;

class Database implements DatabaseInterface
{
	private $connexion;
	private $config;

	const ERROR_CONNECTION_MESSAGE = "Error in connecting to database.";

	public function __construct($config) {
		$this->config = $config;
		$this->connect();
	}

	private function connect() {
		$db_name = $this->config['name'];
		$db_host = $this->config['host'];
		$db_user = $this->config['user'];
		$db_pwd  = $this->config['password'];

		try {
			$this->connexion = new \PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pwd);	
		} catch(\PDOException $e) {
			throw DatabaseException(self::ERROR_CONNECTION_MESSAGE, 0, $e);
		}
	}

	public function getConnexion() {
		return $this->connexion;
	}
}

?>