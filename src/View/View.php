<?php declare(strict_types=1);
/**
 * src/View/View.php
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

namespace PhpRestMVC\View;

use PhpRestMVC\Env;
use PhpRestMVC\Router;
use PhpRestMVC\Utilities\Message;
use PhpRestMVC\Utilities\Tools;

/**
 * Class View
 * Global view class
 */
class View {

  /**
   * Method getHelp
   * Get help content for requested object
   *
   * @return String, the object help file content if exists, otherwise empty string
   */
  private static function getHelp() {

    // Initialize for help file content
    $env = Env::getInstance();
    $router = Router::getInstance();

    $file = ROOT.'src/View/help/'.strtolower(Router::getInstance()::getObject()).'.php';

    if(file_exists($file)) {

      ob_start();
      include($file);
      return ob_get_clean();
    }

    return '';
  }

  /**
   * Method show
   * Generate request response
   *
   * @return String, the JSON stringify response
   */
  public static function show($data = []) {

    $response = [
      'request' => Router::getInstance()::getAll(),
      'result' => [
        'status'   => Message::getInstance()::status(),
        'count'    => is_array($data) ? count($data) : 0,
        'messages' => Message::getInstance()::get(),
      ],
      'data' => $data,
    ];

    $help = self::getHelp();

    if($help != '') {

      $response['help'] = $help;
    }

    if(Env::getInstance()::getConfig('log')) {

      $response['env'] = Env::getInstance()::getAll();
      $response['readme'] = Tools::getLocalFile(ROOT.'README.md');
      $response['license'] = Tools::getLocalFile(ROOT.'LICENSE');

      \PhpRestMVC\Utilities\Log::add('Executed in '.round(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 5).' second(s)', 'info');
      $response['log'] = \PhpRestMVC\Utilities\Log::get();
    }

    return json_encode($response);
  }
}
