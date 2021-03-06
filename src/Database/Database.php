<?php

namespace Moulino\Framework\Database;

use Moulino\Framework\Database\Exception\DatabaseException;

class Database implements DatabaseInterface
{
	private $connection;
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
			$this->connection = new \PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pwd);
			$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch(\PDOException $e) {
			throw new DatabaseException(self::ERROR_CONNECTION_MESSAGE, 0, $e);
		}
	}

	public function getConnection() {
		return $this->connection;
	}
}

?>