<?php
namespace System\Routing;

use System\Http\Request;

/**
 * Class Router
 *
 * Manages the application's routing system, allowing routes to be added,
 * matched against incoming requests, and generated based on route names.
 */
class Router
{
    /**
     * An associative array of routes, indexed by their names.
     *
     * @var array
     */
    protected array $routes;

    /**
     * Router constructor.
     *
     * Initializes the routes array.
     */
    public function __construct()
    {
        $this->routes = [];
    }

    /**
     * Adds a new route to the router.
     *
     * @param string $name The name of the route.
     * @param Route $route The Route object to add.
     *
     * @throws Exception\RouteAlreadyExistsException If a route with the same name already exists.
     */
    public function add(string $name, Route $route)
    {
        if (isset($this->routes[$name]))
            throw new Exception\RouteAlreadyExistsException("Route $name already exists");
        $this->routes[$name] = $route;
    }

    /**
     * Loads routes from a JSON configuration array.
     *
     * @param array $configuration The configuration array containing route definitions.
     * @param string $prefix An optional prefix for nested routes.
     */
    public function loadFromJson(array $configuration, string $prefix = "")
    {
        foreach ($configuration as $name => $route) {
            if (empty($route["path"])) {
                $this->loadFromJson($configuration[$name], ($prefix !== "" ? "$prefix." : "") . $name);
            } else {
                $this->add(($prefix !== "" ? "$prefix." : "") . $name, new Route($route["path"], $route["controller"], $route["method"], $route["enabled"] ?? true));
            }
        }
    }

    /**
     * Retrieves a route by its name.
     *
     * @param string $name The name of the route to retrieve.
     *
     * @return Route|false The Route object if found, false otherwise.
     */
    public function get(string $name)
    {
        return $this->routes[$name] ?? false;
    }

    /**
     * Generates a URL path for a given route name with optional parameters.
     *
     * @param string $name The name of the route.
     * @param array $params An associative array of parameters to replace in the route path.
     *
     * @throws Exception\RouteNotFoundException If the route is not found.
     *
     * @return string The generated URL path.
     */
    public function generate(string $name, array $params = [])
    {
        if (!isset($this->routes[$name]))
            throw new Exception\RouteNotFoundException("Route $name not found");
        $urlpath = $this->routes[$name]->path;
        foreach ($params as $p => $v)
            $urlpath = str_replace('{' . $p . '}', $v, $urlpath);
        return $urlpath;
    }

    /**
     * Matches a given path and method against the registered routes.
     *
     * @param string $path The path to match.
     * @param string $method The HTTP method to match (default is "GET").
     *
     * @return array|false An array containing the route name, Route object, and matched parameters if a match is found, false otherwise.
     */
    public function match(string $path, string $method = "GET")
    {
        foreach ($this->routes as $n => $r) {
            $pathMatch = preg_match($r->compiledPath, $path, $matches);
            $methodMatch = $r->method === $method or $r->method == "any";

            if ($pathMatch && $methodMatch && $r->enabled) {
                $args = array_intersect_key($matches, array_flip(array_filter(array_keys($matches), "is_string")));
                return [$n, $r, $args];
            }
        }
        return false;
    }

    /**
     * Matches an incoming request against the registered routes.
     *
     * @param Request $request The incoming HTTP request to match.
     *
     * @return array|false An array containing the route name, Route object, and matched parameters if a match is found, false otherwise.
     */
    public function matchRequest(Request $request)
    {
        return $this->match($request->path, $request->method);
    }
}