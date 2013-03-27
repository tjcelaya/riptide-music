<?php
require 'vendor/autoload.php';
// use this to send json explicitly if the browser isnt interpreting the response correctly
// header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set("display_errors", 1);

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

$discogs = new \Discogs\Service();

//instantiate slim
$app = new \Slim\Slim(
  array(
      'debug' => true
  )
);

require 'data-retrieval-funcs.php';

//this is a debugging route
$app->get('/', function() use ($dbPassword) {
  // this is actually /~celaya/api/ (or w/o the slash)
  // all paths the slim app defines are relative to itself,
  // so this is "/riptideMusic/api/"
  // its also here so that there is something returned if 
  // someone wanders to that url
  echo gettype($dbPassword);
})->name('index');

//this is a debugging route
$app->get('/getLatest/', function() use ($sqlConnection) {
  $sqlQueryResult = array();

  $searchSuccess = 
    get_sql_results(
      $sqlQueryResult,
      $sqlConnection,
      "select albumName, artistName, released, avgRating, tracklist, albumID, artistID ".
      "from Albums natural join Artists ".
      "where albumID!=-1 ".
      "order by albumID desc"
    );

  echo json_encode($sqlQueryResult);
});


//this is a debugging route
$app->get('/internalSearch/:query+', function($query) use ($sqlConnection) {
  
  $sqlQueryResult = array();
  $query = implode('+', $query);


  $searchSuccess = 
    getAlbumsByName(
      $sqlQueryResult,
      $sqlConnection,
      $query
    );

  if(!$searchSuccess) {
    echo json_encode(array('err' => 'notfound'));
    return;
  }

  echo json_encode(($sqlQueryResult));
});

require 'users.php';
require 'music-engine.php';
require 'genre-engine.php';
require 'albumsByArtist.php';
require 'albumByID.php';
require 'discogsRoutes.php';


$app->run();
