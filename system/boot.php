<?php
/**
 * The root directory of the application, resolved to the parent directory of the current script.
 */
define("ROOT", realpath(__DIR__ . "/.."));

/**
 * The directory where configuration files are stored.
 */
define("CONFIG_DIR", ROOT . "/config");

/**
 * The directory used for temporary files and data.
 */
define("TEMP_DIR", ROOT . "/temp");

/**
 * The main application directory containing the source code.
 */
define("APP_DIR", ROOT . "/src");

/**
 * The directory containing system-related files and libraries.
 */
define("SYSTEM_DIR", ROOT . "/system");

/**
 * The directory where data files are stored.
 */
define("DATA_DIR", ROOT . "/data");