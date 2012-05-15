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
   * Application handler.
   * @var ApplicationHandler
   */
  protected $handler;

  /**
   * Application-related contexts.
   * @var array
   */
  protected $contexts;

  /**
   * Constructs an application with the given handler.
   *
   * @param string $handler
   *   Name of the handler to run.
   */
  protected function __construct($handler) {
    // Construct application context.
    $context = new ApplicationContext($this, $handler);
    $this->addContext($context);
    // TODO Set up critical application components.
    // TODO Set up application handler.
    //$application = new ApplicationHandler($handler, $context);
  }

  /**
   * Adds a context object to this application.
   *
   * @param Context $context
   *   Context object.
   */
  public function addContext(Context $context) {
    $this->contexts[$context->getContextName()] = $context;
  }

  /**
   * Retrieves a context given its name.
   *
   * @param string $name
   *   Name of the context.
   * @return Context
   *   Context object, or NULL if none found with the given name.
   */
  public function getContext($name) {
    return isset($this->contexts[$name]) ? $this->contexts[$name] : NULL;
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
 * Application context containing initialization-time information.
 */
class ApplicationContext extends Context {
  /**
   * Application associated with this context.
   * @var Application
   */
  protected $application;

  /**
   * Application handler name.
   * @var string
   */
  protected $handler;

  /**
   * Constructs this application context.
   *
   * @param Application $context
   *   The owner of this application context.
   */
  public function __construct(Application $owner, $handler) {
    $this->application = $owner;
    $this->handler = $handler;
    $this->setUpEnvironment();
  }

  /**
   * Sets up environment variables.
   */
  protected function setUpEnvironment() {}

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
   * Gets the associated application.
   *
   * @return Application
   *   Application object.
   */
  public function getApplication() {
    return $this->application;
  }

  /**
   * Gets the application handler name.
   *
   * @return string
   *   Name of the handler.
   */
  public function getHandler() {
    return $this->handler;
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
