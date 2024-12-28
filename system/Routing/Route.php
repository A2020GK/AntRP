<?php
namespace System\Routing;

/**
 * Class Route
 *
 * Represents a single route within the application's routing system.
 * A route consists of a path, a handler, a method, and an enabled flag.
 */
class Route
{
    /**
     * The compiled path of the route.
     *
     * @var string
     */
    public string $compiledPath;

    /**
     * Constructs a new Route instance.
     *
     * @param string $path     The path of the route.
     * @param string $handler  The handler associated with the route.
     * @param string $method   The HTTP method of the route (e.g., GET, POST, PUT, DELETE).
     * @param bool   $enabled  Whether the route is enabled or not.
     */
    public function __construct(
        public string $path,
        public string $handler,
        public string $method,
        public bool $enabled
    ) {
        $this->compiledPath = $this->compilePath($path);
    }

    /**
     * Compiles the route path into a regular expression pattern.
     *
     * @param string $pattern The route path to compile.
     *
     * @return string|false The compiled pattern or false if the pattern is invalid.
     */
    protected function compilePath(string $pattern)
    {
        if (preg_match("/[^-:\/_{}()a-zA-Z\d]/", $pattern))
            return false;

        $pattern = preg_replace("#\(/\)#", "/?", $pattern);
        $allowedParamChars = "[a-zA-Z0-9\_\-]+";

        $pattern = preg_replace(
            "/:(" . $allowedParamChars . ")/",
            "(?<$1>" . $allowedParamChars . ")",
            $pattern
        );

        $pattern = preg_replace(
            "/{(" . $allowedParamChars . ")}/",
            "(?<$1>" . $allowedParamChars . ")",
            $pattern
        );

        $patternAsRegex = "@^" . $pattern . "$@D";

        return $patternAsRegex;
    }
}