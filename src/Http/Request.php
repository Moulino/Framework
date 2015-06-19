<?php 

namespace Moulino\Framework\Http;

class Request
{

	private $uri; //url appelée par l'utilisateur
	private $method;
	private $get = array('test');
	private $post = array();
	private $format = '';
	private $formats = array(
		'html' => array('text/html'),
		'json' => array('application/json')
	);

	public function load() {
		$this->uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->get = $_GET;
		$this->post = $_POST;
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

	public function getMimeType($format = '') {
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

	public function getMethod() {
		return $this->method;
	}

	public function setMethod($method) {
		$this->method = $method;
	}

	public function isAjax() {
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? true : false;
	}
}

?>