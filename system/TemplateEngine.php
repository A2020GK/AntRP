<?php
namespace System;

class TemplateEngine extends \Smarty\Smarty {
    public function __construct() {
        parent::__construct();

        $this->setTemplateDir(APP_DIR . "/Views");
        $this->setCompileDir(TEMP_DIR . "/smarty/compile");

        $plugins = require_once SYSTEM_DIR . "/smarty-plugins.php";

        foreach ($plugins as $plugin=>$callback) {
            $this->registerPlugin(\Smarty\Smarty::PLUGIN_FUNCTION, $plugin, $callback);
        }

        require_once SYSTEM_DIR . "/legacy/patch-smarty-strftime.php";
    }
}