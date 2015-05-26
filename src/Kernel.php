<?php 

namespace Moulino\Framework;

use Moulino\Framework\Http\Request;
use Moulino\Framework\Http\Response;

use Moulino\Framework\Router\Router;
use Moulino\Framework\Router\Route;
use Moulino\Framework\Router\RouteLoader;

use Moulino\Framework\Exception\AuthException;

class Kernel 
{
	private $router;

	public function __construct(RouteLoader $routeLoader, Router $router) {
		$this->router = $router;

		$routeLoader->load();
	}

	public function run() {
		$request = new Request();
		$request->load();

		$response = $this->handle($request);

		$response->send();
	}

	private function handle(Request $request) {
		$response = null;
		try {
			$route = $this->router->resolve($request);
			$response = $route->call($request);
		} catch (\Exception $e) {
			$response = new Response();

			$reflector = new \ReflectionObject($e);
			if($reflector->isSubclassOf('Moulino\Framework\Exception\HttpException')) {
				$response->setStatusCode($e->getStatusCode());
			} else {
				$response->setStatusCode(500);
			}

			$response->setContent($e->getMessage());
		}

		return $response;
	}

} ?>