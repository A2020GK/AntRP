<?php
namespace System;

use System\Http\Response;

/**
 * Abstract base class for controllers in the application.
 * 
 * This class provides common functionality for all controllers, 
 * including the ability to render templates with variables.
 */
abstract class Controller {
    
    /**
     * Constructor for the Controller class.
     *
     * @param Application $application The application instance that this controller belongs to.
     */
    public function __construct(
        protected Application $application
    ){
    }

    /**
     * Renders a template file with the provided variables.
     *
     * This method assigns the given variables to the template engine 
     * and returns a Response object containing the rendered template.
     *
     * @param string $filename The name of the template file to render (without the .tpl extension).
     * @param array $vars An associative array of variables to be passed to the template.
     * 
     * @return Response A Response object containing the HTTP status code and the rendered template.
     */
    protected function renderTemplate(string $filename, array $vars = []) {
        foreach($vars as $name => $value) {
            $this->application->templateEngine->assign($name, $value);
        }
        return new Response(200, $this->application->templateEngine->fetch(str_replace(".", "/", $filename) . ".tpl"));
    }
}