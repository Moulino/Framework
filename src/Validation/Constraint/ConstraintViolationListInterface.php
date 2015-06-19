<?php 

namespace Moulino\Framework\Validation\Constraint;

interface ConstraintViolationListInterface
{
	public function add(ConstraintViolationInterface $violation);
	public function isEmpty();
	public function toArray();
}

?>