<?php 

namespace Moulino\Framework\Validation;

use Moulino\Framework\Model\ModelInterface;

interface ValidatorInterface
{
	public function validate(ModelInterface $model, $data);
}

?>