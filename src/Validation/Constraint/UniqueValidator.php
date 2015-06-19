<?php 

namespace Moulino\Framework\Validation\Constraint;

use Moulino\Framework\Model\ModelInterface;

class UniqueValidator implements ConstraintValidatorInterface
{
	public function validate($field, $value, ModelInterface $model) {
		$count = $model->count(array($field => $value));
		if($count > 0) {
			return new ConstraintViolation($field, "This value already exists.");
		}
	}
}

?>