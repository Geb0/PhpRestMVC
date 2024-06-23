<?php declare(strict_types=1);
/**
 * src/Utilities/Show.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC\Utilities;

use PhpRestMVC\Utilities\Tools;

/**
 * Class Show
 * PhpRestMVC show utilities
 */
class Show {

  /**
   * Method showUnknown
   * Show value for unknown type
   *
   * @param $value, the value to show as unknown
   *
   * @return String, 'UNKNOWN'
   */
  private static function showUnknown($value) {

    return 'UNKNOWN';
  }

  /**
   * Method showNull
   * Show value for null type
   *
   * @param $value, the value to show as null
   *
   * @return String, 'NULL'
   */
  private static function showNull($value) {

    return 'NULL';
  }

  /**
   * Method showString
   * Show value for string type
   *
   * @param $value, the value to show as string
   *
   * @return String, the String value
   */
  private static function showString($value) {

    return strval($value);
  }

  /**
   * Method showBoolean
   * Show value for boolean type
   *
   * @param $value, the value to show as boolean
   *
   * @return String, the stringified boolean integer value (0/1)
   */
  private static function showBoolean($value) {

    return strval(intval($value));
  }

  /**
   * Method showInteger
   * Show value for integer type
   *
   * @param $value, the value to show as integer
   *
   * @return String, the stringified integer value
   */
  private static function showInteger($value) {

    return self::showString(intval($value));
  }

  /**
   * Method showDouble
   * Show value for float/double type
   *
   * @param $value, the value to show as double
   *
   * @return String, the stringified double value
   */
  private static function showDouble($value) {

    return self::showString(doubleval($value));
  }

  /**
   * Method showObject
   * Show value for object type
   *
   * @param $value, the value to show as object
   *
   * @return String, the stringified object value
   */
  private static function showObject($value) {

    ob_start();
    var_dump($value);
    return ob_get_clean();
  }

  /**
   * Method showArray
   * Show value for array type
   *
   * @param $value, the value to show as array
   *
   * @return String, the stringified array value
   */
  private static function showArray($value) {

    return self::showObject($value);
  }

  /**
   * Method showResource
   * Show value for ressource type
   *
   * @param $value, the value to show as ressource
   *
   * @return String, the stringified ressource value
   */
  private static function showResource($value) {

    return self::showObject($value);
  }

  /**
   * Method showValue
   * Show a value
   *
   * @param Mixed $value, the value to show
   *
   * @return String, the stringified value
   */
  public static function showValue($value) {

    $method = 'show'.ucfirst(Tools::firstWord(strtolower(gettype($value))));

    return $message = self::$method($value);
  }
/*
  public static function arrayAsTable($array) {

    $out = '<table class="table table-hover"><tbody>'.PHP_EOL;

    foreach($array as $key => $val) {

      if(is_array($val)) {

        if($val === []) {

          $out .= '<tr><th class="col text-start" style="width: 20%;">'.$key.'</th><td class="col text-start">[Empty]</td></tr>'.PHP_EOL;

        } else {

          $out .= '<tr><th class="col text-start" style="width: 20%;">'.$key.'</th><td class="col text-start">'.self::arrayAsTable($val).'</td></tr>'.PHP_EOL;
        }

      } else {

        $out .= '<tr><th style="width: 20%;">'.$key.'</th><td>'.self::showValue($val).'</td></tr>'.PHP_EOL;
      }
    }
    $out .= '</tbody></table>'.PHP_EOL;

    return $out;
  }
*/
}
