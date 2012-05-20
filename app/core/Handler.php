<?php
/**
 * Application handler mechanism architecture.
 *
 * @package     Application
 * @subpackage  Core
 * @author      Taihao Zhang <jason@zth.me>
 * @license     GNU General Public License v3.0
 */

/**
 * Generic application handler.
 */
class Handler {
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
