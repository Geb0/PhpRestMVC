<?php declare(strict_types=1);
/**
 * init.php
 *
 * Initialize session, constants and Composer autoload
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

define('ROOT', __DIR__.'/');

require('./vendor/autoload.php');

\PhpRestMVC\Env::getInstance();
