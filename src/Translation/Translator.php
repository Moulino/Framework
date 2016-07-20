<?php 

namespace Moulino\Framework\Translation;

use Moulino\Framework\DependencyInjection\Container as Dic;
use Moulino\Framework\Http\Request;

class Translator implements TranslatorInterface
{
	private $container;
	private $request;
	private $appLocale;

	public function __construct(ContainerInterface $container, Request $request, $appLocale) {
		$this->container = $container;
		$this->request = $request;
		$this->appLocale = $appLocale;
	}

	public function tr($string) {
		$locale = $this->request->getLocale();
		$arguments = func_get_args();

		if($locale != $this->appLocale) {
			array_shift($arguments);

			$strTr = $this->container->translate($string, $locale);
			array_unshift($arguments, $strTr);
		}

		return call_user_func_array("sprintf", $arguments);
	}
}

?>