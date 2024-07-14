/*
 * js/scripts.js
 *
 * Scripts for PhpRestMVC
 *
 * Copyright © 2024, Philippe Croué, all rights reserved under MIT license.
 */

"use strict";

/**
 * Function request
 * Send request to requester and send result to callback function
 *
 * @param Object data, the data list to send to requester
 * @param Function callback, the function to call by requester callback
 *
 */
function request(data, callback) {

  let formData = new FormData();

  for (let [key, value] of Object.entries(data)) {

    formData.append(key, value);
  }

  fetch(baseurl + 'request.php', {
    method: 'POST',
    mode: 'same-origin',
    cache: 'no-cache',
    credentials: 'same-origin',
    redirect: 'follow',
    referrerPolicy: 'no-referrer',
    body: formData
  })
  .then(function (response) { return response.text(); })
  .then(function (json) { callback(json); });
}

/**
 * Function sendRequest
 * Get request data from page then call request
 */
function sendRequest() {

  let data = {};
  let names = [];
  let values = [];

  data.u = document.getElementById('u').value;
  data.k = document.getElementById('k').value;
  data.m = document.getElementById('m').value;
  data.o = document.getElementById('o').value;
  data.i = document.getElementById('i').value;
  data.l = document.getElementById('l').value;

  document.getElementsByName('name[]').forEach(function(node) {

    names.push(node.value.trim());
  });

  document.getElementsByName('value[]').forEach(function(node) {

    values.push(node.value.trim());
  });

  for(i = 0; i < names.length; i++) {

    if(names[i] !== '') {

      data[names[i]] = values[i];
    }
  }

  resetResult();
  request(data, callbackRequester);
}

/**
 * Function authenticate
 * User authentication
 */
function authenticate() {

  document.getElementById('k').value = '';

  let data = {};

  data.m = 'auth';
  data.u = document.getElementById('u').value;
  data.p = document.getElementById('p').value;

  request(data, callbackAuthenticate);
}

/**
 * Function callbackAuthenticate
 * Callback function called by authenticate
 *
 * @param Object result, the requester response
 */
function callbackAuthenticate(response) {

  result = JSON.parse(response);
  document.getElementById('p').value = '';
  document.getElementById('k').value = result.data.key;
  console.log(result);
}

/**
 * Function callbackRequester
 * Callback function called by request, show result on page
 *
 * @param Object result, the requester response
 */
function callbackRequester(response) {

  result = JSON.parse(response);

  document.getElementById('response').innerHTML = JSON.stringify(result, null, 2);
  document.getElementById('response').classList.remove('d-none');

  if(
    typeof result.data !== 'undefined'
    &&
    Object.keys(result.data).length !== 0
    &&
    result.data !== ''
  ) {

    if(typeof result.data == 'object') {

      document.getElementById('datas').innerHTML = objectAsTable('Result datas', result.data);

    } else {

      document.getElementById('datas').innerHTML = '<h4>Result datas</h4>' + '<p>' + String(result.data) + '</p>';
    }

    document.getElementById('datas').classList.remove('d-none');
  }

  if(typeof result.result !== 'undefined' && typeof result.result.messages !== 'undefined' && result.result.messages.length > 0) {

    document.getElementById('messages').innerHTML = generateMessages(result.result.messages);
    document.getElementById('messages').classList.remove('d-none');
  }

  if(typeof result.log !== 'undefined' && result.log !== {}) {

    document.getElementById('log').innerHTML = generateLogs(result.log);
    document.getElementById('log').classList.remove('d-none');
  }

  if(typeof result.help !== 'undefined' && result.help != '') {

    document.getElementById('help').innerHTML = result.help;
    document.getElementById('help').classList.remove('d-none');
  }
/*
  if(typeof result.readme !== 'undefined' && result.readme != '') {

    document.getElementById('readme').innerHTML = '<h4>README.md</h4>' + result.readme;
    document.getElementById('readme').classList.remove('d-none');
  }

  if(typeof result.license !== 'undefined' && result.license != '') {

    document.getElementById('license').innerHTML = '<h4>LICENSE</h4>' + result.license;
    document.getElementById('license').classList.remove('d-none');
  }
*/
  document.getElementById('result').classList.remove('d-none');
}

/**
 * Function generateLogs
 * Show log list from data
 *
 * @param Array logs, the logs list to show
 */
function generateLogs(logs) {

    let out = '<table class="table text-light bg-dark fs-small table-sm table-responsive m-0">'
              + '<thead>'
              + '<tr><th class="text-light p-1" colspan="99">Logs</th></tr>'
              + '</thead>'
              + '<tbody>';

    logs.forEach(function(log) {

      let className = 'text-' + log.color + ' bg-' + log.background;

      out += '<tr>'
             + '<td class="' + className + ' p-1" style="width: 11rem;">'
             + log.timestamp
             + '</td>'
             + '<td class="' + className + ' p-1" style="width: 5rem;">'
             + ucfirst(log.type)
             + '</td>'
             + '<td class="' + className + ' p-1">'
             + log.message
             + '</td>'
           +' </tr>';
    });

    return out + '</tbody></table>';
}

/**
 * Function generateMessages
 * Show messages list from data
 *
 * @param Array messages, the messages list to show
 */
function generateMessages(messages) {

  let out = '<table class="table text-light bg-dark fs-small fs-small table-sm table-responsive m-0">'
            + '<thead>'
            + '<tr><th class="text-light p-1" colspan="99">Messages</th></tr>'
            + '</thead>'
            + '<tbody>';

  messages.forEach(function(message) {

    let className = 'text-' + message.color + ' bg-' + message.background;

    out += '<tr>'
           + '<td class="' + className + ' p-1" style="width: 5rem;">'
           + ucfirst(message.type)
           + '</td>'
           + '<td class="' + className + ' p-1">'
           + message.text
           + '</td>'
         +' </tr>';
  });

  return out + '</tbody></table>';
}

/**
 * Function resetResult
 * Empty and hide result objects in page
 */
function resetResult() {

  document.getElementById('datas').innerHTML = '';
  document.getElementById('messages').innerHTML = '';
  document.getElementById('help').innerHTML = '';
  document.getElementById('log').innerHTML = '';
  document.getElementById('response').innerHTML = '';

  document.getElementById('datas').classList.add('d-none');
  document.getElementById('messages').classList.add('d-none');
  document.getElementById('help').classList.add('d-none');
  document.getElementById('log').classList.add('d-none');
  document.getElementById('response').classList.add('d-none');

  document.getElementById('result').classList.add('d-none');
}

/**
 * Function resetArguments
 * Reset arguments names and values
 */
function resetArguments() {

  document.getElementsByName('name[]').forEach(function(node, key) {

    node.value = '';
  });

  document.getElementsByName('value[]').forEach(function(node, key) {

    node.value = '';
  });
  resetResult();
}

/**
 * Function objectAsTable
 * Show object content in a table
 *
 * @param String title, the table title
 * @param Object|Array object, the object to show
 *
 * @return String, the generated table with object content
 */
function objectAsTable(title, object) {

  let out = '';

  if(object !== null) {

    // Convert array to object
    if(Object.prototype.toString.call(object) === '[object Array]') {

      object = Object.assign({}, object);
    }

    out = '<table class="table table-responsive table-sm table-hover m-0">';

    if(title !== '') {

      out += '<thead>'
             + '<tr>'
               + '<th class="p-1" colspan="99">'
                 + '<h5>' + title + '</h5>'
               + '</th>'
             + '</tr>'
           + '</thead>';
    }
    out +=   '<tbody>';


    Object.keys(object).forEach(key => {

      if(typeof object[key] == 'object') {

        out += '<tr><th class="col text-start p-1" style="width: 20%;">' + key + '</th><td class="col text-start">' + objectAsTable('', object[key]) + '</td></tr>';

      } else {

        out += '<tr><th class="p-1" style="width: 20%;">' + key + '</th><td class="p-1">' + object[key] + '</td></tr>';
      }
    });

    out += '</tbody></table>';
  }

  return out;
}

/**
 * Function ucfirst
 * Capitalize word (first letter uppercase)
 *
 * @param String word, the word to capitalize
 *
 * @return String, the capitalized word
 */
function ucfirst(word) {

    return word.charAt(0).toUpperCase() + word.slice(1);
}

/**
 * Function isJson
 * Verify if a string is a JSON definition
 *
 * @param String string, the string to verify
 *
 * @return Boolean, true if string is Json, otherwise false
 */
function isJson(string) {

  try {

    JSON.parse(string);

  } catch (e) {

    return false;
  }

  return true;
}
