<?php 

namespace Moulino\Framework\Validation\Constraint;

use Moulino\Framework\Model\ModelInterface;
use Moulino\Framework\Translation\TranslatorInterface;

abstract class AbstractConstraintValidator implements ConstraintValidatorInterface
{
	protected $translator;
	
	public function __construct(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

	abstract public function validate($field, $value, ModelInterface $model);
}
?>