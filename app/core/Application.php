<?php
/**
 * @file
 * Application implementation required to bootstrap a site.
 */

if (!defined('DEFAULT_CONF_NAME')) {
  /**
   * Default configuration name.
   * @var string
   */
  define('DEFAULT_CONF_NAME', 'default');
}

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
   * @param string $confName
   *   Name of the configuration to load.
   * @return Application
   *   Initialized application, ready to run.
   */
  public static function init($handler, $confName = DEFAULT_CONF_NAME) {
    if (!isset(self::$application)) {
      // Create application.
      $application = new Application($handler, $confName);
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
   * Application-related contexts.
   * @var array
   */
  protected $contexts;

  /**
   * Application object store.
   * @var ApplicationStore
   */
  protected $store;

  /**
   * Constructs an application with the given handler.
   *
   * @param string $handler
   *   Name of the handler to run.
   * @param string $confName
   *   Name of the configuration to load.
   */
  protected function __construct($handler, $confName) {
    $this->contexts = array();
    $this->store = new ApplicationStore($this);

    // Initialize application context.
    $context = new ApplicationContext($this, $handler, $confName);
    $this->addContext($context);

    // Initialize main application components.
    $this->setUp();
  }

  /**
   * Sets up the application components.
   */
  protected function setUp() {
    // TODO Set up application cache.

    // TODO Set up application loader.

    // TODO Set up configuration.

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
   * Gets the application store.
   *
   * @return ApplicationStore
   *   Application store.
   */
  public function getStore() {
    return $this->store;
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
 * Registry for objects used alongside the application.
 */
class ApplicationStore {
  /**
   * Owner application
   * @var Application
   */
  protected $application;

  /**
   * Object store array.
   * @var array
   */
  protected $store;

  /**
   * Constructs a store.
   *
   * @param Application $owner
   *   Owner of this store.
   */
  public function __construct(Application $owner) {
    $this->application = $owner;
    $this->store = array();
  }

  /**
   * Stores an object given a name, if it does not already exist.
   *
   * @param string $name
   *   Name to store the object with.
   * @param object $object
   *   The object to store.
   */
  public function store($name, $object) {
    if (!isset($this->store[$name])) {
      $this->store[$name] = $object;
    }
  }

  /**
   * Retrieves an object by looking up its name.
   *
   * @param string $name
   *   Name to look up.
   * @return object
   *   Object stored with the given name.
   */
  public function lookup($name) {
    return isset($this->store[$name]) ? $this->store[$name] : NULL;
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
   * Configuration name.
   * @var string
   */
  protected $confName;

  /**
   * Constructs this application context.
   *
   * @param Application $context
   *   The owner of this application context.
   * @param string $confName
   *   Name of the configuration to load.
   */
  public function __construct(Application $owner, $handler, $confName) {
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
