<?php
namespace System\Http;

/**
 * Class Response
 *
 * This class represents an HTTP response, including the status code, body content,
 * headers, and cookies that can be sent back to the client.
 */
class Response
{
    /**
     * @var array $cookies A static array to hold cookies to be sent with the response.
     */
    public static array $cookies = [];

    /**
     * Response constructor.
     *
     * @param int $statusCode The HTTP status code for the response. Defaults to 200 (OK).
     * @param string $body The body content of the response. Defaults to an empty string.
     * @param array $headers Optional additional headers to include in the response. Defaults to an empty array.
     */
    public function __construct(
        public int $statusCode = 200,
        public string $body = "",
        public array $headers = []
    ) {
    }

    /**
     * Set a cookie globally for the response.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     * @param int|null $expires Optional expiration time for the cookie (timestamp). Defaults to null.
     * @param string $path The path on the server in which the cookie will be available. Defaults to "/".
     */
    public static function setCookieGlobal(string $name, string $value, int|null $expires = null, string $path = "/") {
        self::$cookies[$name] = [$name, $value, $expires, $path];
    }

    /**
     * Set a cookie for the response.
     *
     * This method calls the global cookie setter to register the cookie.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     * @param int|null $expires Optional expiration time for the cookie (timestamp). Defaults to null.
     * @param string $path The path on the server in which the cookie will be available. Defaults to "/".
     */
    public function setCookie(string $name, string $value, int|null $expires, string $path = "/") {
        self::setCookieGlobal($name, $value, $expires, $path);
    }

    /**
     * Set a response header.
     *
     * @param string $name The name of the header to set.
     * @param string $value The value of the header.
     * @return $this Returns the current instance for method chaining.
     */
    public function header(string $name, string $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Send the response to the client.
     *
     * This method sets the HTTP response code, sends the headers, sets cookies, 
     * and outputs the response body.
     *
     * @return void
     */
    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        foreach (self::$cookies as $cookie) {
            setcookie(...$cookie);
        }

        echo $this->body;
    }
}