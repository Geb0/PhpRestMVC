<?php declare(strict_types=1);
/**
 * src/Utilities/Log.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC\Utilities;

use PhpRestMVC\Utilities\Show;

/**
 * Class Message
 * Message system for PhpRestMVC
 */
class Message {

  /**
   * Property Message $_instance
   * Self instance for singleton management
   */
  private static $_instance = null;

  /**
   * Property Boolean $alert
   * Message alert flag, become true if some alert type message is added
   */
  private static $alert = null;

  /**
   * Property Array $messages
   * The list of added messages
   */
  private static $messages = null;

  /**
   * Array of values of valid messages
   * Row key is the PSR log level value
   * color is the Bootsrtap color value
   * background is the Bootsrtap background color value
   * alert is true if the message informs the application
   */
  private static $validTypes = [
    'emergency' => ['color' =>'warning',   'background' => 'danger',  'alert' => true],
    'alert'     => ['color' =>'light',     'background' => 'warning', 'alert' => true],
    'critical'  => ['color' =>'secondary', 'background' => 'info',    'alert' => true],
    'error'     => ['color' =>'danger',    'background' => 'dark',    'alert' => true],
    'warning'   => ['color' =>'warning',   'background' => 'dark',    'alert' => false],
    'notice'    => ['color' =>'success',   'background' => 'dark',    'alert' => false],
    'info'      => ['color' =>'info',      'background' => 'dark',    'alert' => false],
    'debug'     => ['color' =>'secondary', 'background' => 'dark',    'alert' => false],
  ];

  /**
   * Method getInstance()
   * Singleton instance management
   *
   * @return Message, new or current object instance
   */
  public static function getInstance() {

    if(self::$_instance === null) {

      self::$_instance = new self();
    }

    return self::$_instance;
  }

  /**
   * Method __construct()
   * Message initialization
   */
  public function __construct() {

    self::$alert = false;
    self::$messages = [];
  }

  /**
  * Method add
  * Add message
  *
  * @param Mixed $message, the message to add
  * @param String $type, the type of the message (default info)
  */
  public static function add($message, $type = 'debug') {

    $type = self::getValidType($type);

    if(self::$validTypes[$type]['alert']) {

      self::$alert = true;
    }

    self::$messages[] = [
      'type' => $type,
      'text' => Show::showValue($message),
      'color' => self::getTypeColor($type),
      'background' => self::getTypeBackgroundColor($type),
    ];
   }


  // GETers


  /**
   * getTypeColor
   * Get color of a message type
   *
   * @param String $type, the type name
   *
   * @return String, the color value
   */
  public static function getTypeColor($type) {

    return self::$validTypes[$type]['color'];
  }

  /**
   * getTypeBackgroundColor
   * Get background color of a message type
   *
   * @param String $type, the type name
   *
   * @return String, the background color value
   */
  public static function getTypeBackgroundColor($type) {

    return self::$validTypes[$type]['background'];
  }

  /**
   * getValidType
   * Get valid message type
   *
   * @param String $type, the type name
   *
   * @return String, the type name if valid, otherwide 'debug'
   */
  public static function getValidType($type) {

    if(!array_key_exists($type, self::$validTypes)) {

      $type = 'debug';
    }

    return $type;
  }

  /**
  * Method get
  * Get messages list
  *
  * @return Array, the messages list
  */
  public static function status() {

    return self::$alert ? 'error' : 'success';
  }

  /**
  * Method get
  * Get messages list
  *
  * @return Array, the messages list
  */
  public static function get() {

    return self::$messages;
  }
}
