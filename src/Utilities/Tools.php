<?php declare(strict_types=1);
/**
 * src/Utilities/Tools.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC\Utilities;

use PhpRestMVC\Env;

/**
 * Class Tools
 * Toolbox for PhpRestMVC
 */
class Tools {

  /**
   * Method sanitize
   * Clean value
   *
   * @param Mixed $value, the value to sanitize
   * @param String $type, the value type, can be id, int, float, email, url
   *
   * @return Mixed, the sanitized value
   */
  public static function sanitize($value, $type = '') {

    if($type === 'id') {

      return abs(intval(filter_var($value, FILTER_SANITIZE_NUMBER_INT)));

    } elseif($type === 'int') {

      return intval(filter_var($value, FILTER_SANITIZE_NUMBER_INT));

    } elseif($type === 'float' || $type === 'double') {

      return floatval(filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));

    } elseif($type === 'email') {

      return filter_var($value, FILTER_SANITIZE_EMAIL);

    } elseif($type === 'url') {

      return filter_var($value, FILTER_SANITIZE_URL);

    } else {

      if(gettype($value) == 'array' || gettype($value) == 'object') {

        return '';

      } else {

        return htmlspecialchars_decode(htmlspecialchars($value, ENT_NOQUOTES | ENT_SUBSTITUTE));
      }
    }
  }

  /**
   * Method firstWord
   * Get first word of string
   *
   * @param String $string, the string to manage
   *
   * @return String, the first word of string or empty string
   */
  public static function firstWord($string) {

    $words = explode(' ', trim($string));
    return count($words) > 0 ? $words[0] : '';
  }

  /**
   * Method setRandomString
   * Generate random string from letters or numbers at least
   *
   * @param Integer $length, the string length to generate
   * @param String $added, the additional chars to use to generate string
   *
   * @return String, the generated value
   */
  public static function setRandomString($length = 10, $specific = '') {

    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $out = '';

    for($i=0; $i < $length; $i++) {

      $out .= substr($str.$specific, rand(0, strlen($str.$specific) - 1), 1);
    }

    return $out;
  }

  /**
   * getJsonFromLocalFile
   * Load JSON object from local file
   *
   * @param String $filename, Path and file name to read
   *
   * @return Array, JSON definition, otherwise empty array
   */
  public static function getJsonFromLocalFile($filePathAndName) {

    if(file_exists($filePathAndName)) {

      return json_decode(file_get_contents($filePathAndName), true);

    } else {

      return [];
    }
  }

  /**
   * getLocalFile
   * Load local file
   *
   * @param String $filename, Path and file name to read
   *
   * @return String, the file content or empty string if file does not exists
   */
  public static function getLocalFile($filePathAndName) {

    if(file_exists($filePathAndName)) {

      return file_get_contents($filePathAndName);

    } else {

      return '';
    }
  }

  /**
  * Method objectExists
  * Test if object have controller
  *
  * @param String $object, the object name
  *
  * @return Bool, true if object controller exists, otherwise false
  */
  public static function objectExists($object) {

    return file_exists(ROOT.'src/Controller/'.ucfirst(strtolower($object)).'.php');
  }

  /**
   * loadControllerObject
   * Load application object
   *
   * @param String $object, the object name
   *
   * @return Object, the object loaded or null if not exists
   */
  public static function loadControllerObject($object) {

    $object = ucfirst(strtolower($object));

    if(self::objectExists($object)) {

      $className = '\\'.Env::getName().'\\Controller\\'.$object;

      return new $className();
    }

    return null;
  }

  /**
   * getClassName
   * Get the name of a class excluding its path
   *
   * @param Object $class, the class to get the name
   *
   * @return String, the class name
   */
  public static function getClassName($class) {

    $object = explode('\\', get_class($class));

    return end($object);
  }

  /**
  * Method getValidObject
  * Set a valid object name
  *
  * @param String $object, the object name
  *
  * @return String, a valid object name
  */
  public static function getValidObject($object = '') {

    if($object != '' && !self::objectExists($object)) {

      $object = '';
    }

    return $object;
  }
}
