<?php 

namespace Moulino\Framework\Firewall;

use Moulino\Framework\Firewall\Rule;
use Moulino\Framework\Http\Request;

/**
* 
*/
interface AccessControlInterface
{
	public function addRule(Rule $rule);
	public function isAuthorized(Request $request);
	public function checkAuthorization(Request $request);
}

?>