<?php 

namespace Moulino\Framework\Service;

use Moulino\Framework\Service\Exception\NoDefinitionFoundException;
use Moulino\Framework\Service\Exception\ClassNoInstantiableException;
use Moulino\Framework\Service\Exception\AliasNotAuthorizedException;

/**
* Container de dépendances
*/
class Container
{
	static private $dic_instance;

	private $instances = array();
	private $definitions = array();

	static function getInstance() {
		if(is_null(self::$dic_instance)) {
			self::$dic_instance = new Container();
		}
		return self::$dic_instance;
	}
	
	private function __construct() {}

	public function set($key, $object) {
		$this->instances[$key] = $object;
	}

	public function setDefinition($alias, $definition) {
		$alias = strtolower($alias);

		$this->checkAlias($alias);
		$this->definitions[$alias] = $definition;
	}

	public function getDefinition($alias) {
		$alias = strtolower($alias);
		if(!isset($this->definitions[$alias])) {
			throw new NoDefinitionFoundException("The definition of service '$alias' is unknown.");
		}
		return $this->definitions[$alias];
	}

	public function get($alias) {
		if($alias === "container") {
			return $this;
		}

		if(isset($this->instances[$alias])) {
			return $this->instances[$alias];
		}

		$def = $this->getDefinition($alias);
		$class = $def->getClass();

		$reflector = new \ReflectionClass($class);
		if($reflector->isInstantiable()) {
			$instance = null;

			$dependencies = $def->getDependencies();
			$arguments = array();
			foreach ($dependencies as $dependency) {
				if($dependency instanceof Reference) {
					$arguments[] = $this->get($dependency->getAlias());
				} else {
					$arguments[] = $dependency;
				}
			}
			$arguments;
			$instance = $reflector->newInstanceArgs($arguments);

			$this->instances[$alias] = $instance;
			return $instance;

		} else {
			throw new ClassNoInstantiableException("The class '$class' is not instantiable.");
		}
	}

	/**
	 * Récupère le modèle avec son nom
	 * @param $modelName String Nom du modèle sous la forme 'App:Model'
	 */
	public function getModel($modelName) {
		$modelName = ucfirst($modelName);
		$config = $this->getConfig();

		if($config->isDefined('entities', $modelName, 'class')) {
			$modelClass = $config->get('entities', $modelName, 'class');
			return $this->get($modelClass);
		}

		$modelClass = 'App\\Model\\'.$modelName.'Model';
		if(isset($this->instances[$modelClass])) {
			return $this->get($modelClass);
		}

		$database = $this->getService('Database');
		$model = new \Moulino\Framework\Model\Model($config, $database, $modelName);
		$this->set('App\\Model\\'.$modelName.'Model', $model);
		return $model;
	}

	public function getAliasByClass($class) {
		foreach ($this->definitions as $alias => $definition) {
			if($definition->getClass() == $class) return $alias;
		}
		return false;
	}
	
	private function checkAlias($alias) {
		if($alias === 'container') {
			throw new AliasNotAuthorizedException("The alias 'container' is reserved.");
		}
	}
} ?>