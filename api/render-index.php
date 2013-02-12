<?php

// index page - navigated to by users, 
$app->get('/', function() use ($sqlConnection) {
  // get the template into a variable, 
  echo file_get_contents('index.html');
})->name('index');
