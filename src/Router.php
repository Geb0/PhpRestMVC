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
   * Property String $apikey
   * User identification API key
   */
  private static $apikey = null;

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
   */
  private static $validMethods = ['get', 'post', 'put', 'delete'];

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

    if(LOG) {

      ini_set('display_errors', 1);
      error_reporting(E_ALL);

    } else {

      ini_set('display_errors', 0);
      error_reporting(0);
    }

    self::setUser();
    self::setApiKey();
    self::setMethod();
    self::setObject();
    self::setId();
    self::setData();
    self::setOffset();
  }


  // SETers


  /**
   * Method setUser
   * Set request user from 'u' argument
   */
  private static function setUser() {

    self::$user = 0;

    if(isset($_GET['u'])) {

      self::$user = abs(intval($_GET['u']));
    }

    if(isset($_POST['u'])) {

      self::$user = abs(intval($_POST['u']));
    }
  }

  /**
   * Method setApiKey
   * Set request API key from 'k' argument
   */
  private static function setApiKey() {

    self::$apikey = '';

    if(isset($_GET['k'])) {

      self::$apikey = trim(strval($_GET['k']));
    }

    if(isset($_POST['k'])) {

      self::$apikey = trim(strval($_POST['k']));
    }
  }

  /**
   * Method setMethod
   * Set request API key from 'm' argument
   */
  private static function setMethod() {

    if(isset($_GET['m'])) {

      self::$method = trim(strtolower($_GET['m']));
    }

    if(isset($_POST['m'])) {

      self::$method = trim(strtolower($_POST['m']));
    }

    if(!in_array(self::$method, self::$validMethods)) {

      self::$method = self::$validMethods[0];
    }
  }

  /**
   * Method setObject
   * Set request object from 'o' argument
   */
  private static function setObject() {

    self::$object = '';

    if(isset($_GET['o'])) {

      self::$object = Tools::getValidObject($_GET['o']);
    }

    if(isset($_POST['o'])) {

      self::$object = Tools::getValidObject($_POST['o']);
    }
  }

  /**
   * Method setId
   * Set request object identifier from 'i' argument
   */
  private static function setId() {

    self::$id = 0;

    if(isset($_GET['i'])) {

      self::$id = abs(intval($_GET['i']));
    }

    if(isset($_POST['i'])) {

      self::$id = abs(intval($_POST['i']));
    }
  }

  /**
   * Method setOffset
   * Set request object list offset from 'l' argument
   */
  private static function setOffset() {

    self::$offset = 0;

    if(isset($_GET['l'])) {

      self::$offset = abs(intval($_GET['l']));
    }

    if(isset($_POST['l'])) {

      self::$offset = abs(intval($_POST['l']));
    }
  }

  /**
   * Method setData
   * Set object data list offset from posted arguments
   */
  private static function setData() {

    // Exclude global arguments (for user, API key,object...)
    $exclude = ['u', 'k', 'm', 'o', 'i', 'l'];

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
   * Method getApiKey
   * Get request API key value
   *
   * @return String, the API key value
   */
  public static function getApiKey() {

    return self::$apikey;
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
      'key'  => self::getApiKey(),
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

    if(
      // User not found
      $user == []
      ||
      // User deactivated
      $user !== [] && $user['usr_active'] == 0
      ||
      // Wrong API key
      $user !== [] && $user['usr_key'] !== self::getApiKey()
    ) {

      Message::getInstance()::add('Error, unauthorized access', 'error');

      return false;
    }

    return true;
  }

  // Show

  /**
   * Method show
   * Call object action
   *
   * @return String, the object action result if object is valid, otherwise empty string
   */
  public static function show() {

    $object = Tools::getValidObject(self::getObject());

    if($object !== '') {

      $object = Tools::loadControllerObject($object);

      if(!self::userIsValid()) {

        return View::show();
      }

      $action = self::getMethod();

      if(method_exists($object, $action)) {

        return $object->$action();
      }
    }

    Message::getInstance()::add('Error, unknown object', 'error');
    return View::show();
  }
}
