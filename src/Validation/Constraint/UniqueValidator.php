<?php 

namespace Moulino\Framework\Validation\Constraint;

use Moulino\Framework\Model\ModelInterface;

class UniqueValidator extends AbstractConstraintValidator
{
	public function validate($field, $value, ModelInterface $model) {
		$count = $model->count(array($field => $value));
		if($count > 0) {
			return new ConstraintViolation($field, $this->translator->tr("This value already exists"));
		}
	}
}

?>