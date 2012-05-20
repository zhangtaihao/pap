  <?php
/**
 * @file
 * Default application initialization script.
 */

if (!defined('PLATFORM_ROOT')) {
  /**
   * Application platform root directory.
   */
  define('PLATFORM_ROOT', realpath(dirname(__FILE__) . '/..'));
}

if (!defined('WEB_ROOT')) {
  /**
   * Web directory.
   */
  define('WEB_ROOT', PLATFORM_ROOT . '/web');
}

if (!defined('APP_ROOT')) {
  /**
   * Application code directory.
   */
  define('APP_ROOT', PLATFORM_ROOT . '/app');
}

if (!defined('CONF_ROOT')) {
  /**
   * Configuration directory.
   */
  define('CONF_ROOT', PLATFORM_ROOT . '/conf');
}

if (!defined('LIB_ROOT')) {
  /**
   * Libraries directory.
   */
  define('LIB_ROOT', PLATFORM_ROOT . '/lib');
}
if (!defined('APPLIB_ROOT')) {
  /**
   * Directory for libraries included with the application.
   */
  define('APPLIB_ROOT', APP_ROOT . '/lib');
}
// Set up libraries for include.
ini_set('include_path', LIB_ROOT . PATH_SEPARATOR . APPLIB_ROOT . PATH_SEPARATOR . ini_get('include_path'));
