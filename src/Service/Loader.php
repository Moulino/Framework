<?php 

namespace Moulino\Framework\Service;

use Moulino\Framework\Config\Config as AppConfig;
use Moulino\Framework\Utils\Container\ContainerInterface;

/**
* Container de dépendances
*/
class Loader
{
	private $container;

	public function __construct() {
		$this->container = Container::getInstance();
	}

	public function getContainer() {
		return $this->container;
	}

	public function load(Config $config) {
		$services = $config->get('');

		foreach ($services as $alias => $service) {
			$definition = Loader::getDefinitionObject($service);
			$this->container->setDefinition($alias, $definition);
		}

		return $this;
	}

	public function loadModels() {
		$entities = AppConfig::get('entities');

		foreach ($entities as $entityName => $definition) {
			$alias = strtolower($entityName).'_model';
			$tableName = AppConfig::get('entities.'.$entityName.'.table');

			$this->container->setDefinition($alias, new Definition(
				'Moulino\\Framework\\Model\\Model', array(
					new Reference('database'),
					$entityName,
					$tableName
					)
				));
		}

		return $this;
	}

	public static function getDefinitionObject($serviceDef) {
		$arguments = array();

		if(isset($serviceDef['arguments'])) {
			foreach ($serviceDef['arguments'] as $argument) {

				if(preg_match('#^@#', $argument)) {
					$argument = preg_replace_callback('#%([0-9\w\.]+)%#', function($matches) {
						array_shift($matches);
						$key = $matches[0];
						return strtolower(AppConfig::get($key));
					}, $argument);
					array_push($arguments, new Reference(ltrim($argument, '@')));
				} 

				else if(preg_match('#%([0-9\w\.]+)%#', $argument, $matches)) {
					array_shift($matches);
					$key = $matches[0];
					array_push($arguments, AppConfig::get($key));
				}
			}
		}

		return new Definition($serviceDef['class'], $arguments);
	}
}

?>
