<?php 

namespace Moulino\Framework\Validation;

use Moulino\Framework\Validation\Exception\AttributMissingException;
use Moulino\Framework\Validation\Exception\ConstraintUndefinedException;

use Moulino\Framework\Validation\Constraint\IntegerValidator;
use Moulino\Framework\Validation\Constraint\RequiredValidator;
use Moulino\Framework\Validation\Constraint\StringValidator;
use Moulino\Framework\Validation\Constraint\UniqueValidator;

use Moulino\Framework\Validation\Constraint\ConstraintViolationInterface;
use Moulino\Framework\Validation\Constraint\ConstraintViolationList;

use Moulino\Framework\Model\ModelInterface;

use Moulino\Framework\Config\ConfigInterface;

class Validator implements ValidatorInterface
{
	private $translator;
	private $entityConfig;
	private $violationList; // constraint violation list

	function __construct($translator, array $entityConfig) {
		$this->translator = $translator;
		$this->entityConfig = $entityConfig;
		$this->violationList = new ConstraintViolationList();
	}

	public function validate(ModelInterface $model, $data, $exclusions = array()) {
		$entityName = $model->getEntityName();

		$fields = $this->entityConfig[$entityName]['validation'];

		foreach ($fields as $field => $constraints) {
			$constraints = explode('|', $constraints);

			if(!array_key_exists($field, $data)) {
				throw new AttributMissingException("The field '$field' is missing.");
			}

			foreach ($constraints as $constraint) {
				if(!in_array($constraint, $exclusions)) {
					$class = 'Moulino\\Framework\\Validation\\Constraint\\'.ucfirst($constraint).'Validator';

					if(class_exists($class)) {
						$constraintValidator = new $class($this->translator);

						$violation = $constraintValidator->validate($field, $data[$field], $model);

						if($violation instanceof ConstraintViolationInterface) {
							$this->violationList->add($violation);
						}
					} else {
						throw new ConstraintUndefinedException("The constraint '$constraint' does not exist.");
					}
				}
			}
		}
		return $this->violationList;
	}
}

?>