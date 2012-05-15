<?php
/**
 * @file
 * Application implementation required to bootstrap a site.
 */

/**
 * Main application controller.
 */
class Application {
  /**
   * Main application.
   * @var Application
   */
  protected static $application;

  /**
   * Initializes the main application for a named handler. If an application
   * has already been initialized, no new application will be created.
   *
   * @param string $handler
   *   Name of the handler of this application.
   * @return Application
   *   Initialized application, ready to run.
   */
  public static function init($handler) {
    if (!isset(self::$application)) {
      // Create application.
      $application = new Application($handler);
      self::$application = $application;
    }
    return self::$application;
  }

  /**
   * Gets the initialized application.
   *
   * @return Application
   *   Initialized application, if any.
   */
  public static function getInstance() {
    return self::$application;
  }

  /**
   * Constructs an application with the given handler.
   *
   * @param string $handler
   *   Name of the handler to run.
   */
  protected function __construct($handler) {
    // TODO Construct application context.
    // TODO Set up critical application components.
    // TODO Set up application handler.
    //$application = new ApplicationHandler($handler, $context);
  }

  /**
   * Starts the main application.
   *
   * @param string $handler
   *   Name of the handler to run.
   */
  public function run() {
    // TODO Start application.
    //$application->run();
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
 * Abstract base class for contexts.
 */
abstract class Context {
  /**
   * Gets a name of the context class for context registry.
   *
   * @return string
   *   Context name.
   */
  abstract public function getContextName();
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
class ApplicationContext extends Context {
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
   * Gets a name of the context class for context registry.
   *
   * @return string
   *   Context name.
   */
  public function getContextName() {
    return 'application';
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
