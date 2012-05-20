<?php
/**
 * Application caching API.
 *
 * @package     Application
 * @subpackage  Core
 * @category    Caching
 * @author      Taihao Zhang <jason@zth.me>
 * @license     GNU General Public License v3.0
 */

/**
 * Default cache controller delegate.
 */
final class Cache {
  /**
   * Prevents this class from instantiating.
   */
  private function __construct() {}

  /**
   * Gets the encapsulated cache controller.
   */
  public static function getController() {
    $controller = Application::lookup('CacheController');
    if (!isset($controller)) {
      $controller = new CacheController();
      Application::getApplication()->getStore()->store('CacheController', $controller);
    }
    return $controller;
  }

  /**
   * Delegates a method call to the controller.
   */
  private static function delegateController($bin, $method, array $arguments = array()) {
    $controller = self::getController();
    if ($cache = $controller->getCache($bin)) {
      return call_user_func_array(array($cache, $method), $arguments);
    }
  }

  /**
   * Gets a cached value.
   *
   * @param $cid
   *   Cache identifier.
   * @param $bin
   *   Cache bin name.
   * @return
   *   Cached value.
   */
  public static function get($cid, $bin = 'default') {
    self::delegateController($bin, __FUNCTION__, array($cid));
  }

  /**
   * Caches a value.
   *
   * @param $cid
   *   Cache identifier.
   * @param $value
   *   Cached value.
   * @param $bin
   *   Cache bin name.
   * @param bool $commit
   *   Whether or not to immediately commit cached value. May not have any
   *   effect depending on the actual cache provider.
   */
  public static function set($cid, $value, $bin = 'default', $commit = FALSE) {
    self::delegateController($bin, __FUNCTION__, array($cid));
    if ($commit) {
      self::delegateController($bin, 'commit');
    }
  }

  /**
   * Clears cached values.
   *
   * @param $cid
   *   Cache identifier.
   * @param $bin
   *   Cache bin name.
   */
  public static function clear($cid = NULL, $bin = 'default') {
    self::delegateController($bin, __FUNCTION__, array($cid));
  }
}

/**
 * Bin-based cache controller.
 */
class CacheController {
  /**
   * Cache bin registry.
   * @var array
   */
  protected $caches = array();

  /**
   * Registers cache handler to a bin.
   *
   * @param CacheProvider $cache
   *   Cache handler instance.
   * @param string $bin
   *   Name of the bin.
   * @param bool $override
   *   Whether to override any existing cache.
   */
  public function registerCache(CacheProvider $cache, $bin, $override = FALSE) {
    if (!isset($this->caches[$bin]) || $override) {
      $this->caches[$bin] = $cache;
    }
  }

  /**
   * Retrieves the registered cache handler.
   *
   * @param string $bin
   *   Name of the bin to retrieve for.
   * @param bool $alwaysReturnCache
   *   Always return an object. If no cache is registered against the specified
   *   bin, an instance of NullCacheProvider is returned.
   * @return CacheProvider
   *   Registered cache handler, or NULL if none registered and
   *   $alwaysReturnCache is FALSE.
   */
  public function getCache($bin, $alwaysReturnCache = FALSE) {
    // Return the registered cache.
    if (isset($this->caches[$bin])) {
      return $this->caches[$bin];
    }
    // Return a null cache.
    elseif ($alwaysReturnCache) {
      return self::getNullCache();
    }
  }

  /**
   * Creates and returns a single instance of NullCacheProvider.
   *
   * @return NullCacheProvider
   *   Null cache handler.
   */
  protected static function getNullCache() {
    static $instance;
    if (!isset($instance)) {
      $instance = new NullCacheProvider();
    }
    return $instance;
  }
}

/**
 * Bin-based cache factory.
 */
class CacheFactory implements Configurable {
  /**
   * Gets XPath expressions to match cache bin configuration.
   *
   * @return string
   *   XPath expression.
   */
  public function getXPaths() {
    return array(
      '/application/cache',
    );
  }

  /**
   * Matches cache bin configuration.
   *
   * @param string $xpath
   *   XPath matching the supplied data.
   * @param array $configuration.
   *   Configuration data.
   * @throws InvalidConfigurationException
   *   If supplied configuration does not match component expectations.
   */
  public function matchConfiguration($xpath, array $data) {
    // TODO: Implement matchConfiguration() method.
  }

  /**
   * Registers a cache provider for a bin name.
   */
  public static function registerProvider($class, $bin = NULL) {
    // TODO
  }

  /**
   * Instantiates a cache provider for a given bin.
   *
   * @param $bin
   *   Name of the bin.
   * @return CacheProvider
   *   Cache object, instance of NullCacheProvider if none configured.
   */
  public static function createCache($bin) {
    // TODO
  }
}

/**
 * Universal cache interface.
 */
interface CacheProvider {
  /**
   * Gets a cached value.
   *
   * @param $cid
   *   Cache identifier.
   * @return
   *   Cached value.
   */
  public function get($cid);

  /**
   * Caches a value.
   *
   * @param $cid
   *   Cache identifier.
   * @param $value
   *   Cached value.
   */
  public function set($cid, $value);

  /**
   * Clears cached values.
   *
   * @param $cid
   *   Specific identifier of cached value to clear. If not specified, all
   *   values are cleared.
   */
  public function clear($cid = NULL);

  /**
   * Commits cached values.
   */
  public function commit();
}

/**
 * Base implementation of a cache provider.
 */
abstract class AbstractCacheProvider implements CacheProvider {
  /**
   * Shuts down this cache.
   */
  public function shutdown() {}

  /**
   * Commits and closes this cache.
   */
  public function __destruct() {
    $this->commit();
    $this->shutdown();
  }
}
