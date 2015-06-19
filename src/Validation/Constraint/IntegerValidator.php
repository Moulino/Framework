<?php 

namespace Moulino\Framework\Validation\Constraint;

use Moulino\Framework\Model\ModelInterface;

class IntegerValidator implements ConstraintValidatorInterface
{
	public function validate($field, $value, ModelInterface $model) {
		if(!preg_match('#\d#', $value)) {
			return new ConstraintViolation($field, "The field must be an integer number.");
		}
	}
}

?>