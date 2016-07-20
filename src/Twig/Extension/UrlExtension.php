<?php

namespace Moulino\Framework\Twig\Extension;

use Moulino\Framework\Service\Container;

class UrlExtension extends \Twig_Extension
{
	private $request;

	public function __construct(Container $container) {
		$this->request = $container->get('request');
	}

	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('absolute_url', array($this, 'generateAbsoluteUrl'))
		);
	}

	public function generateAbsoluteUrl($path) {
		$url = $this->request->getBaseUrl().$this->request->getUri();

		if(strlen($path) > 0 && substr($path, 0, 1) != '/') {
			$url .= '/';
		}
		return $url.$path;
	}

	public function getName() {
		return 'url';
	}
}