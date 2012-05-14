<?php
/**
 * @file
 * Application caching API.
 */

/**
 * Base cache handler.
 */
final class Cache {
  /**
   * Prevents this class from instantiating.
   */
  private function __construct() {}

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
    // TODO
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
    // TODO
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
    // TODO
  }

  /**
   * Gets an instance of
   */
}

/**
 * Bin-based cache factory.
 */
class CacheBin implements Configurable {
  /**
   * Gets an XPath expression to match cache bin configuration.
   *
   * @return string
   *   XPath expression.
   */
  public function getXPaths() {
    // TODO: Implement getXPath() method.
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

/**
 * Cache provider that does not actually cache.
 */
class NullCacheProvider implements CacheProvider {
  /**
   * Gets a cached value.
   */
  public function get($cid) {
    return NULL;
  }

  /**
   * Caches a value.
   */
  public function set($cid, $value) {}

  /**
   * Clears cached values.
   */
  public function clear($cid = NULL) {}

  /**
   * Commits cached values.
   */
  public function commit() {}
}
