<?php declare(strict_types=1);
/**
 * src/Utilities/Log.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC\Utilities;

use PhpRestMVC\Env;
use PhpRestMVC\Utilities\Show;
use PhpRestMVC\Utilities\Message;

/**
 * Class Log
 * Log system for PhpRestMVC
 */
class Log {

  /**
  * Property String $separator
  * Separator to use to separate line elements in file
  */
  private static $separator = '|-|';

  /**
  * Method add
  * Show system message
  *
  * @param Mixed $message, the message to show
  * @param String $type, the type of the message
  */
  public static function add($message, $type = '') {

    $type = Message::getValidType($type);

    if(Env::getInstance()::getConfig('log')) {

      $timestamp = microtime();
      $timestampParts = explode(" ", $timestamp);
      $now = date("Y-m-d H:i:s", intval($timestampParts[1]));
      $now .= intval($timestampParts[0] * 10000);
      // New file each minute
      $file = ROOT.'session/log/php/'.date("Y-m-d-H-m", intval($timestampParts[1])).'.log';
      $fp = fopen($file, 'a');
      fwrite($fp,
        $now
       .self::$separator
       .$type
       .self::$separator
       .Show::showValue($message)
       .PHP_EOL.self::$separator.PHP_EOL
      );
      fclose($fp);
    }
  }

  /**
  * Method getLastFileContent
  * Get last log file content
  *
  * @return String, the last file content in log folder or empty string if no file found
  */
  private static function getLastFileContent() {

    $files = array_diff(scandir(ROOT.'session/log/php/'), array('..', '.'));
    asort($files);
    $file = end($files);

    // No file found in folder
    if($file === false) {

      return '';
    }

    return file_get_contents(ROOT.'session/log/php/'.$file);
  }

  /**
  * Method get
  * Get list of logs
  *
  * @return Array, the logs list
  */
  public static function get() {

    $out = [];

    if(Env::getInstance()::getConfig('log')) {

      $lines = explode(PHP_EOL.self::$separator.PHP_EOL, self::getLastFileContent());

      foreach($lines as $line) {

        if($line !== '') {

          $elements = explode(self::$separator, $line);

          $out[] = [
            'timestamp' => $elements[0],
            'type' => $elements[1],
            'message' => $elements[2],
            'color' => Message::getTypeColor($elements[1]),
            'background' => Message::getTypeBackgroundColor($elements[1]),
          ];
        }
      }
    }

    return $out;
  }

  /**
  * Method show
  * Generate HTML text with logs
  *
  * @return String, the generated HTML text
  */
  public static function show() {

    $out = '';

    if(Env::getInstance()::getConfig('log')) {

      $out .= '<div class="log">';
      $lines = explode(PHP_EOL.self::$separator.PHP_EOL, self::getLastFileContent());

      foreach($lines as $line) {

        $elements = explode(self::$separator, $line);

        $out .= '<div class="log-line log-'.$elements[1].'">'
                 .'['.$elements[0].']'
                 .' '
                 .'['.$elements[1].']'
                 .' '
                 .'['.$elements[2].']'
               .'</div>'.PHP_EOL;
      }

      $out .= '</div>';
    }

    return $out;
  }
}
