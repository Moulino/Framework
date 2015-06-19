<?php 

namespace Moulino\Framework\Validation\Constraint;

use Moulino\Framework\Model\ModelInterface;

class RequiredValidator implements ConstraintValidatorInterface
{
	public function validate($field, $value, ModelInterface $model) {
		if(empty($value)) {
			return new ConstraintViolation($field, "This field is required.");
		}
	}
}

?>