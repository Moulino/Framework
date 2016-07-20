<?php 

namespace Moulino\Framework\Model;

use Moulino\Framework\Database\DatabaseInterface;
use Moulino\Framework\Config\ConfigInterface;
use Moulino\Framework\Config\Config as AppConfig;

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

	public function getFieldParameters() {
		$fields = array();
		$path = "entities.".ucfirst($this->tableName).".fields";

		if(AppConfig::isDefined($path)) {
			$fields = AppConfig::get($path);
		}
		return $fields;
	}

	public function add($parameters) {
		$sql = "INSERT INTO $this->tableName(";

		$number = 0;
		foreach ($parameters as $key => $value) {
			if($number > 0) {
				$sql .= ',';
			}
			$sql .= '`'.$key.'`';
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
		try {
			$parameters = $this->prepareQueryParameters($parameters);
			$query->execute($parameters);
		} catch(\PDOException $e) {
			throw new \Exception("Erreur lors de l'ajout de l'element : ".$e->getMessage());
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

		$queryParameters = $this->prepareQueryParameters($queryParameters);
		$query->execute($queryParameters);
		$entity = $this->convertEntity($query->fetch(\PDO::FETCH_ASSOC));
		return $entity;
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

			$sql .= "`$key` = :$key";
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

		$queryParameters = $this->prepareQueryParameters($queryParameters);
		$query->execute($queryParameters);
		return $query->rowCount();
	}

	public function cget($criteria = null, $filters = null) {
		$sql = "SELECT * FROM $this->tableName";
		$queryParameters = null;

		if(is_string($criteria)) {
			$sql .= " WHERE $criteria";
		}

		if(is_array($criteria)) {
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

		$queryParameters = $this->prepareQueryParameters($queryParameters);
		$query->execute($queryParameters);
		$entities = $this->convertEntities($query->fetchAll(\PDO::FETCH_ASSOC));
		return $entities;
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

		$queryParameters = $this->prepareQueryParameters($queryParameters);
		$query->execute($queryParameters);
		return $query->rowCount();
	}

	public function removeAll() {
		return $this->connection->exec("DELETE FROM $this->tableName");

	}

	public function count($criteria = array()) {
		$sql = "SELECT COUNT(*) FROM $this->tableName WHERE";
		$queryParameters = null;

		if(count($criteria) > 0) {
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
			$sql .= " 1;";
		}

		$query = $this->connection->prepare($sql);

		$queryParameters = $this->prepareQueryParameters($queryParameters);
		$query->execute($queryParameters);
		if(($result = $query->fetch()) != false) {
			return intval($result[0]);
		}
		return false;
	}
	
	public function errorInfo() {
		return $this->connection->errorInfo();
	}

	public function prepareQueryParameters($queryParameters) {
		if(false == is_null($queryParameters)) {
			$fields = $this->getFieldParameters();

			foreach($queryParameters as $key => $value) {
				$newval = $value;
				if(array_key_exists($key, $fields)) {
					$type = $fields[$key];

					switch($type) {
						case 'integer':
							$newval = strval($value);
							break;
						case 'number':
							$newval = strval($value);
							break;
						case 'boolean':
							$newval = (true === $value) ? '1' : '0';
							break;
						default:
							$newval = $value;
							break;
					}
					$queryParameters[$key] = $newval;//$this->convertToType($type, $value);
				}
			}
		}

		return $queryParameters;
	}

	public function convertEntities($entities) {
		if(is_array($entities)) {
			foreach ($entities as $key => $value) {
				$entities[$key] = $this->convertEntity($value);
			}
		}

		return $entities;
	}

	public function convertEntity($entity) {
		$fields = $this->getFieldParameters();

		if(false != $entity) {
			foreach ($entity as $key => $value) {
				if(array_key_exists($key, $fields)) {
					$type = $fields[$key];
					$newval = null;

					if(!is_null($value)) {
						switch ($type) {
							case 'integer':
								$newval = intval($value);
								break;

							case 'number':
								$newval = floatval($value);
								break;

							case 'boolean':
								$newval = boolval($value);
								break;
							
							default:
								$newval = $value;
								break;
						}
					}
					$entity[$key] = $newval;
				}
			}
		}

		return $entity;
	}
}

?>