<?php

namespace Moulino\Framework\Twig\Extension;

use Moulino\Framework\Http\RequestInterface;

class LangExtension extends \Twig_Extension
{
	private $request;

	public function __construct(RequestInterface $request) {
		$this->request = $request;
	}

	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('lang', array($this, 'getLang'))
		);
	}

	public function getLang() {
		return $this->request->getLocale();
	}

	public function getName() {
		return 'lang';
	}
}

?>