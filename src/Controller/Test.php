<?php declare(strict_types=1);
/**
 * src/Controller/Test.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC\Controller;

use PhpRestMVC\Utilities\Tools;
use PhpRestMVC\Utilities\Message;
use PhpRestMVC\Controller\Controller;
use PhpRestMVC\Model\Test as ModelTest;

/**
 * Class Test
 * Test controller
 */
class Test extends Controller {

  private static $validFields = [
    'test_name',
    'test_description',
    'test_value',
    'test_price',
    'test_active',
  ];

  /**
   * Method list
   * Get Test list
   *
   * @return String, the Test list
   */
  public function list() {

    $test = ModelTest::getTest();

    return $this->show($test);
  }

  /**
   * Method list
   * Get Test row
   *
   * @return String, the Test row
   */
  public function index() {

    $test = ModelTest::getTestById(self::$_router::getId());

    return $this->show($test);
  }

  /**
   * Method post
   * Create new test row
   *
   * @return String, the creation result
   */
  public function post() {

    $test = ModelTest::createTest($this->getFields(self::$validFields));

    if($test > 0) {

      Message::getInstance()::add('A new test have been created with identifier #'.$test.'.', MessageLevel::Info);

    } else {

      Message::getInstance()::add('Error, test have not been created.', MessageLevel::Error);
    }

    return $this->show($test);
  }

  /**
   * Method put
   * Update existing test row
   *
   * @return String, the update result
   */
  public function put() {

    $test = ModelTest::updateTest(self::$_router::getId(), $this->getFields(self::$validFields));

    if($test !== false) {

      Message::getInstance()::add('Test updated, '.$test.' row(s) impacted.', MessageLevel::Info);

    } else {

      Message::getInstance()::add('Error, Test have not been updated.', MessageLevel::Error);
    }

    return $this->show($test);
  }

  /**
   * Method delete
   * Delete existing test row
   *
   * @return String, the delete result
   */
  public function delete() {

    $test = ModelTest::deleteTest(self::$_router::getId());

    if($test) {

      Message::getInstance()::add('Test deleted, '.$test.' row(s) impacted.', MessageLevel::Info);

    } else {

      Message::getInstance()::add('Error, Test have not been deleted.', MessageLevel::Error);
    }

    return $this->show($test);
  }
}
