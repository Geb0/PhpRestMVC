<?php declare(strict_types=1);
/**
 * tests/RouterTest.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC;

require('init.php');

use PhpRestMVC\Env;
use PhpRestMVC\Utilities\Tools;
use PHPUnit\Framework\TestCase;

/**
* Class RouterTest
* PHPUnit test methods for PhpRestMVC\Utilities\Tools
*/
class ToolsTest extends TestCase {

  public function testSanitizeIdIsValid() {

    $val = 10;
    $this->assertTrue(
      Tools::sanitize($val ,'id') === $val
    );
  }

  public function testSanitizeIdIsNotValid() {

    $val = -10;
    $this->assertTrue(
      Tools::sanitize($val ,'id') !== $val
    );
  }

  public function testSanitizeIntIsValid() {

    $val = 10;
    $this->assertTrue(
      Tools::sanitize($val ,'int') === $val
    );
  }

  public function testSanitizeIntIsNotValid() {

    $val = 'test';
    $this->assertTrue(
      Tools::sanitize($val ,'int') !== $val
    );
  }

  public function testSanitizeFloatIsValid() {

    $val = 10.5;
    $this->assertTrue(
      Tools::sanitize($val ,'float') === $val
    );
  }

  public function testSanitizeFloatIsNotValid() {

    $val = 'test';
    $this->assertTrue(
      Tools::sanitize($val ,'float') !== $val
    );
  }

  public function testSanitizeEmailIsValid() {

    $val = 'a.b@c.d';
    $this->assertTrue(
      Tools::sanitize($val ,'email') === $val
    );
  }

  public function testSanitizeEmailIsNotValid() {

    $val = 'a b@c.d';

    $this->assertTrue(
      Tools::sanitize($val ,'email') !== $val
    );
  }

  public function testSanitizeUrlIsValid() {

    $val = 'http://a.b/c/d.e';
    $this->assertTrue(
      Tools::sanitize($val ,'url') === $val
    );
  }

  public function testSanitizeUrlIsNotValid() {

    $val = 'a / c / d . e';
    $this->assertTrue(
      Tools::sanitize($val ,'url') !== $val
    );
  }

  public function testSanitizeOtherIsValid() {

    $val = 'test';
    $this->assertTrue(
      Tools::sanitize($val ,'') === $val
    );
  }

  public function testSanitizeOtherIsNotValid() {

    $val = [1,2];
    $this->assertTrue(
      Tools::sanitize($val ,'') === ''
    );
  }

  public function testFirstWordIsValid() {

    $val = 'Hello world';
    $this->assertTrue(
      Tools::firstWord($val) === 'Hello'
    );
  }

  public function testSetRandomStringIsValid() {

    $val = Tools::setRandomString(10);
    $this->assertTrue(
       strlen($val) == 10
    );
  }

  public function testGetJsonFromLocalFileIsValid() {

    $val = Tools::getJsonFromLocalFile(ROOT.'tests/config/test.json');
    $this->assertTrue(
      $val['a'] === true
      &&
      $val['b'] === 10
      &&
      $val['c'] === 10.5
      &&
      $val['d'] === 'string'
    );
  }

  public function testLocalFileIsValid() {

    $val = Tools::getLocalFile(ROOT.'index.php');
    $this->assertTrue(
      $val != ''
    );
  }

  public function testLocalFileIsNotValid() {

    $val = Tools::getLocalFile(ROOT.'unknown.file');
    $this->assertTrue(
      $val == ''
    );
  }

  public function testObjectExistsIsValid() {

    $val = Tools::objectExists('test');
    $this->assertTrue(
      $val
    );
  }

  public function testObjectExistsIsNotValid() {

    $val = Tools::objectExists('unknownObject');
    $this->assertTrue(
      !$val
    );
  }

  public function testLoadControllerObjectIsValid() {

    $val = Tools::loadControllerObject('test');
    $this->assertTrue(
      gettype($val) === 'object'
    );
  }

  public function testLoadControllerObjectIsNotValid() {

    $val = Tools::loadControllerObject('unknownObject');
    $this->assertTrue(
      gettype($val) !== 'object'
    );
  }

  public function testGetClassNameIsValid() {

    $this->assertTrue(
      Tools::getClassName($this) === 'ToolsTest'
    );
  }
}
