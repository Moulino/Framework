<?php 

	namespace Moulino\Framework\Service;

	use App\Config\Parameters;
	use Moulino\Framework\Exception\ConfigException;

	/**
	* Manages the access to application parameters
	*/
	class Config
	{
		public function isDefined($section) {
			$args = func_get_args();
			array_shift($args);

			if(!isset(Parameters::${$section})) {
				return false;
			}

			$param = Parameters::${$section};
			foreach ($args as $arg) {
				if(!isset($param[$arg])) {
					return false;
				}
				$param = $param[$arg];
			}
			return true;
		}

		public function get($section, $exception = true) {
			$args = func_get_args();
			array_shift($args);

			if(!isset(Parameters::${$section})) {
				throw new ConfigException("The $section section was not configured.");
			}

			$param = Parameters::${$section};
			$params = array();
			foreach ($args as $arg) {
				$params[] = $arg;

				if(!isset($param[$arg])) {
					$message = "The parameter [$section";
					foreach ($params as $param) {
						$message .= " -> $param";
					}
					$message .= "] was not configured.";

					throw new ConfigException($message);
				}

				$param = $param[$arg];
			}

			return $param;
		}
	}
?>