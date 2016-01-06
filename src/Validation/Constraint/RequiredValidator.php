<?php 

namespace Moulino\Framework\Validation\Constraint;

use Moulino\Framework\Model\ModelInterface;

class RequiredValidator extends AbstractConstraintValidator
{
	public function validate($field, $value, ModelInterface $model) {
		if(empty($value)) {
			return new ConstraintViolation($field, $this->translator->tr("This field is required"));
		}
	}
}

?>