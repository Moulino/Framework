<?php 

namespace Moulino\Framework\Validation\Constraint;

class ConstraintViolationList implements ConstraintViolationListInterface
{
	private $violations;

	public function add(ConstraintViolationInterface $violation) {
		$this->violations[] = $violation;
	}

	public function isEmpty() {
		return (count($this->violations) > 0) ? false : true;
	}

	public function toArray() {
		$data = array();

		foreach ($this->violations as $violation) {
			$data[$violation->getField()][] = $violation->getMessage();
		}

		return $data;
	}
}

?>