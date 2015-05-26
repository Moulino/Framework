<?php 

namespace Moulino\Tests\Framework;

use Moulino\Framework\Exception\AssertException;

/**
* 
*/
class Assert
{
	
	static function equals($expected, $actual, $message = '') {
		if(is_array($expected) && is_array($actual)) {
			$diff = array_diff($expected, $actual);
			var_dump($diff);
		} else {
			if($expected != $actual) {
				if(empty($message)) {
					$message = "ERROR : values are not equals [ Expected=$expected, Actual=$actual ]";
				}
				throw new AssertException($message);
			}
		}
	}

}

?>