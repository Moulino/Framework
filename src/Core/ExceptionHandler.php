<?php 

namespace Moulino\Framework\Core;

use Moulino\Framework\Log\LoggerInterface;
use Moulino\Framework\Http\Response;

use Moulino\Framework\View\EngineInterface as ViewEngineInterface;

use Moulino\Framework\Core\Exception\BadRequestException;
use Moulino\Framework\Core\Exception\ForbiddenException;
use Moulino\Framework\Core\Exception\InternalErrorException;
use Moulino\Framework\Core\Exception\NotFoundException;
use Moulino\Framework\Core\Exception\TemplateNotFoundException;

class ExceptionHandler implements ExceptionHandlerInterface
{
	private $logger;
	private $view;
	private $mode;

	public function __construct(
		LoggerInterface $logger, 
		ViewEngineInterface $view, 
		$mode) {
		
		$this->logger = $logger;
		$this->view = $view;
		$this->mode = $mode;
	}

	public function handle(\Exception $e, $format) {
		$statusCode = 500;
		$sendMail = true;

		if($e instanceof BadRequestException) {
			$statusCode = 400;
		}

		if($e instanceof ForbiddenException) {
			$statusCode = 403;
		}

		if($e instanceof NotFoundException) {
			$statusCode = 404;
			$sendMail = false;
		}

		try {
			$filepath = $this->getViewFilepath($statusCode, $format);
		} catch (TemplateNotFoundException $e) {
			return $this->handle($e, $format);
		}

		$this->logger->error($e->__toString(), $sendMail);

		$vars = array(
			'message' => $e->getMessage(),
			'mode' => $this->mode
		);

		if($this->mode === 'dev') {
			$vars['file'] = $e->getFile();
			$vars['line'] = $e->getLine();
			$vars['code'] = $e->getCode();
			$vars['trace'] = $e->getTrace();
		}

		$content = $this->view->render($filepath, $vars);

		return new Response($content, $statusCode);
	}

	private function getViewFilepath($statusCode, $format) {
		$templateRelPath = DS.'Resources'.DS.'views'.DS.$statusCode.'.'.$format.'.php';

		$userTemplate = APP.$templateRelPath;
		if(file_exists($userTemplate)) {
			return $userTemplate;
		}

		$frameworkTemplate = FRAMEWORK.$templateRelPath;
		if(file_exists($frameworkTemplate)) {
			return $frameworkTemplate;
		}

		throw new TemplateNotFoundException("The template for the status code '$statusCode' and the format '$format' was not found.");
	}
}

?>