<?php 

namespace Moulino\Framework\Core;

use Moulino\Framework\Http\Request;
use Moulino\Framework\Http\Response;
use Moulino\Framework\Http\HttpException;

use Moulino\Framework\Routing\RouterInterface;
use Moulino\Framework\Routing\Route;
use Moulino\Framework\Routing\RoutesLoaderInterface;

use Moulino\Framework\Firewall\AccessRulesLoaderInterface;
use Moulino\Framework\Firewall\AccessControlInterface;
use Moulino\Framework\Firewall\AccessForbiddenException;

use Moulino\Framework\Translation\TranslatorInterface;
use Moulino\Framework\Translation\LoaderInterface as TranslationLoaderInterface;

use Moulino\Framework\Core\Exception\ForbiddenException;

class Kernel 
{
	private $router;
	private $accessControl;
	private $translator;
	private $errorHandler;
	private $mode;
	private $charset;

	public function __construct(
		RouterInterface $router, 
		AccessControlInterface $accessControl, 
		TranslatorInterface $translator,
		ErrorHandlerInterface $errorHandler,
		$mode,
		$charset) {

		$this->router        = $router;
		$this->accessControl = $accessControl;
		$this->translator    = $translator;
		$this->errorHandler  = $errorHandler;
		$this->mode          = $mode;
		$this->charset       = $charset;
	}

	public function run() {
		$request = new Request();
		$request->load();

		$response = $this->handle($request);
		$response->send($request);
	}

	private function handle(Request $request) {
		$response = null;
		
		try {
			$route = $this->router->resolve($request);

			// Checks if the request is in ajax
			if($route->isAjax() && !$request->isAjax()) {
				throw new HttpException(403, $this->translator->tr("The request must be in ajax."));
			}
			
			// Checks whether the user is authorized to access the ressource
			$this->accessControl->checkAuthorization($request);

			$response = $route->call($request);

		}	catch (\Exception $e) {
			if($this->mode === 'dev') {
				throw $e;
			}
			
			$response = $this->errorHandler->handleException($e, $request->getFormat());
		}
		return $response;
	}

} ?>