<?php declare(strict_types=1);
/*
 * src/Model/Model.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC\Model;

use PhpRestMVC\Env;
use PhpRestMVC\Router;
use PhpRestMVC\Utilities\Log;
use PhpRestMVC\Utilities\Tools;
use PhpRestMVC\Utilities\Message;

/**
 * Class Model
 * Main model class for MySQL using PDO
 */
class Model {

  /**
   * Property Model $_instance
   * Self instance for singleton management
   */
  protected static $_instance = null;

  /**
   * Property PDO $_pdo, PDO database object
   */
  private static $_pdo = null;

  /**
   * Method getInstance
   * Get current or new instance of object
   *
   * @return Model, instance of Model object
   */
  public static function getInstance() {

    if(self::$_instance === null) {

      self::$_instance = new self();
    }

    return self::$_instance;
  }

  /**
   * Method __construct
   * Class initialisation, Load and configure PDO engine
   */
  public function __construct() {

    $params = Tools::getJsonFromLocalFile(ROOT.'src/config/database.json');

    try {

      $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING,
        \PDO::ATTR_EMULATE_PREPARES => false,
      ];

      self::$_pdo = new \PDO(
        'mysql:host='.$params['host'].';port='.$params['port'].';dbname='.$params['dbname'],
        $params['user'],
        $params['password'],
        $options
      );

      // Configure database connection (encoding, errormode)
      self::$_pdo->exec("set names ".$params['encoding']);
      self::$_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);

    } catch (\PDOException $e) {

      // Connection error
      Message::getInstance()::add('Database connection PDO', 'critical');

    } catch (\Exception $e) {

      // Other error
      Message::getInstance()::add('Database connection', 'critical');
    }
  }

  /**
   * Method getUserById
   * Get user information with its identifier
   *
   * @param Integer $id, The user identifier
   *
   * @return Array, the user information
   */
  public static function getUserById($id) {

    if($id !== 0) {

      $query = Model::getInstance()::query(
        'SELECT * from user WHERE usr_id=? LIMIT 0,1',
        [$id]
      );

      if(gettype($query) == 'array' && count($query) == 1) {

        return $query[0];
      }
    }

    return [];
  }

  /**
  * query
  * Execute SQL query
  *
  * Example:
  * ('SELECT * FROM table WHERE price>? and price<?', [$min, $max])
  *
  * @param String $query, SQL query with references to bind with params
  * @param Array $bindParams, List of params to bind with SQL query
  *
  * @return null|bool|int|array :
  *     null for error
  *     true or false for UPDATE, DELETE, TRUNCATE
  *     insert_id value or false for INSERT
  *     result fetchAll(FETCH_ASSOC) for other (SELECT, SHOW, DESCRIBE...) or empty array
  */
  public static function query($query, $bindParams = []) {

    $out = false;

    // Add query and parameters to SQL logs
    self::log($query, $bindParams);

    // Execute query
    if(self::$_pdo !== null) {

      try {

        $stmt = self::$_pdo->prepare($query);
        $stmt->execute($bindParams);

        switch(strtoupper(Tools::firstWord($query))) {

          case 'INSERT':

            // For INSERT, return new row identifier
            $out = self::$_pdo->lastInsertId();
            break;

          case 'UPDATE':
          case 'DELETE':

            // For UPDATE or DELETE, return number of lines impacted
            $out = $stmt->rowCount();
            break;

          case 'TRUNCATE':

            // For TRUNCATE returns true
            $out = true;

          default:

            // For others like SELECT, SHOW, DESCRIBE... returns rows
            $out = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

      } catch (\PDOException $e) {

        if(Env::getInstance()::getConfig('log')) self::log('Database connection error');

        Message::getInstance()::add('Query PDO error', 'error');

      } catch (\Exception $e) {

        if(Env::getInstance()::getConfig('log')) self::log('General database error');

        Message::getInstance()::add('Query error', 'error');
      }
    }

    return $out;
  }

  /*
   * log
   * Add SQL query to SQL logs
   *
   * @param String $query, SQL query to log
   * @param Array $bindParams, Parameters of SQL query
   */
  private static function log($query, $bindParams = []) {

    $filename = ROOT.'session/log/sql/'.date('Y-m-d').'.log';
    $fp = fopen($filename, 'a');
    fwrite($fp, date('Y-m-d H:i:s.u')." - ".$query."\n");

    if($bindParams !== []) {

      fwrite($fp, print_r($bindParams, true)."\n");
    }

    fwrite($fp, "\n");
    fclose($fp);
  }
}
