<?php 

namespace Moulino\Framework\Http;

use Moulino\Framework\Config\Config;

class Response
{
	private $content;
	private $headers;
	private $statusCode;
	private $httpVersion = '1.0';

	private $statusText = array(
    	100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates (Experimental)',                      // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
	);

    /**
     * Construct a new response object
     * @param $content Response content
     * @param $statusCode Http status code
     * @param $headers Http headers
     * @param $format Format of the response ['html', 'json']
     */
	public function __construct($content = '', $statusCode = 200, array $headers = array()) {
        $this->content    = $content;
        $this->headers    = $headers;
        $this->statusCode = $statusCode;
        $this->charset = Config::get('app.charset') ?: 'UTF-8';
	}

	public function send(Request $request) {
		$statusText = $this->statusText[$this->statusCode];
		header(sprintf('HTTP/%s %s %s', $this->httpVersion, $this->statusCode, $this->statusText[$this->statusCode]));
		
		foreach ($this->headers as $key => $value) {
			header($key.' : '.$value);
		}

        if(!array_key_exists('Content-Type', $this->headers)) {
            $mimeType = $request->getMimeType();
            $charset = $this->charset;

            header("Content-Type: $mimeType;charset=$charset");
        }

		echo $this->content;
	}

    public function getContent() {
        return $this->content;
    }

	public function setContent($content) {
		$this->content = $content;
	}

	public function setStatusCode($statusCode) {
		$this->statusCode = $statusCode;
	}

	public function setMessage($content) {
		$this->content = $content;
	}

    public function getHeaders() {
        return $this->headers;
    }

    public function setHeaders(array $headers = array()) {
        $this->headers = $headers;
    }

	public function redirect($location) {
		$this->headers['Location'] = $location;
	}
}

?>