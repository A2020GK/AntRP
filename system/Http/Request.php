<?php
namespace System\Http;

use System\Routing\Route;

/**
 * Class Request
 *
 * This class represents an HTTP request, encapsulating the request method, URI, headers,
 * query parameters, post parameters, cookies, body, and any additional data.
 */
class Request
{
    public Route $route; // The route associated with the request.
    public string $path; // The path component of the URI.

    /**
     * Request constructor.
     *
     * @param string $method The HTTP method of the request (e.g., GET, POST).
     * @param string $uri The URI of the request.
     * @param array $headers The headers sent with the request.
     * @param array $getParams The query parameters from the request URI.
     * @param array $postParams The post parameters from the request body.
     * @param array $cookies The cookies sent with the request.
     * @param string $body The raw body of the request.
     * @param array $data Additional data associated with the request.
     */
    public function __construct(
        public string $method,
        public string $uri,
        public array $headers = [],
        public array $getParams = [],
        public array $postParams = [],
        public array $cookies = [],
        public string $body = '',
        public array $data = []
    ) {
        $this->path = parse_url($this->uri, PHP_URL_PATH);
    }

    /**
     * Construct a Request object from the global PHP variables.
     *
     * @return self A new instance of the Request class populated with data from the global state.
     */
    public static function constructFromGlobals(): self
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $headers = getallheaders();
        $getParams = $_GET;
        $postParams = $_POST;
        $cookies = $_COOKIE;
        $body = file_get_contents('php://input');

        return new self($method, $uri, $headers, $getParams, $postParams, $cookies, $body);
    }
}