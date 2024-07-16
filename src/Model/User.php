<?php declare(strict_types=1);
/*
 * src/Model/User.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC\Model;

use PhpRestMVC\Router;
use PhpRestMVC\Model\Model;
use PhpRestMVC\Utilities\MessageLevel;
use PhpRestMVC\Utilities\Message;

/**
 * Class User
 * Model class for User object
 */
class User {

  /**
   * Method getById
   * Get user information with its identifier
   *
   * @param Integer $id, The user identifier
   *
   * @return Array, the user information
   */
  public static function getById($id) {

    $query = Model::getInstance()::getUserById($id);

    if(gettype($query) == 'array') {

      return $query;
    }

    Message::getInstance()::add('Error, unknown user.', MessageLevel::Error);
    return [];
  }

  /**
   * Method update
   * Update user in database
   *
   * @param Integer $id, The user identifier
   * @param Array $fields, The user fields to update
   *
   * @return Boolean, true if user is updated, otherwise false
   */
  public static function update($id, $fields) {

    if($fields == []) return false;

    $names = [];
    $binds = [];

    foreach($fields as $name => $value) {

      $names[] = "$name=?";
      $binds[] = $value;
    }

    $sql = 'UPDATE user SET '
           .implode(',', $names)
           .' WHERE usr_id=?';

    $binds[] = $id;
    $query = Model::getInstance()::query($sql, $binds);

    return $query == 1;
  }
}
