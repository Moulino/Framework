<?php 

namespace Moulino\Framework\DependencyInjection;

/**
* Container de dépendances
*/
class DIContainer
{
	static private $dic_instance;

	private $instances = array(); // instances en cours ('name', 'object')
	private $factories = array(); // registre de correspondance ('name', 'resolver') -> fabrique un nouvel objet à chaque appel

	static function getInstance() {
		if(is_null(self::$dic_instance)) {
			self::$dic_instance = new DIContainer();
		}
		return self::$dic_instance;
	}
	
	private function __construct() {}

	public function set($key, $object) {
		$instances[$key] = $object;
	}

	public function setFactory($key, Callable $resolver) {
		if(isset($this->factories[$key])) {
			throw new \Exception("This factory '$key' has already been declared in the DIC.");
		}
		$this->factories[$key] = $resolver;
	}

	public function get($key) {
		if(isset($this->instances[$key])) {
			return $this->instances[$key];
		}

		if(isset($this->factories[$key])) {
			return $this->factories[$key];
		}

		$reflector = new \ReflectionClass($key);
		if($reflector->isInstantiable()) {
			$instance = null;
			if($constructor = $reflector->getConstructor()) {
				$parameters = $constructor->getParameters();

				$args = array();
				foreach ($parameters as $parameter) {
					if(!$parameter->isOptional()) {
						$args[] = $this->get($parameter->getClass()->getName());
					}
				}

				$instance = $reflector->newInstanceArgs($args);
			} else {
				$instance = $reflector->newInstance();
			}

			$this->instances[$key] = $instance;
			return $instance;
		} else {
			throw new \Exception("The class '$key' is not instantiable.");
		}
	}

	/**
	 * Récupère le modèle avec son nom
	 * @param $modelName String Nom du modèle sous la forme 'App:Model'
	 */
	public function getModel($modelName) {
		$modelName = ucfirst($modelName);
		$config = $this->getService('config');

		if($config->isDefined('entities', $modelName, 'class')) {
			echo "Class model user defined";
			$modelClass = $config->get('entities', $modelName, 'class');
			return $this->get($modelClass);
		}

		$modelClass = 'App\\Model\\'.$modelName.'Model';
		if(isset($instances[$modelClass])) {
			return $this->get($modelClass);
		}

		$database = $this->getService('Database');
		$model = new \Moulino\Framework\Model\Model($config, $database, $modelName);
		$this->set('App\\Model\\'.$modelName.'Model', $model);
		return $model;
	}

	public function getService($serviceName) {
		return $this->get('Moulino\\Framework\\Service\\'.ucfirst($serviceName));
	}

	public function getTemplating() {
		return $this->get('Moulino\\Framework\\Templating\\Engine');
	}
	
} ?>