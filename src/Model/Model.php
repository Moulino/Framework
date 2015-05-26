<?php 

namespace Moulino\Framework\Model;

use Moulino\Framework\Service\Database;
use Moulino\Framework\Service\Config;

class Model implements ModelInterface
{
	protected $connexion;
	protected $entityName;
	protected $tableName;
	
	public function __construct(Config $config, Database $database, $entityName)
	{
		$this->connexion = $database->getConnexion();
		$this->entityName = $entityName;
		$this->tableName = $config->get('entities', $entityName, 'table');
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

		$query = $this->connexion->prepare($sql);
		if(!$query->execute($parameters)) {
			$error = $this->connexion->errorInfo();
			throw new \Exception("Erreur lors de l'ajout de l'element.", $error[1]);
		}
	}

	public function get($criteria) {
		$sql = "SELECT * FROM $this->tableName WHERE";
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

		$query = $this->connexion->prepare($sql);
		$query->execute($queryParameters);
		return $query->fetch();
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
			$sql .= ';';
		} else {
			$sql .= " id = :id";
		}

		$query = $this->connexion->prepare($sql);
		return $query->execute($queryParameters);
	}

	public function cget() {
		$sql = "SELECT * FROM $this->tableName;";

		$query = $this->connexion->prepare($sql);
		$query->execute();
		return $query->fetchAll();
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

		$query = $this->connexion->prepare($sql);
		return $query->execute($queryParameters);
	}

	public function removeAll() {
		$this->connexion->exec("DELETE FROM $this->tableName");
	}

	public function count() {
		$statement = $this->connexion->query("SELECT * FROM $this->tableName");
		return $statement->rowCount();
	}
}

?>