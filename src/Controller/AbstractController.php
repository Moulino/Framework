<?php 

namespace Moulino\Framework\Controller;

use Moulino\Framework\Service\Container;
use Moulino\Framework\View\EngineInterface;
use Moulino\Framework\Http\Response;

use Moulino\Framework\Validation\Constraint\ConstraintViolationListInterface;
	
abstract class AbstractController implements ControllerInterface
{
	protected $container;
	protected $view;

	public function __construct() {
		$this->container = $GLOBALS['service_container'];
		$this->view = $this->container->get('view');
	}

	public function getModel($modelName) {
		return $this->container->get($modelName.'_model');
	}

	public function render($view, $vars = array()) {

		$folder = ucfirst(strstr($view, ':', true));
		$file = substr(strstr($view, ':'), 1).'.php';

		$filepath = VIEW.DS.$folder.DS.$file;
		
		$content = $this->view->render($filepath, $vars);
		return new Response($content);
	}

	public function renderValidationInJson(ConstraintViolationListInterface $list) {
		$headers = array(
			'Content-Type' => 'application/json'
		);

		$content = json_encode(array(
			'validation' => $list->toArray()
			));

		$response = new Response($content, 400, $headers);
		return $response;
	}
}
?>