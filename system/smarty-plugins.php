<?php
use Smarty\Template;

return [
    /**
     * Smarty function to generate a URL based on a route name and parameters.
     *
     * This function utilizes the application's router to generate a URL 
     * for a given route name, optionally accepting parameters to be included 
     * in the generated URL.
     *
     * @param array $params An associative array of parameters. Must include:
     *                      - string $name: The name of the route to generate the URL for.
     *                      - mixed ...$params: Additional parameters to be passed to the route.
     * 
     * @param Template $template The Smarty template instance from which this function is called.
     * 
     * @return string The generated URL for the specified route.
     */
    "route" => function ($params, Template $template) {
        $name = $params["name"];
        unset($params["name"]);
        $app = APPLICATION;
        return $app->router->generate($name, $params);
    }
];