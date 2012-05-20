<?php
/**
 * Core application components required to bootstrap a request.
 *
 * @package     Application
 * @subpackage  Core
 * @author      Taihao Zhang <jason@zth.me>
 * @license     GNU General Public License v3.0
 */

if (!defined('APPLICATION_CLASS')) {
  /**
   * Default application class name.
   * @var string
   */
  define('APPLICATION_CLASS', 'DefaultApplication');
}

if (!defined('APPLICATION_CONTEXT_CLASS')) {
  /**
   * Default application context class name.
   * @var string
   */
  define('APPLICATION_CONTEXT_CLASS', 'ApplicationContext');
}

if (!defined('CONF_NAME')) {
  /**
   * Default configuration name.
   * @var string
   */
  define('CONF_NAME', 'default');
}

/**
 * Main application base class and controller.
 */
abstract class Application {
  /**
   * Main application.
   * @var Application
   */
  protected static $application;

  /**
   * Bootstraps the main application. If an application has already been
   * initialized, no new application will be created.
   *
   * @param array $bootOptions
   *   Application bootstrap options, to be wrapped in a context before the
   *   application object is constructed.
   * @param string $applicationClass
   *   Optional class name for the application to bootstrap. If none is given,
   *   the default APPLICATION_CLASS is used.
   * @return Application
   *   Initialized application, ready to run.
   */
  public static function boot(array $bootOptions, $applicationClass = APPLICATION_CLASS) {
    if (!isset(self::$application)) {
      // Create application.
      $contextClass = APPLICATION_CONTEXT_CLASS;
      $context = new $contextClass($bootOptions);
      $application = new $applicationClass($context);
      self::$application = $application;
    }
    return self::$application;
  }

  /**
   * Bootstraps and runs the main application.
   *
   * @param array $bootOptions
   *   Application bootstrap options, to be wrapped in a context before the
   *   application object is constructed.
   * @param array $runOptions
   *   Options for running an application, to be given to the run() method.
   * @param string $applicationClass
   *   Optional class name for the application to bootstrap. If none is given,
   *   the default APPLICATION_CLASS is used.
   * @return Application
   *   Initialized application, ready to run.
   */
  public static function bootAndRun(array $bootOptions, array $runOptions = array(), $applicationClass = APPLICATION_CLASS) {
    $application = self::boot($bootOptions, $applicationClass);
    $application->run($runOptions);
    return $application;
  }

  /**
   * Gets the main application.
   *
   * @return Application
   *   Initialized application, if any.
   */
  public static function getApplication() {
    return self::$application;
  }

  /**
   * Looks up a context from the application.
   *
   * @param string $name
   *   Name to look up.
   * @return Context|null
   *   Context with the given name, or NULL if no such context was found.
   * @throws ApplicationNotInitializedException
   *   If there is no application instance.
   */
  public static function getContext($name) {
    if (!$application = self::getApplication()) {
      throw new ApplicationNotInitializedException();
    }
    return $application->getContexts()->getContext($name);
  }

  /**
   * Application-related contexts.
   * @var Contexts
   */
  protected $contexts;

  /**
   * Constructs an application.
   *
   * @param ApplicationContext $context
   *   Application context.
   * @throws ApplicationInitializationException
   *   If there is an error during initialization.
   */
  protected function __construct($context) {
    $this->contexts = new Contexts();
    // Add the application context.
    $this->contexts->addContext($context);
    // Initialize main application components.
    $this->setUp();
  }

  /**
   * Sets up the application components.
   *
   * @throws ApplicationInitializationException
   *   If there is an error during initialization.
   */
  protected function setUp() {}

  /**
   * Gets the application context store.
   *
   * @return Contexts
   *   Context store.
   */
  public function getContexts() {
    return $this->contexts;
  }

  /**
   * Runs the application.
   *
   * @param array $runOptions
   *   Options for running the application.
   */
  abstract public function run(array $runOptions);
}

/**
 * Default application implementation.
 */
class DefaultApplication extends Application {
  /**
   * Bootstraps the default application.
   */
  protected function setUp() {
    parent::setUp();
    $contexts = $this->getContexts();

    // Load the cache API for setup.
    require_once APP_ROOT . '/core/Cache.php';

    // Set up application registry.
    $registry = new ApplicationRegistry();
    // TODO $store->store('ApplicationRegistry', $registry);

    // TODO Set up configuration.
  }

  /**
   * Runs the application.
   *
   * @param array $runOptions
   *   Options for running the application.
   */
  public function run(array $runOptions) {
    // TODO Set up application handler.
    //$handler = new ApplicationHandler($handler, $context);

    // TODO Run application handler.
    //$handler->run();
  }
}

/**
 * Context store for managing and looking up contexts.
 */
final class Contexts {
  /**
   * Object store array.
   * @var array
   */
  protected $store = array();

  /**
   * Adds a context object to the store.
   *
   * @param Context $context
   *   Context object.
   */
  public function addContext(Context $context) {
    $this->store[$context->getContextName()] = $context;
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
    return isset($this->store[$name]) ? $this->store[$name] : NULL;
  }
}

/**
 * Interface of objects as contexts.
 */
interface Context {
  /**
   * Gets a name of the context class for context registry.
   *
   * @return string
   *   Context name.
   */
  public function getContextName();
}

/**
 * Application context containing initialization-time information.
 */
class ApplicationContext extends ArrayObject implements Context {
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
   * Constructs this application context.
   *
   * @param array $data
   *   Application context data to encapsulate.
   */
  public function __construct(array $data) {
    parent::__construct($data, ArrayObject::ARRAY_AS_PROPS);
    $this->setUpEnvironment();
  }

  /**
   * Sets up environment variables.
   */
  protected function setUpEnvironment() {}
}

/**
 * Application information container.
 */
class ApplicationInformation {
  /**
   * Information producers.
   * @var ApplicationInformationConsumable[]
   */
  protected $sources;

  /**
   * Constructs an information container.
   */
  public function __construct() {
    $this->sources = array();
  }

  /**
   * Adds an information source to be processed.
   *
   * @param ApplicationInformationConsumable $source
   *   Metadata source.
   */
  public function addSource(ApplicationInformationConsumable $source) {
    $this->sources[] = $source;
  }
}

/**
 * Interface for implementing a metadata provider.
 */
interface ApplicationInformationConsumable {
  /**
   * Processes produced metadata into useful information with a consumer.
   *
   * @param ApplicationInformationConsumer $consumer
   *   Metadata consumer.
   */
  public function process(ApplicationInformationConsumer $consumer);
}

/**
 * Interface for consuming metadata to extract component-specific information
 * for setting up the application.
 */
interface ApplicationInformationConsumer {
  /**
   * Consumes data for setting up data.
   *
   * @param array $data
   *   Package metadata array structure.
   */
  public function consume(array $data);
}

/**
 * Application registry and class loader.
 */
class ApplicationRegistry {
  /**
   * Constructs this loader for a given application root.
   */
  public function __construct() {
    // Load class registry.
    $this->loadRegistry();

    // Register this loader.
    spl_autoload_register(array($this, 'autoload'));
  }

  /**
   * Loads the class registry.
   */
  protected function loadRegistry() {
    // TODO
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
  public function __destruct() {
    spl_autoload_unregister(array($this, 'autoload'));
  }
}

/**
 * Exception thrown because application is not initialized.
 */
class ApplicationNotInitializedException extends Exception {}

/**
 * Exception thrown during application initialization.
 */
class ApplicationInitializationException extends Exception {}
