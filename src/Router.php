<?php declare(strict_types=1);
/**
 * src/Router.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC;

use PhpRestMVC\Env;
use PhpRestMVC\Model\Model;
use PhpRestMVC\Utilities\Tools;
use PhpRestMVC\Utilities\Message;
use PhpRestMVC\View\View;
use PhpRestMVC\Utilities\Log;

/**
 * Class Router
 * PhpRestMVC router
 */
class Router {

  /**
   * Property Router $_instance
   * Self instance for singleton management
   */
  private static $_instance = null;

  /**
   * Property Integer $user
   * User identifier
   */
  private static $user = null;

  /**
   * Property String $sessionKey
   * User identification API key
   */
  private static $sessionKey = null;

  /**
   * Property String $method
   * Router method requested
   */
  private static $method = null;

  /**
   * Property String $object
   * Current Object
   */
  private static $object = null;

  /**
   * Property Integer $id
   * Current Object identifier
   */
  private static $id = null;

  /**
   * Property Integer $offset
   * List offset value
   */
  private static $offset = null;

  /**
   * Property Array $data
   * Current Object data for writing
   */
  private static $data = null;

  /**
   * Property Array $validMethods
   * List of valid methods to execute
   * Nota: First method is default method when invalid method is sent
   */
  private static $validMethods = ['get', 'post', 'put', 'delete', 'auth'];

  /**
   * Method getInstance()
   * Singleton instance management
   *
   * @return Router, the new or existing self instance
   */
  public static function getInstance() {

    if(self::$_instance === null) {

      self::$_instance = new self();
    }

    return self::$_instance;
  }

  /**
   * Method __construct()
   * Router initialization
   */
  public function __construct() {

    if(Env::getInstance()::getConfig('log')) {

      ini_set('display_errors', 1);
      error_reporting(E_ALL);

    } else {

      ini_set('display_errors', 0);
      error_reporting(0);
    }

    self::setUser();
    self::setSessionKey();
    self::setMethod();
    self::setObject();
    self::setId();
    self::setData();
    self::setOffset();
  }

  /**
   * Method getArgument
   * Get argment value from POST or GET method
   * GET method is used only if POST method is not used
   *
   * @param String $key, the argument key
   * @param String $type, the argument type (string, int, float, default string)
   *
   * @return Mixed, the typed argument key value
   */
  private static function getArgument($key, $type = 'string') {

    $arg = '';

    if(isset($_POST[$key])) {

      $arg = $_POST[$key];

    } elseif(isset($_GET[$key])) {

      $arg = $_GET[$key];
    }

    switch($type) {

      case 'int':

        return intval(trim($arg));

      case 'float':

        return floatval(trim($arg));

      case 'string':
      default:

        return trim($arg);
    }
  }


  // SETers


  /**
   * Method setUser
   * Set request user from 'u' argument
   */
  private static function setUser() {

    self::$user = abs(self::getArgument('u', 'int'));
  }

  /**
   * Method setSessionKey
   * Set request API key from 'k' argument
   */
  private static function setSessionKey() {

    self::$sessionKey = self::getArgument('k');
  }

  /**
   * Method setMethod
   * Set request API key from 'm' argument
   */
  private static function setMethod() {

    self::$method = strtolower(self::getArgument('m'));

    // If not valid method is sent, use first valid method
    if(!in_array(self::$method, self::$validMethods)) {

      self::$method = self::$validMethods[0];
    }
  }

  /**
   * Method setObject
   * Set request object from 'o' argument
   */
  private static function setObject() {

    self::$object = Tools::getValidObject(self::getArgument('o'));
  }

  /**
   * Method setId
   * Set request object identifier from 'i' argument
   */
  private static function setId() {

    self::$id = abs(self::getArgument('i', 'int'));
  }

  /**
   * Method setOffset
   * Set request object list offset from 'l' argument
   */
  private static function setOffset() {

    self::$offset = abs(self::getArgument('l', 'int'));
  }

  /**
   * Method setData
   * Set object data list offset from POSTed arguments
   */
  private static function setData() {

    // Exclude global arguments (for user, API key,object...)
    $exclude = ['u', 'k', 'm', 'o', 'i', 'l', 'p'];

    self::$data = [];

    foreach($_POST as $key => $val) {

      if(!in_array($key, $exclude)) {

        self::$data[$key] = $val;
      }
    }
  }

  // GETers

  /**
   * Method getUser
   * Get request user value
   *
   * @return String, the user value
   */
  public static function getUser() {

    return self::$user;
  }

  /**
   * Method getSessionKey
   * Get request API key value
   *
   * @return String, the API key value
   */
  public static function getSessionKey() {

    return self::$sessionKey;
  }

  /**
   * Method getMethod
   * Get request method value
   *
   * @return String, the method value
   */
  public static function getMethod() {

    return self::$method;
  }

  /**
   * Method getObject
   * Get request object value
   *
   * @return String, the object value
   */
  public static function getObject() {

    return self::$object;
  }

  /**
   * Method getId
   * Get request object identifier value
   *
   * @return Integer, the object identifier value
   */
  public static function getId() {

    return self::$id;
  }

  /**
   * Method getOffset
   * Get request object list offset value
   *
   * @return Integer, the list offset value
   */
  public static function getOffset() {

    return self::$offset;
  }

  /**
   * Method getAll
   * Get request parameters list
   *
   * @return Array, the router parameters list
   */
  public static function getAll() {

    return [
      'user'   => self::getUser(),
      'key'  => self::getSessionKey(),
      'method' => self::getMethod(),
      'object' => self::getObject(),
      'id'     => self::getId(),
      'offset' => self::getOffset(),
    ];
  }

  /**
   * Method userIsValid
   * Checks if the user is valid (exists, active and has a correct API key)
   *
   * @return Boolean, true is user is valid, otherwise false
   */
  private static function userIsValid() {

    $user = Model::getInstance()::getUserById(self::getUser());

    Log::add($user);
    Log::add(self::getSessionKey());

    // Verify user

    if(
      // User not found
      $user == []
      ||
      // User deactivated
      $user !== [] && $user['usr_active'] == 0
      ||
      // Wrong session key
      $user !== [] && $user['usr_key'] !== hash(Env::getInstance()::getConfig('passwordHash'), self::getSessionKey())
    ) {

      Message::getInstance()::add('Error, unauthorized access', 'error');

      return false;
    }

    // Verify session timeout

    if(microtime(true) - $user['usr_timestamp'] > Env::getInstance()::getConfig('secondsTimeout')) {

      Message::getInstance()::add('Error, session timeout', 'error');

      return false;
    }

    Model::getInstance()::setUserSessionTimestamp(self::getUser(), intval(microtime(true)));

    return true;
  }

  // Show

  /**
   * Method show
   * Call object action ot authenticate user
   *
   * @return String, the object action result if object is valid, otherwise empty string
   */
  public static function show() {

    $object = Tools::getValidObject(self::getObject());
    $action = self::getMethod();

    if($action == 'auth') {

      return self::authenticate();
    }

    if($object !== '') {

      $object = Tools::loadControllerObject($object);

      if(!self::userIsValid()) {

        return View::show();
      }

      if(method_exists($object, $action)) {

        return $object->$action();
      }
    }

    Message::getInstance()::add('Error, unknown object', 'error');

    return View::show();
  }

  /**
   * Method authenticate
   * Verify user authentication (id and password) and generate current user API key
   *
   * @return String, the current user API key if authenticatoin is ok, otherwise empty string
   */
  private static function authenticate() {

    $user = Model::getInstance()::getUserById(self::getUser());

    if(
      // User not found
      $user == []
      ||
      // User deactivated
      $user !== [] && $user['usr_active'] == 0
    ) {

      Message::getInstance()::add('Error, unknown or deactivated user', 'error');

      return View::show();
    }

    if($user['usr_password'] != hash(Env::getInstance()::getConfig('passwordHash'), self::getArgument('p'))) {

      Message::getInstance()::add('Connection error', 'error');

      return View::show();
    }

    $key = Tools::setRandomString(20);

    Model::getInstance()::setUserSession(self::getUser(), hash(Env::getInstance()::getConfig('passwordHash'), $key), intval(microtime(true)));

    return View::show(['key' => $key]);
  }
}
