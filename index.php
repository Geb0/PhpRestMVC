<?php
/**
 * index.php
 *
 * Application Requester
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

require('init.php');

$env = \PhpRestMVC\Env::getInstance();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />


  <title>PhpRestMVC - sendRequest</title>


  <meta name="language" content="English" />
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1" />

  <meta name="description" content="PhpRestMVC is a PHP Rest MVC base example" />
  <meta name="keywords" LANG="fr" content="PhpRestMVC,php,rest,mvc,base,basic,example,authentication,phpunit" />
  <meta name="author" content="Philippe Croué" />
  <meta name="copyright" content="Copyright 2024 - Philippe Croué - All rights reserved under MIT license." />

  <link rel="shortcut icon" type="image/svg" href="<?= $env::getBaseUrl() ?>/img/logo.svg">

  <script src="<?= $env::getBaseUrl() ?>/js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
  <script>var baseurl = "<?= $env::getBaseUrl() ?>";</script>
  <script src="<?= $env::getBaseUrl() ?>/js/scripts.js"></script>

  <link rel="stylesheet" href="./css/bootstrap@5.3.3/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="./css/styles.css">
</head>

<body>
  <div class="container main mx-auto my-0">
    <div class="row">
      <div class="col main-content text-start m-0 p-2 pb-0">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch">
          <div class="align-self-start align-self-md-center pe-0 pe-md-1 pb-1 pb-md-0 logoSpace">
            <img class="logo" src="./img/logo.svg" onclick="resetArguments()" title="Reset arguments" />
          </div>
          <div class="align-self-start align-self-md-center w-100 pe-0 pe-md-1 pb-1 pb-md-0">
            <input class="form-control fs-small" type="text" id="u" name="u" placeholder="Authentication user Identifier" />
          </div>
          <div class="align-self-start align-self-md-center w-100 pe-0 pe-md-1 pb-1 pb-md-0">
            <input class="form-control fs-small" type="text" id="p" name="p" placeholder="Authentication password" />
          </div>
          <div class="align-self-start align-self-md-center pe-0 pe-md-1 pb-1 pb-md-0">
            <button class="btn btn-primary px-2 py-1" style="font-size: 75%;" onclick="authenticate()">Authenticate</button>
          </div>
          <div class="align-self-start align-self-md-center w-100">
            <input class="form-control fs-small" type="text" id="k" name="k" placeholder="User current session key" readonly="readonly" />
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col main-content text-start m-0 p-2 pt-1">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch">
          <div class="align-self-start align-self-md-center pe-0 pe-md-1 pb-1 pb-md-0 logoSpace"></div>
          <div class="align-self-start align-self-md-center w-100 pe-0 pe-md-1 pb-1 pb-md-0">
            <select class="form-select fs-small" id="m" name="m" placeholder="Method">
              <option disabled>Select a method...</option>
              <option value="get">Get</option>
              <option value="post">Post</option>
              <option value="put">Put</option>
              <option value="delete">Delete</option>
            </select>
          </div>
          <div class="align-self-start align-self-md-center w-100 pe-0 pe-md-1 pb-1 pb-md-0">
            <select class="form-select fs-small" id="o" name="o" placeholder="Object">
              <option disabled>Select an object...</option>
              <option value="test">Test</option>
              <option value="user">User</option>
            </select>
          </div>
          <div class="align-self-start align-self-md-center w-100 pe-0 pe-md-1 pb-1 pb-md-0">
            <input class="form-control fs-small" type="text" id="i" name="i" placeholder="Object Id." />
          </div>
          <div class="align-self-start align-self-md-center w-100">
            <input class="form-control fs-small" type="text" id="l" name="l" placeholder="List offset" />
          </div>
        </div>
        <div class="row">
          <div class="col">
            <table class="table table-borderless table-sm bg-transparent table-responsive m-0">
              <tbody>
                <tr>
                  <td class="ps-0 pe-1 pt-1 pb-0"><input class="form-control fs-small" type="text" id="name[]" name="name[]" placeholder="Argument name" /></td>
                  <td class="px-0 pt-1 pb-0"><input class="form-control fs-small" type="text" id="value[]" name="value[]" placeholder="Argument value" /></td>
                </tr>
                <tr>
                  <td class="ps-0 pe-1 pt-1 pb-0"><input class="form-control fs-small" type="text" id="name[]" name="name[]" placeholder="Argument name" /></td>
                  <td class="px-0 pt-1 pb-0"><input class="form-control fs-small" type="text" id="value[]" name="value[]" placeholder="Argument value" /></td>
                </tr>
                <tr>
                  <td class="ps-0 pe-1 pt-1 pb-0"><input class="form-control fs-small" type="text" id="name[]" name="name[]" placeholder="Argument name" /></td>
                  <td class="px-0 pt-1 pb-0"><input class="form-control fs-small" type="text" id="value[]" name="value[]" placeholder="Argument value" /></td>
                </tr>
                <tr>
                  <td class="ps-0 pe-1 pt-1 pb-0"><input class="form-control fs-small" type="text" id="name[]" name="name[]" placeholder="Argument name" /></td>
                  <td class="px-0 pt-1 pb-0"><input class="form-control fs-small" type="text" id="value[]" name="value[]" placeholder="Argument value" /></td>
                </tr>
                <tr>
                  <td class="ps-0 pe-1 pt-1 pb-0"><input class="form-control fs-small" type="text" id="name[]" name="name[]" placeholder="Argument name" /></td>
                  <td class="px-0 pt-1 pb-0"><input class="form-control fs-small" type="text" id="value[]" name="value[]" placeholder="Argument value" /></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <button class="btn btn-primary w-100 mt-2" onclick="sendRequest()">Send Request</button>
          </div>
        </div>
        <div id="result" class="d-none row">
          <div class="col">
            <div class="d-none mt-2" id="messages"></div>
            <div class="d-none fs-small mt-2" id="datas"></div>
            <div class="d-none alert alert-info fs-small mt-2" id="help"></div>
            <pre class="d-none mt-2" id="response"></pre>
            <div class="d-none mt-2" id="log"></div>
            <div class="d-none mt-2" id="readme"></div>
            <div class="d-none mt-2" id="license"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
