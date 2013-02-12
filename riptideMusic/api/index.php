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

//this is a debugging route
$app->get('/', function() use ($sqlConnection, $dbPassword) {
    // this is actually /~celaya/api/ (or w/o the slash)
    // all paths the slim app defines are relative to itself
  echo gettype($dbPassword);
})->name('index');

//route returns album info
//if given artist, gets all albums by that artist, 
//if given artist/album, gets that album info 
//this route captures anything defined after /api/album/x0/x1/x2/x3...
//where para[0] => x0, [1] => x1 etc
$app->get('/albuminfo/:parameters+', 
  function($parameters) use ($sqlConnection) {
  //first parameter is artist name  
  //second parameter is album

  // var_dump($parameters); 
  //make sure there is at least one param
  if(!isset($parameters[0]))
  {
    echo json_encode(array('err'=>'NO params'));
    return;
  }

  $queryDetails = array();

  $sqlSuccess =
    get_sql_results(
      $queryDetails,
      $sqlConnection,
      "select * from Albums ".
      "where artistID in ".
        "(select artistID from Artists where ". 
          "artistName='{$parameters[0]}'".
          ");"
      );

  $queryDetails['tags'] = array();

  $tagQuerySuccess =
    get_sql_results(
      $queryDetails['tags'],
      $sqlConnection,
      "select tagName from AlbumTags ".
      "where albumID in ".
        "(select artistID from Artists where ".
          "artistName='{$parameters[0]}')"
    );

  if ($sqlSuccess or $tagQuerySuccess)
    echo json_encode($queryDetails);
  else
    echo json_encode(array('err'=>'SQLerr'));

});

require 'users.php';
require 'music-engine.php';

$app->run();
