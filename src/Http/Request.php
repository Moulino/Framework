<?php 

namespace Moulino\Framework\Http;

class Request implements RequestInterface
{

	private $uri; //url appelée par l'utilisateur
	private $path;
	private $baseUrl;
	private $method;
	private $get = array();
	private $post = array();
	private $format = '';
	private $formats = array(
		'html' => array('text/html'),
		'json' => array('application/json')
	);
	private $locale;
	private $defaultLocale;

	public function __construct($locale) {
		$this->defaultLocale = $locale;
	}
	
	public function load() {
		$this->baseUrl = 'http://'.$_SERVER['HTTP_HOST'];
		$this->uri = $_SERVER['REQUEST_URI'];
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->get = $_GET;
		$this->post = $_POST;

		$this->path = $this->loadPath($this->uri);

		$lang = $this->getParameter('lang');

		$this->locale = ($lang) ? $lang : $this->defaultLocale;
	}

	private function loadPath($uri) {
		$regex = '#^/(fr|en|en|nl)(.*)$#';

		if(preg_match($regex, $uri, $matches)) {
			return $matches[2];
		}
		return $uri;
	}

	public function getFormat() {
		if(!empty($this->format)) return $this->format;

		$mimeTypesAccepted = $_SERVER['HTTP_ACCEPT'];

		if(false !== $pos = strpos($mimeTypesAccepted, ';')) {
			$mimeTypesAccepted = substr($mimeTypesAccepted, 0, $pos);
		}

		$mimeTypesAccepted = explode(',', $mimeTypesAccepted);

		$this->format = 'html';
		foreach ($this->formats as $format => $mimeTypes) {

			foreach ($mimeTypesAccepted as $mimeTypeAccepted) {
				if(in_array($mimeTypeAccepted, $mimeTypes)) {
					$this->format = $format;
					break;
				}
			}
		}
		return $this->format;
	}

	public function getMimeType() {
		$format = $this->getFormat();
		return $this->formats[$format][0];
	}

	public function getParameter($key, $method = 'GET') {
		$attr = strtolower($method);
		if(isset($this->{$attr}[$key])) {
			return $this->{$attr}[$key];
		}
		return null;
	}

	public function setParameter($key, $value, $method = 'GET') {
		$attr = strtolower($method);
		$this->{$attr}[$key] = $value;
	}

	public function getUri() {
		return $this->uri;
	}

	public function setUri($uri) {
		$this->uri = $uri;
	}

	public function getBaseUrl() {
		return $this->baseUrl;
	}

	public function getMethod() {
		return $this->method;
	}

	public function setMethod($method) {
		$this->method = $method;
	}

	public function getPath() {
		return $this->path;
	}

	public function isAjax() {
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? true : false;
	}

	public function getContent() {
		return file_get_contents('php://input');
	}

	public function getHeaders() {
		return getallheaders();
	}

	public function setLocale($locale) {
		$this->locale = $locale;
	}

	public function getLocale() {
		return $this->locale;
	}
}

?>