<?php 

namespace Moulino\Framework\Routing;

use Moulino\Framework\Routing\Exception\RoutingValidationException;
use Moulino\Framework\Translation\TranslatorInterface;

class RouteValidator
{
	private $translator;

	public function __construct(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

	/**
	 * Checks whether the route meets the definition
	 * @param $route array Route settings
	* @return True if the route is valid or false if the route is not valid.
	 */
	public function validate(array $definition, $id, $route) {
		foreach ($definition as $key => $value) {

			// test 'require'
			if(isset($value['require'])) {
				if($value['require'] === true && !isset($route[$key])) {
					throw new RoutingValidationException($this->tr("The parameter '%s' for the route '%s' must be defined.", $key, $id));
				}
			}

			// test 'match'
			if(isset($value['match'])) {
				if(!preg_match('#'.$value['match'].'#', $route[$key])) {
					throw new RoutingValidationException($this->tr("The format of parameter '%s' for the route '%s' is wrong : [%s].", $key, $id, $route[$key]));
				}
			}
		}
	}

	private function tr() {
		$args = func_get_args();
		return call_user_func_array(array($this->translator, 'tr'), $args);
	}
}

?>