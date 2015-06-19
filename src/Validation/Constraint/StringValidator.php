<?php 

namespace Moulino\Framework\Validation\Constraint;

use Moulino\Framework\Model\ModelInterface;

class StringValidator implements ConstraintValidatorInterface
{
	public function validate($field, $value, ModelInterface $model) {
		if(!preg_match('#^[\w]+$#', $value)) {
			return new ConstraintViolation($field, "The field must be a string.");
		}
	}
}

?>