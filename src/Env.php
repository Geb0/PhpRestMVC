<?php declare(strict_types=1);
/**
 * src/Env.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC;

use PhpRestMVC\Utilities\Tools;

/**
 * Class Env
 * Application environment constants
 */
class Env {

  /**
   * Property Env $_instance
   * Self instance for singleton management
   */
  private static $_instance = null;

  /**
   * Property String $name
   * Application name
   */
  private static $name = null;

  /**
   * Property String $baseUrl
   * HTTP root folder
   */
  private static $baseUrl = null;

  /**
   * Property Array $config
   * Application constants
   * Loaded from src/config/config.json
   */
  private static $config = null;

  /**
   * Property Array $composer
   * Application Composer constants
   * Loaded from /composer.json
   */
  private static $composer = null;

  /**
   * Method getInstance()
   * Singleton instance management
   *
   * @return Env, the new or existing self instance
   */
  public static function getInstance() {

    if(self::$_instance === null) {

      self::$_instance = new self();
    }

    return self::$_instance;
  }

  /**
   * Method __construct()
   * Initialization
   */
  public function __construct() {

    self::setBaseUrl();
    self::setName();
    self::setConfig();
    self::setComposer();
  }


  // SETers


  /**
   * Method setBaseUrl
   * Set application base URL from server constants
   */
  private static function setBaseUrl() {

    self::$baseUrl = './';

    if(isset($_SERVER['REQUEST_SCHEME'])) {

      $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_ADDR'].$_SERVER['SCRIPT_NAME'];
      $url = explode('/', $url);
      array_pop($url);
      self::$baseUrl = implode('/', $url).'/';
    }
  }

  /**
   * Method setName
   * Set application name from namespace
   */
  private static function setName() {

    self::$name = __NAMESPACE__;
  }

  /**
   * Method setConfig
   * Set environment config with /src/config/config.json file content
   */
  private static function setConfig() {

    self::$config = Tools::getJsonFromLocalFile(ROOT.'src/config/config.json');

    define('LOG', self::getConfig('log'));
  }

  /**
   * Method setComposer
   * Set environment composer config with /composer.json file content
   */
  private static function setComposer() {

    self::$composer = Tools::getJsonFromLocalFile(ROOT.'composer.json');
  }


  // GETers

  /**
   * Method getBaseUrl
   * Get application base URL value
   */
  public static function getBaseUrl() {

    return self::$baseUrl;
  }

  /**
   * Method getName
   * Get application name
   */
  public static function getName() {

    return self::$name;
  }

  /**
   * Method getConfig
   * Get a config value if exists or all config values
   *
   * @param String $key, the config key, optional
   *
   * @return String|Array, the config key value or all config keys
   */
  public static function getConfig($key = '') {

    return $key !== '' && isset(self::$config[$key])
           ? self::$config[$key]
           : self::$config;
  }

  /**
   * Method getComposer
   * Get a composer config subtree if exists or all composer config tree
   *
   * @param String $key, the subtree composer config key, optional
   *
   * @return String|Array, the key value or key subtree
   */
  public static function getComposer($key = '') {

    return $key !== '' && isset(self::$composer[$key])
           ? self::$composer[$key]
           : self::$composer;
  }

  /**
   * Method getAll
   * Get global environment config
   *
   * @return Array, the global environment config tree
   */
  public static function getAll() {

    return [
      'name'     => self::getName(),
      'root'     => ROOT,
      'baseurl'  => self::getBaseUrl(),
      'log'      => self::getConfig('log'),
      'composer' => self::getComposer(),
      'config'   => self::getConfig(),
    ];
  }
}
