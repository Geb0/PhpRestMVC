<?php
/**
 * request.php
 *
 * Execute Request
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

require('init.php');

header('Content-Type: application/json');

\PhpRestMVC\Utilities\Log::add('Start request.php', 'info');

echo \PhpRestMVC\Router::getInstance()::show();
