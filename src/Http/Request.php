<?php 

namespace Moulino\Framework\Http;

class Request
{

	private $uri; //url appelée par l'utilisateur
	private $method;
	private $get = array('test');
	private $post = array();
	private $format = array();
	private $formats = array(
		'html' => array('text/html', 'application/xhtml+xml'),
		'txt'  => array('text/plain'),
		'js'   => array('application/javascript', 'application/x-javascript', 'text/javascript'),
		'css'  => array('text/css'),
		'json' => array('application/json', 'application/x-json'),
		'xml'  => array('text/xml', 'application/xml', 'application/x-xml'),
		'rdf'  => array('application/rdf+xml'),
		'atom' => array('application/atom+xml'),
		'rss'  => array('application/rss+xml'),
	);

	public function load() {
		$this->uri = $_SERVER['REQUEST_URI'];
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->get = $_GET;
		$this->post = $_POST;
	}

	public function getFormat() {

	}

	public function getParameter($key, $method = 'GET') {
		$attr = strtolower($method);
		if(isset($this->{$attr}[$key])) {
			return $this->{$attr}[$key];
		}

		throw new \Exception("Attribut '$key' introuvable dans la variable $method");
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

	
}

?>