<?php declare(strict_types=1);
/*
 * src/Model/Test.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC\Model;

use PhpRestMVC\Env;
use PhpRestMVC\Router;
use PhpRestMVC\Model\Model;
use PhpRestMVC\Utilities\MessageLevel;
use PhpRestMVC\Utilities\Message;

/**
 * Class Test
 * Model class for Test object
 */
class Test {

  /**
   * Method get
   * Get test row with its identifier
   *
   * @param Integer $id, The test identifier
   *
   * @return Array, the test information row
   */
  public static function get() {

    $query = Model::getInstance()::query(
      'SELECT * from test LIMIT '
        .Router::getInstance()::getOffset()
        .','
        .Env::getInstance()::getConfig('rowCount')
      ,[]
    );

    if(gettype($query) == 'array') {

      return $query;
    }

    Message::getInstance()::add('Error, tests not found.', MessageLevel::Error);
    return [];
  }

  /**
   * Method getById
   * Get test row with its identifier
   *
   * @param Integer $id, The test identifier
   *
   * @return Array, the test information row
   */
  public static function getById($id) {

    $query = Model::getInstance()::query(
      'SELECT * from test WHERE test_id=? LIMIT 0,1',
      [$id]
    );

    if(gettype($query) == 'array' && count($query) == 1) {

      return $query[0];
    }

    Message::getInstance()::add('Error, test '.$id.' not found.', MessageLevel::Error);
    return [];
  }

  /**
   * Method create
   * Add new test in database
   *
   * @param Array $fields, The test fields to use for creation
   *
   * @return Integer, the created test identifier or 0 if error
   */
  public static function create($fields) {

    if($fields == []) return 0;

    $names = [];
    $values = [];
    $binds = [];

    foreach($fields as $name => $value) {

      $names[] = $name;
      $values[] = '?';
      $binds[]= $value;
    }

    $sql = 'INSERT INTO test ('
           .implode(',', $names)
           .') VALUES ('
           .implode(',', $values)
           .')';

    $query = Model::getInstance()::query($sql, $binds);

    return intval($query);
  }

  /**
   * Method update
   * Update specific test in database
   *
   * @param String $id, The test identifier
   * @param Array $fields, The test fields to update
   *
   * @return Boolean, true if test is updated, otherwise false
   */
  public static function update($id, $fields) {

    if($fields == []) return false;

    $names = [];
    $binds = [];

    foreach($fields as $name => $value) {

      $names[] = "$name=?";
      $binds[] = $value;
    }

    $sql = 'UPDATE test SET '
           .implode(',', $names)
           .' WHERE test_id=?';

    $binds[] = $id;
    $query = Model::getInstance()::query($sql, $binds);

    return $query == 1;
  }

  /**
   * Method delete
   * Delete specific test in database
   *
   * @param String $id, The test identifier
   *
   * @return Boolean, true if test is deleted, otherwise false
   */
  public static function delete($id) {

    $sql = 'DELETE FROM test WHERE test_id=?';
    $binds[] = $id;
    $query = Model::getInstance()::query($sql, $binds);

    return $query == 1;
  }
}
