<?php
/**
 * @file
 * Bootstrap file for PHPUnit.
 */

// Initialize default paths.
require_once '../web/init.default.php';

// Put the application root in the include path.
ini_set('include_path', APP_ROOT . PATH_SEPARATOR . ini_get('include_path'));
