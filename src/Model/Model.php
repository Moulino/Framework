<?php 

namespace Moulino\Framework\Model;

use Moulino\Framework\Database\DatabaseInterface;
use Moulino\Framework\Config\ConfigInterface;

class Model implements ModelInterface
{
	protected $connection;
	protected $entityName;
	protected $tableName;
	
	public function __construct(DatabaseInterface $database, $entityName, $tableName)
	{
		$this->connection = $database->getConnection();
		$this->entityName = $entityName;
		$this->tableName = $tableName;
	}

	public function getEntityName() {
		return $this->entityName;
	}

	public function add($parameters) {
		$sql = "INSERT INTO $this->tableName(";

		$number = 0;
		foreach ($parameters as $key => $value) {
			if($number > 0) {
				$sql .= ',';
			}
			$sql .= $key;
			$number++;
		}
		$sql .= ") VALUES(";

		$number = 0;
		foreach ($parameters as $key => $value) {
			if($number > 0) {
				$sql .= ',';
			}

			$sql .= ":$key";
			$number++;
		}
		$sql .= ");";

		$query = $this->connection->prepare($sql);
		if(!$query->execute($parameters)) {
			$error = $this->connection->errorInfo();
			throw new \Exception("Erreur lors de l'ajout de l'element. SQL : $sql", $error[1]);
		}
	}

	public function get($criteria, $filters = null) {
		$sql = "SELECT * FROM $this->tableName WHERE";
		$queryParameters = null;

		if(is_numeric($criteria)) {
			$criteria = intval($criteria);
		}

		if(is_string($criteria)) {
			$sql .= " $criteria";
		}

		else if(is_array($criteria)) {
			$number = 0;
			foreach ($criteria as $key => $value) {
				if($number > 0) {
					$sql .= " AND";
				}

				$sql .= " $key = :$key";
				$number++;
			}

			$queryParameters = $criteria;
		} 

		else {
			$sql .= " id = :id";
			$queryParameters = array(
				'id' => intval($criteria)
				);
		}

		if(is_string($filters)) {
			$sql .= " $filters";
		}

		$sql .= ';';

		$query = $this->connection->prepare($sql);
		$query->execute($queryParameters);
		return $query->fetch(\PDO::FETCH_ASSOC);
	}

	public function set($criteria, $parameters) {
		$sql = "UPDATE $this->tableName SET ";
		$queryParameters = null;

		// configure les paramètres de la requête
		if(is_array($criteria)) {
			$queryParameters = array_merge($criteria, $parameters);
		} else {
			$queryParameters = $parameters;
			$queryParameters['id'] = intval($criteria);
		}

		// paramètre la rpartie 'SET' de la requête
		$number = 0;
		foreach ($parameters as $key => $value) {
			if($number > 0) {
				$sql .= ',';
			}

			$sql .= "$key = :$key";
			$number++;
		}

		// paramètre la partie 'WHERE' de la requête
		$sql .= " WHERE";
		if(is_array($criteria)) {
			$number = 0;
			foreach ($criteria as $key => $value) {
				if($number > 0) {
					$sql .= " AND";
				}

				$sql .= " $key = :$key";
				$number++;
			}
		} else {
			$sql .= " id = :id";
		}

		$sql .= ";";

		$query = $this->connection->prepare($sql);
		return $query->execute($queryParameters);
	}

	public function cget($criteria = null, $filters = null) {
		$sql = "SELECT * FROM $this->tableName";
		$queryParameters = null;

		if(is_string($criteria)) {
			$sql .= " WHERE $criteria";
		}

		if(is_array($criteria)) {
			$sql .= " WHERE";
			$number = 0;

			foreach ($criteria as $key => $value) {
				if($number === 0) {
					$sql .= " WHERE";
				}
				else if($number > 0) {
					$sql .= " AND";
				}
				$sql .= " $key = :$key";
				$number++;
			}
			$queryParameters = $criteria;
		}

		if(is_string($filters)) {
			$sql .= " $filters";
		}

		$sql .= ";";

		$query = $this->connection->prepare($sql);
		$query->execute($queryParameters);;
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function remove($criteria) {
		$sql = "DELETE FROM $this->tableName WHERE";
		$queryParameters = null;

		if(is_array($criteria)) {
			$number = 0;
			foreach ($criteria as $key => $value) {
				if($number > 0) {
					$sql .= " AND";
				}

				$sql .= " $key = :$key";
				$number++;
			}
			$queryParameters = $criteria;
		} else {
			$sql .= " id = :id";
			$queryParameters = array('id' => intval($criteria));
		}
		$sql .= ';';

		$query = $this->connection->prepare($sql);
		return $query->execute($queryParameters);
	}

	public function removeAll() {
		$this->connection->exec("DELETE FROM $this->tableName");
	}

	public function count($criteria = array()) {
		$sql = "SELECT COUNT(*) FROM $this->tableName WHERE";
		$queryParameters = null;

		if(is_array($criteria)) {
			$number = 0;
			foreach ($criteria as $key => $value) {
				if($number > 0) {
					$sql .= " AND";
				}

				$sql .= " $key = :$key";
				$number++;
			}
			$sql .= ';';

			$queryParameters = $criteria;
		} else {
			$sql .= " id = :id";
			$queryParameters = array(
				'id' => intval($criteria)
				);
		}

		try {
			$query = $this->connection->prepare($sql);
		} catch(\PDOException $e) {

		}

		if($query->execute($queryParameters)) {
			if(($result = $query->fetch()) != false) {
				return intval($result[0]);
			}
		} else {
			
		}
	}
	
	public function errorInfo() {
		return $this->connection->errorInfo();
	}
}

?>