<?php 

namespace Moulino\Framework\Twig\Extension;

use Moulino\Framework\Translation\TranslatorInterface;

class TranslateExtension extends \Twig_Extension
{
	private $translator;

	public function __construct(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('tr', array($this, 'translate'))
		);
	}

	public function translate($text) {
		return $this->translator->tr($text);
	}

	public function getName() {
		return 'translate';
	}
}

?>