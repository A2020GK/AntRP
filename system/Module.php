<?php
namespace System;

/**
 * Abstract base class for modules in the application.
 * 
 * This class provides a basic interface for modules to interact with the application.
 */
class Module
{
    /**
     * Constructor for the Module class.
     * 
     * Initializes the module with the given application instance.
     * 
     * @param Application $application The application instance that this module belongs to.
     */
    public function __construct(protected Application $application)
    {

    }

}