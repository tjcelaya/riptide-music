<?php
require 'vendor/autoload.php';
// use this to send json explicitly if the browser isnt interpreting the response correctly
// header("Content-Type: application/json");
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

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

function get_discogs_albumdata(&$arrayToAppendResults, &$discogsMaster) {

  if (is_null($discogsMaster))
  {
    return;
  }

  $tempResult = array(
    'artistName' => count($artistReq = $discogsMaster->getArtists()) ? $artistReq[0]->getName() : null,
    'albumName' => $discogsMaster->getTitle(),
    'released' => $discogsMaster->getYear(),
    'genre' => count($genreReq = array_merge((array)$discogsMaster->getGenres(), (array)$discogsMaster->getStyles())) ? ($genreReq) : null,
    'imageURL' => count($imgReq = $discogsMaster->getImages()) ? $imgReq[0]->getUri150() : null,
    'avgRating' => 2, 
    'tags' => array_merge((array)$discogsMaster->getGenres(), (array)$discogsMaster->getStyles()), 
    'tracks' => count($tracksReq = $discogsMaster->getTracklist()) ? array() : null
    );

  // $tempResult['tracks'] = array_values($tempResult['tracks']);
  foreach ($tracksReq as $t)
  {
    // echo gettype($t->toArray());
    array_push($tempResult['tracks'], array_values($t->toArray()));
  }
  // $tempResult['test'] = $tempResult['test'][0]->toArray();
  // var_dump($tempResult);
  array_push($arrayToAppendResults, $tempResult);
  return;
}

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
$app->get('/d/:params+', function($params) use ($discogs) {

  $artist = $params[0];

  if(isset($params[1]))
    $album = $params[1];
  else
    $album = null;

  // echo "you sent:<BR>";
  // print_r($params);
  // echo "<BR>";
  
  $result = array();

  //do req
  if ($album == '')
  {
    $result[0] = 'empty';
    $discogsRequestParams = array(
        'q' => $artist,
        'type' => 'master'
      );
  }
  else
  {
    $result[0] = 'specified';
    $discogsRequestParams = array(
        'artist' => $artist,
        'q' => $album,
        'type' => 'master'
      );
  }

  $resultFound = false;
  $maxTries = 3;
  $maxDiscogsRetrieves = 5;
  
  // while (!$resultFound && --$maxTries != 0 )
  // {
    $dReq = $discogs->search($discogsRequestParams);

    // echo count($dReq)."<BR>";
    // echo "alb:".$result[0]."<BR>";
    // echo "try:".$maxTries."<BR>";

    foreach ($dReq as $k => $v) {
      if ($k > $maxDiscogsRetrieves)
        break;

      // echo "<BR>".$k."<BR>";
      //convert current result to hashmap
      // var_dump(
      $albumDetails = $v->toArray()
      //   )
      ;
      // echo "<BR>".$k."<BR>";
      $masterName = $albumDetails['title'];
      $requestedName = $artist." - ".$album;
      
      // result is a valid format
      if (is_array($albumDetails['format']) 
        && 
          (in_array("Album", $albumDetails['format']) ||
          in_array("LP", $albumDetails['format']) ||
          in_array("EP", $albumDetails['format']) || 
          in_array("CD", $albumDetails['format']) || 
          in_array("Vinyl", $albumDetails['format'])
            )
        &&
          (!in_array("Unofficial Release", $albumDetails['format']) ||
            !in_array("Single", $albumDetails['format']) ||
            !in_array("Compilation", $albumDetails['format'])
            )
        )
      {
        if ($result[0] == 'specified' && 
              ($masterName == $requestedName 
                || 
                levenshtein($masterName, $requestedName)/strlen($masterName) < 0.2
                )
          )
        {
          // found matching name 

          // if($D) {
            // echo "<BR>";
            // echo "album:".$album."<BR>";
            // echo "artist:".$artist."<BR>";
            // echo "master:".$masterName."<BR>";
            // echo "reques:".$requestedName."<BR>";
            // echo "lev:".levenshtein($masterName, $requestedName)."<BR>";
            // echo "len:".strlen($masterName)."<BR>";
            // echo "ratio:<h1>".levenshtein($masterName, $requestedName)/strlen($masterName)."</h1><BR>";
          // }
          // */

          $foundMaster = $discogs->getMaster($v->getId());

          if (is_null($foundMaster))
          {
            continue;
          }

          get_discogs_albumdata($result, $foundMaster);
          echo json_encode(array_slice($result,1));
          return;
          // exit with matched result
        }
        else
        {//returned from result but name doesnt match
          //add to results and continue
          $foundMaster = $discogs->getMaster($v->getId());
          get_discogs_albumdata($result, $foundMaster);
        }
      }
  }
  echo json_encode(array_slice($result,1));
  //no direct match, return all

});


require 'users.php';
require 'music-engine.php';

$app->run();
