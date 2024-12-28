<?php
namespace System\Http;

use System\Routing\Route;

/**
 * Class RedirectResponse
 *
 * This class represents an HTTP response that performs a redirect to a specified URL.
 * It extends the base Response class and sets the HTTP status code to 302 (Found).
 */
class RedirectResponse extends Response {
    
    /**
     * RedirectResponse constructor.
     *
     * @param string $url The URL to which the response should redirect.
     */
    public function __construct(string $url) {
        parent::__construct(302);
        $this->header("Location", $url);
    }

    /**
     * Generate a redirect response to a named route.
     *
     * @param string $name The name of the route to redirect to.
     * @param array $args Optional arguments to pass to the route generation.
     * @return RedirectResponse A new instance of RedirectResponse pointing to the generated route URL.
     */
    public static function toRoute(string $name, array $args = []) {
        return new RedirectResponse("/"); // self(APPLICATION->router->generate($name, $args));
    }
}