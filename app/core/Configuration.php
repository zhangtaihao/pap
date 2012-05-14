<?php
/**
 * @file
 * Architecture for configurable objects.
 */

/**
 * Configuration manager. This class is responsible for processing application
 * configuration and delegating matched configuration to component factories.
 */
class Configuration {
  // TODO
}

/**
 * Interface for a configurable factory.
 */
interface Configurable {
  /**
   * Gets XPath expressions for the component configuration to match.
   *
   * @return array
   *   XPath expressions.
   */
  public function getXPaths();

  /**
   * Matches configuration for setup.
   *
   * @param string $xpath
   *   XPath matching the supplied data.
   * @param array $data.
   *   Configuration data.
   * @throws InvalidConfigurationException
   *   If supplied configuration does not match component expectations.
   */
  public function matchConfiguration($xpath, array $data);
}

/**
 * Exception thrown when configuration is invalid.
 */
class ConfigurationException extends Exception {}

/**
 * Exception thrown when configuration is not formatted properly.
 */
class ConfigurationFormatException extends ConfigurationException {}

/**
 * Exception thrown when configuration is invalid.
 */
class InvalidConfigurationException extends ConfigurationException {}
