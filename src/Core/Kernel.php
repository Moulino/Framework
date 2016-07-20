<?php 

namespace Moulino\Framework\Core;

use Moulino\Framework\Http\RequestInterface;
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

use Moulino\Framework\Service\Container;

use Moulino\Framework\Core\Exception\ForbiddenException;

class Kernel 
{
	private $container;
	private $mode;
	private $charset;

	public function __construct(Container $container, $mode, $charset) {
		$this->container = $container;
		$this->mode          = $mode;
		$this->charset       = $charset;

		//$container->get('error_handler')->register();

	}

	public function run() {
		$request = $this->container->get('request');
		$request->load();

		$response = $this->handle($request);
		$response->send($request);
	}

	private function handle(RequestInterface $request) {
		$response = null;
		
		try {
			$router = $this->container->get('router');
			$route = $router->resolve($request);

			// Checks if the request is in ajax
			if($route->isAjax() && !$request->isAjax()) {
				throw new HttpException(403, $this->translator->tr("The request must be in ajax."));
			}
			
			// Checks whether the user is authorized to access the ressource
			$this->container->get('firewall')->checkAuthorization($request);

			$response = $route->call($request);

		}	catch (\Exception $e) {
			$response = $this->container->get('exception_handler')->handle($e, $request->getFormat());
		}
		return $response;
	}

} ?>