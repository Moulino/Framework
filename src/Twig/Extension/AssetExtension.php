<?php

namespace Moulino\Framework\Twig\Extension;

use Moulino\Framework\Service\Container;

class AssetExtension extends \Twig_Extension
{
	private $request;

	public function __construct(Container $container) {
		$this->request = $container->get('request');
	}

	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('asset', array($this, 'getAssetUrl'))
		);
	}

	public function getAssetUrl($path) {
		$url = $this->request->getBaseUrl();

		if(substr($path, 0, 1) != '/') {
			$url .= '/';
		}
		return $url.$path;
	}

	public function getName() {
		return 'asset';
	}
}