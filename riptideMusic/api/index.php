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

$app->get('/album/:artist', function($artist) use ($sqlConnection) {
  
  $artistDetails = array();

  $artistQuery = mysqli_query(
    $sqlConnection,
    "select * from Albums where artistID in (select artistID from Artists where artistName='Michael Jackson' AND albumName='Thriller');"
    );

  //get them record by record into the result object
  if($artistQuery->num_rows > 0) {
      while($row = $artistQuery->fetch_assoc()) {
          array_push($artistDetails, $row);
      }
  }
  else {
      echo json_encode(array('err' =>'NO RESULTS'));
  }

  $tagQuery = mysqli_query(
    $sqlConnection,
    "select * from AlbumTags where albumID in (select artistID from Artists where artistName='$artist')"
    );

  if($tagQuery->num_rows > 0) {
      while($row = $tagQuery->fetch_assoc()) {
          array_push($artistDetails, $row);
      }
  }
  else {
    echo json_encode(array('err' => 'NO TAGS'));
  }

  echo json_encode($artistDetails);

});

require 'users.php';
require 'music-engine.php';

$app->run();
