<?php
require 'vendor/autoload.php';
// use this to send json explicitly if the browser isnt interpreting the response correctly
// header("Content-Type: application/json");


/*
methods of returned object from a master
"getArtists"
"getDataQuality"
"getGenres"
"getId"
"getImages"
"getMainRelease"
"getMainReleaseUrl"
"getResourceUrl"
"getStyles"
"getTitle"
"getTracklist"
"getUri"
"getVersionsUrl"
"getYear"
"getVideos",
"toArray"
*/


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
$app->get('/d/:artist(/:album)', function($artist, $album = '') use ($discogs) {
  
  $foundMaster = array();

  $dReq = $discogs->search(array(
        'artist' => $artist,
        'q' => $album,
        'type' => 'master'
      ));
  echo count($dReq);

  foreach ($dReq as $v) {
    //convert current result to hashmap
    $albumDetails = $v->toArray();
    echo json_encode($albumDetails);
    $masterName = $v->getTitle();
    $requestedName = $artist." - ".$album;
    
    if (is_array($albumDetails['format']) 
      && 
        (in_array("Album", $albumDetails['format']) ||
        in_array("LP", $albumDetails['format']) ||
        in_array("EP", $albumDetails['format']) || 
        in_array("Vinyl", $albumDetails['format'])
          )
      )
    {
      if ($masterName == $requestedName || 
          levenshtein($masterName, $requestedName)/strlen($masterName) < 2
          )
      {
        echo "<BR>";
        echo $masterName."<BR>";
        echo "lev:".levenshtein($masterName, $requestedName)."<BR>";
        echo "len:".strlen($masterName)."<BR>";
        echo "ratio:".levenshtein($masterName, $requestedName)/strlen($masterName)."<BR>";
        $masterReq = $discogs->getMaster($v->getId());

        if (is_null($masterReq))
        {
          echo json_encode(array('err' => 'no master found'));
          exit;
        }

        $foundMaster = $masterReq->getImages();

          echo "<BR>";
          var_dump($foundMaster[0]);
          echo "<BR>";
          echo "<img src='".$foundMaster[0]->getUri150()."'/>";

        break;
      }
    }
  }
});


require 'users.php';
require 'music-engine.php';

$app->run();
