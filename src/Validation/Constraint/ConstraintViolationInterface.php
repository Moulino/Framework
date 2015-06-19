<?php 

namespace Moulino\Framework\Validation\Constraint;

interface ConstraintViolationInterface
{
	public function __construct($field, $message);
	public function getField();
	public function getMessage();
}

?>