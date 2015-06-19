<?php 

namespace Moulino\Framework\Validation\Constraint;

use Moulino\Framework\Model\ModelInterface;

interface ConstraintValidatorInterface
{
	public function validate($field, $value, ModelInterface $model);
}

?>