<?php 

namespace Moulino\Framework\Validation\Constraint;

class ConstraintViolation implements ConstraintViolationInterface
{
	private $field;
	private $message;

	public function __construct($field, $message) {
		$this->field = $field;
		$this->message = $message;
	}

	public function getField() {
		return $this->field;
	}

	public function getMessage() {
		return $this->message;
	}
}

?>