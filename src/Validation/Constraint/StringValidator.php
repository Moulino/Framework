<?php 

namespace Moulino\Framework\Validation\Constraint;

use Moulino\Framework\Model\ModelInterface;

class StringValidator extends AbstractConstraintValidator
{
	public function validate($field, $value, ModelInterface $model) {
		if(!preg_match('#^[\w\p{L}\p{N}\p{Pd}\s]+$#u', $value)) {
			return new ConstraintViolation($field, $this->translator->tr("This field must be a string"));
		}
	}
}

?>