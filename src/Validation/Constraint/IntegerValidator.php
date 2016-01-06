<?php 

namespace Moulino\Framework\Validation\Constraint;

use Moulino\Framework\Model\ModelInterface;

class IntegerValidator extends AbstractConstraintValidator
{
	public function validate($field, $value, ModelInterface $model) {
		if(!preg_match('#\d#', $value)) {
			return new ConstraintViolation($field, $this->translator->tr("This field must be an integer number"));
		}
	}
}

?>