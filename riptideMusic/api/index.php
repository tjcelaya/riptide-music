<?php
require 'vendor/autoload.php';

//file include
ob_start();
require 'dbPassFileUnguessableName';
$dbPassword = ob_get_clean();

$sqlConnection = mysqli_connect(
  'dbsrv2.cs.fsu.edu',
  'tilley',
  $dbPassword,
  'tilleydb'
  );

//instantiate slim
$app = new \Slim\Slim(
  array(
      'debug' => true,
      // 'view' => new \Slim\Extras\Views\Smarty()
  )
);

//this is a debugging route
$app->get('/', function() use ($sqlConnection, $dbPassword) {
    // this is actually /~celaya/api/ (or w/o the slash)
    // all paths the slim app defines are relative to itself
  echo gettype($dbPassword);
})->name('index');

require 'users.php';
require 'music-engine.php';

$app->run();
