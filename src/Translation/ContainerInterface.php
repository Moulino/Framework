<?php 

namespace Moulino\Framework\Translation;

interface ContainerInterface
{
	public function translate($string, $locale);
}

?>