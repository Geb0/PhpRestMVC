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
   * Method getUserById
   * Get user information with its identifier
   *
   * @param Integer $id, The user identifier
   *
   * @return Array, the user information
   */
  public static function getUserById($id) {

    $query = Model::getInstance()::getUserById($id);

    if(gettype($query) == 'array') {

      return $query;
    }

    Message::getInstance()::add('Error, user '.$id.' not found.', MessageLevel::Error);
    return [];
  }

  /**
   * Method updateUser
   * Update user in database
   *
   * @param String $id, The user identifier
   * @param Array $fields, The user fields to update
   *
   * @return Boolean, true if user is updated, otherwise false
   */
  public static function updateUser($id, $fields) {

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
    $query = Model::query($sql, $binds);

    return $query;
  }
}
