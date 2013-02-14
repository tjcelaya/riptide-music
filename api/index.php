<?php
require 'vendor/autoload.php';
// use this to send json explicitly if the browser isnt interpreting the response correctly
// header("Content-Type: application/json");

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
      'debug' => true,
      // 'view' => new \Slim\Extras\Views\Smarty()
  )
);

// array byref, 
// sql object (usually the global $sqlConnection)
// sql query string
function get_sql_results(&$arrayToAppendResults, $sqlC, $query) {

  assert(strlen($query) > 1);
  //execute query
  $queryResult = mysqli_query(
    $sqlC,
    $query
    );

  //check for sql errors
  if (mysqli_connect_errno()) {
    exit('Connect failed: '. mysqli_connect_error());
  }

  //retrieve results into array
  if($queryResult->num_rows > 0) {
      while($row = $queryResult->fetch_assoc()) {
          array_push($arrayToAppendResults, $row);
      }
      return true;
  }
  else {
      return false;
  }
};

http://ww2.cs.fsu.edu/~celaya/riptideMusic/api/d/Pink+Floyd/Dark+Side+of+the+Moon
//this is a debugging route
$app->get('/', function() use ($dbPassword) {
  // this is actually /~celaya/api/ (or w/o the slash)
  // all paths the slim app defines are relative to itself,
  // so this is "/riptideMusic/api/"
  // its also here so that there is something returned if 
  // someone wanders to that url
  echo gettype($dbPassword);
})->name('index');

//this route currently returns all the album art an artist has,
//its just 90px thumbnails though, working on that
$app->get('/d/:artist/:album', function($artist, $album) use ($discogs) {
  
  $dReq = $discogs->search(array(
      'artist' => $artist,
      'title' => $album,
      'type' => 'master'
    ));

  foreach ($dReq as $v) {
    // echo "<BR>";
    // echo "<BR>";

    $albumDetails = $v->toArray();

    $masterName = $v->getTitle();
    $requestedName = $artist." - ".$album;
    
    if ($masterName == $requestedName)
    {
      // echo "<h1>perfectMATCH</h1>";
      echo json_encode($albumDetails);
      break;
    }
    elseif (levenshtein($masterName, $requestedName,
      2, 0, 10)/strlen($masterName) < 0.4) 
    {
      // echo "<h2>partialMATCH</h2>";
      echo json_encode($albumDetails);
      break;
    }
    // else
      // echo json_encode(array('err' => 'noMATCH'));

  }
  exit;

});


require 'users.php';
require 'music-engine.php';

$app->run();
