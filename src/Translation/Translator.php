<?php 

namespace Moulino\Framework\Translation;

use Moulino\Framework\DependencyInjection\Container as Dic;

class Translator implements TranslatorInterface
{
	private $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	public function tr($string) {
		$arguments = func_get_args();
		array_shift($arguments);

		$strTr = $this->container->translate($string);
		array_unshift($arguments, $strTr);

		return call_user_func_array("sprintf", $arguments);
	}
}

?>