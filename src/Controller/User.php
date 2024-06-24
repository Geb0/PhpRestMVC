<?php declare(strict_types=1);
/**
 * src/Controller/User.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC\Controller;

use PhpRestMVC\Utilities\Tools;
use PhpRestMVC\Utilities\MessageLevel;
use PhpRestMVC\Utilities\Message;
use PhpRestMVC\Controller\Controller;
use PhpRestMVC\Model\User as ModelUser;

/**
 * Class User
 * User controller
 */
class User extends Controller {

  private static $validFields = [
    'usr_firstname',
    'usr_lastname',
    'usr_description',
    'usr_active',
  ];

  /**
   * Method list
   * Call index method to manage only current user
   * Always only show current user, never get user list
   *
   * @return String, the user row
   */
  public function list() {

    return $this->index();
  }

  /**
   * Method index
   * Get current user row
   *
   * @return String, the user row
   */
  public function index() {

    $user = ModelUser::getUserById(self::$_router::getUser());

    return $this->show($user);
  }

  /**
   * Method post
   * Create new test row
   * Usable only by administrator users
   *
   * @return String, the creation result
   */
  public function post() {

    $user = ModelUser::createUser($this->getFields(self::$validFields));

    if($user > 0) {

      Message::getInstance()::add('A new user have been created with identifier #'.$user.'.', MessageLevel::Info);

    } else {

      Message::getInstance()::add('Error, user have not been created.', MessageLevel::Error);
    }

    return $this->show($user);
  }

  /**
   * Method put
   * Update existing user row
   * Usable only by administrator users
   *
   * @return String, the update result
   */
  public function put() {

    $user = ModelUser::updateUser(self::$_router::getUser(), $this->getFields(self::$validFields));

    if($user !== false) {

      Message::getInstance()::add('User updated, '.$user.' row(s) impacted.', MessageLevel::Info);

    } else {

      Message::getInstance()::add('Error, User have not been updated.', MessageLevel::Error);
    }

    return $this->show($user);
  }

  /**
   * Method delete
   * Delete existing user row
   * Usable only by administrator users
   *
   * @return String, the delete result
   */
  public function delete() {

    $user = ModelUser::deleteTest(self::$_router::getId());

    if($user) {

      Message::getInstance()::add('User deleted, '.$user.' row(s) impacted.', MessageLevel::Info);

    } else {

      Message::getInstance()::add('Error, User have not been deleted.', MessageLevel::Error);
    }

    return $this->show($user);
  }
}
