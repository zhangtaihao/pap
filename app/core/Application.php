<?php
/**
 * @file
 * Application implementation required to bootstrap a site.
 */

/**
 * Main application controller.
 */
final class Application {
  /**
   * Prevents this class from being instantiated.
   */
  private function __construct() {}

  /**
   * Starts the main application.
   *
   * @param string $handler
   *   Name of the handler to run.
   * @param string $confRoot
   *   If specified, the directory containing application configuration.
   *   Otherwise, the constant CONF_ROOT is used.
   * @param string $appRoot
   *   If specified, the application code root directory. Otherwise, the
   *   constant APP_ROOT is used.
   */
  static public function run($handler, $confRoot = NULL, $appRoot = NULL) {
    // Check arguments.
    if (isset($confRoot) && (!file_exists($confRoot) || !is_dir($confRoot))) {
      throw new InvalidArgumentException('Specified configuration root is not a valid directory.');
    }
    if (isset($appRoot) && (!file_exists($appRoot) || !is_dir($appRoot))) {
      throw new InvalidArgumentException('Specified application root is not a valid directory.');
    }

    // Check constants.
    if (!isset($confRoot) && (!defined('CONF_ROOT') || !file_exists(CONF_ROOT) || !is_dir(CONF_ROOT))) {
      throw new InvalidArgumentException('Global configuration root is not defined or is invalid.');
    }
    if (!isset($appRoot) && (!defined('APP_ROOT') || !file_exists(APP_ROOT) || !is_dir(APP_ROOT))) {
      throw new InvalidArgumentException('Global application root is not defined or is invalid.');
    }

    // Construct context.
    $context = new ApplicationContext(array(
      'platformRoot' => PLATFORM_ROOT,
      'webRoot' => WEB_ROOT,
      'appRoot' => $appRoot,
      'confRoot' => $confRoot,
    ));

    // Start application.
    $application = new ApplicationHandler($handler, $context);
    $application->run();
  }
}

/**
 * Generic application handler.
 */
class ApplicationHandler {
  /**
   * Application context.
   * @var ApplicationContext
   */
  protected $context;

  /**
   * Instantiates an application with the specified configuration.
   *
   * @param string $handler
   *   Configured handler name.
   * @param ApplicationContext $context
   *   Full path to the configuration file.
   */
  public function __construct($handler, ApplicationContext $context) {
    $this->context = $context;
    $this->initialize();
  }

  /**
   * Initializes the application.
   */
  protected function initialize() {
    // TODO
  }

  /**
   * Runs the application.
   */
  public function run() {
    // TODO
  }
}

/**
 * Read-only application context. Context properties are accessed as object
 * attributes. This context provides the following properties:
 *
 * - platformRoot: Platform root directory.
 * - webRoot: Web documents directory.
 * - appRoot: Application root directory.
 * - confRoot: Configuration directory.
 */
class ApplicationContext {
  /**
   * Context properties.
   * @var array
   */
  protected $properties;

  /**
   * Constructs this context object.
   *
   * @param array $properties
   *   Array of properties to initialize the context with.
   */
  public function __construct(array $properties) {
    $this->properties = array();
    foreach ($this->getPropertyKeys() as $key) {
      if (isset($properties[$key])) {
        $this->properties[$key] = $properties[$key];
      }
      else {
        throw new InvalidArgumentException('Missing context property: ' . $key);
      }
    }
  }

  /**
   * Returns property keys to check.
   *
   * @return array
   *   Keys of context property values.
   */
  protected function getPropertyKeys() {
    return array('platformRoot', 'webRoot', 'appRoot', 'confRoot');
  }

  /**
   * Magic method to get property.
   */
  public function __get($name) {
    if (isset($this->properties[$name])) {
      return $this->properties[$name];
    }
  }
}

/**
 * Application class loader.
 */
class ApplicationLoader {
  /**
   * Application root.
   * @var string
   */
  protected $appRoot;

  /**
   * Constructs this loader for a given application root.
   *
   * @param string $appRoot
   *   Directory path to the application root.
   */
  public function __construct($appRoot) {
    $this->appRoot = $appRoot;
    $this->initialize();
  }

  /**
   * Initializes this loader.
   */
  protected function initialize() {
    // TODO Load class registry.
    // Register this loader.
    spl_autoload_register(array($this, 'autoload'));
  }

  /**
   * Autoloads a class.
   *
   * @see http://www.php.net/manual/en/function.spl-autoload.php
   */
  public function autoload($class) {
    // TODO Load class.
  }

  /**
   * Finalizes this loader.
   */
  public function shutdown() {
    spl_autoload_unregister(array($this, 'autoload'));
  }
}
