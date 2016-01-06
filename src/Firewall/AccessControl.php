<?php 

namespace Moulino\Framework\Firewall;

use Moulino\Framework\Auth\AuthenticatorInterface;
use Moulino\Framework\Translation\TranslatorInterface;

use Moulino\Framework\Firewall\Exception\AccessRefusedException;

use Moulino\Framework\Http\Request;

/**
* 
*/
class AccessControl implements AccessControlInterface
{
	private $authenticator;
	private $translator;
	private $rules = array();
	
	function __construct(AuthenticatorInterface $authenticator, TranslatorInterface $translator)
	{
		$this->authenticator = $authenticator;
		$this->translator = $translator;
	}

	public function addRule(Rule $rule) {
		$this->rules[] = $rule;
	}

	public function isAuthorized(Request $request) {
		$uri = $request->getUri();
		foreach ($this->rules as $rule) {
			$regex = '#'.$rule->getPath().'#';

			if(preg_match($regex, $uri)) {

				if($rule->hasRole('ANONYMOUS')) {
					return true;
				}

				$authInfo = $this->authenticator->getAuthInfo();
				if($authInfo['authenticated']) {
					if($rule->hasRole('IS_AUTHENTICATED')) {
						return true;
					}

					foreach ($authInfo['roles'] as $role) {
						if($rule->hasRole($role)) {
							return true;
						}
					}
				}
			}
		}
		return false;
	}

	public function checkAuthorization(Request $request) {
		if(!$this->isAuthorized($request)) {
			throw new AccessRefusedException($this->translator->tr("Access forbidden."));
		}
	}
}

?>