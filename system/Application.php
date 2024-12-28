<?php
namespace System;

use Smarty\Smarty;
use System\Http\Request;
use System\Http\Response;
use System\Routing\Router;
use System\Routing\Route;

/**
 * Class Application
 * 
 * This class represents the main application, responsible for initializing
 * the router, loading modules, handling requests, and generating responses.
 */
class Application
{
    /**
     * @var Router $router The router instance for handling routes.
     */
    public Router $router;

    /**
     * @var TemplateEngine $templateEngine The Smarty template engine instance.
     */
    public TemplateEngine $templateEngine;

    /**
     * @var array $modules Array to hold loaded modules, organized by event groups.
     */
    public array $modules = [];
    /**
     * @var bool $isWeb Is current enviroment web or not
     */
    public bool $isWeb = true;

    /**
     * Application constructor.
     * 
     * Initializes the application by loading modules, setting up the router,
     * and configuring the Smarty template engine.
     */
    public function __construct()
    {
        $this->modules = json_decode(file_get_contents(CONFIG_DIR . "/modules.json"), true);
        $this->loadModules();

        $this->event("boot");
        $this->isWeb = php_sapi_name() !== "cli";

        if ($this->isWeb) {
            $this->router = new Router();
            $this->router->loadFromJson(
                json_decode(file_get_contents(CONFIG_DIR . "/routes.json"), true)
            );

            $this->templateEngine = new TemplateEngine();
        }
        define("APPLICATION", $this);
    }

    /**
     * Loads modules defined in the configuration.
     * 
     * Iterates through the modules defined in the modules.json file and
     * instantiates each module, associating it with its event group.
     */
    protected function loadModules()
    {
        $modules = $this->modules;
        $this->modules = [];
        foreach ($modules as $eventGroup => $moduleGroup) {
            $this->modules[$eventGroup] = [];
            foreach ($moduleGroup as $module) {
                $moduleClasss = "\\App\\Modules\\$module";
                $moduleInstance = new $moduleClasss($this);
                $this->modules[$eventGroup][] = $moduleInstance;
            }
        }
    }

    /**
     * Triggers an event for the loaded modules.
     * 
     * @param string $event The name of the event to trigger.
     * @param mixed ...$args Optional arguments to pass to the event handler.
     */
    public function event(string $event, ...$args)
    {
        foreach ($this->modules[$event] as $module)
            $module->run(...$args);
    }

    /**
     * Runs the specified route with the provided request and arguments.
     * 
     * @param Request $request The incoming HTTP request.
     * @param string $name The name of the route.
     * @param Route $route The route object containing route details.
     * @param array $args Additional arguments to pass to the route handler.
     * @return mixed The response from the route handler.
     */
    public function runRoute(Request $request, string $name, Route $route, array $args)
    {
        $prefix = "\\App\\Controllers\\";

        $handler = explode("::", $route->handler, 2);
        $handlerAction = $handler[1];
        $handlerClass = $handler[0];

        $handler = $prefix . $handlerClass;

        $handler = new $handler($this, $request);

        return $handler->$handlerAction($request, $args);
    }

    /**
     * Processes the incoming request and generates a response.
     * 
     * @param Request $request The incoming HTTP request.
     * @return Response The generated HTTP response.
     */
    public function run(Request $request): Response
    {
        $response = new Response(204);
        $this->event("request", $request, $response);
        if ($route = $this->router->matchRequest($request)) {
            $response = $this->runRoute($request, ...$route);
        } else {
            $response = new Response(404);
        }
        if ($response->statusCode != 200 && $response->statusCode != 302) {
            $route = $this->router->get("error");
            $response = $this->runRoute($request, "error", $route, ["code" => $response->statusCode]);
        }
        $this->event("response", $request, $response);
        return $response;
    }
    public function runCLI() {
        $this->event("run_cli");
    }
}