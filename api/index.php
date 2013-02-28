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

  if(!$queryResult)
    return false;
      
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
    'avgRating' => 0, 
    'tags' => array_merge((array)$discogsMaster->getGenres(), (array)$discogsMaster->getStyles()), 
    'tracks' => count($tracksReq = $discogsMaster->getTracklist()) ? array() : null,
    'dID' => $discogsMaster->getID()
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

//this route retrieves search results from discogs
$app->get('/dget/:params+', function($params) use ($discogs, $sqlConnection) {
  $artist = $params[0];

  if(isset($params[1]))
    $album = $params[1];
  else
    $album = null;

  $result = array();
  $sqlQueryResult = array();

  //album was specified, look for ours
  if ($album != '')
  {
    $result[0] = 'specified';
    $discogsRequestParams = array(
        'artist' => $artist,
        'q' => $album,
        'type' => 'master'
      );
  }
  else
  {
    $result[0] = 'empty';
    $discogsRequestParams = array(
        'q' => $artist,
        'type' => 'master'
      );
  }

  $resultFound = false;
  $maxDiscogsRetrieves = 5;
  
  $dReq = $discogs->search($discogsRequestParams);

  foreach ($dReq as $k => $v) {
    if ($k > $maxDiscogsRetrieves)
      break;

    //convert current result to hashmap
    $albumDetails = $v->toArray();
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
        $foundMaster = $discogs->getMaster($v->getId());

        if (is_null($foundMaster))
          continue;

        get_discogs_albumdata($result, $foundMaster);
       
        echo json_encode(array_merge(array_slice($result,1), $sqlQueryResult));
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
});

$app->post('/found/:discogsID', function ($discogsID) use ($app, $sqlConnection, $discogs) {

  $albumInfo = array();

  // get discogs data
  get_discogs_albumdata($albumInfo, $discogs->getMaster($discogsID));
  assert(0 < count($albumInfo));

  $albumInfo = $albumInfo[0];
  $albumInfo['albumName'] = mysqli_escape_string($sqlConnection, $albumInfo['albumName']);

  // assign meaningful names
  $artistName = mysqli_escape_string($sqlConnection, $albumInfo['artistName']);
  $fullAlbumTitle = $albumInfo['artistName']." - ".$albumInfo['albumName'];
  $genreString = implode('|', $albumInfo['genre']);
  $remoteImageURL = $albumInfo['imageURL'];
  $localImageName = $fullAlbumTitle.'('.$albumInfo['released'].')';
  $tracks = implode("|", 
    array_map(function($a) use ($sqlConnection) {
      return mysqli_escape_string($sqlConnection, implode('~', $a));
    }, 
      $albumInfo['tracks']));

  //////////////////////////////////
  // IMAGE - check if we have the 
  // quick way of getting correct file destination,
  // always make sure to chdir back
  chdir('..');
  $localImageDir = getcwd()."/img/$localImageName.jpg";
  chdir('api');  

  if(!file_exists($localImageDir))
  {//get album art since we dont have it
    copy($remoteImageURL, $localImageDir);
  }

  //////////////////////////////////
  //ARTIST - check if we have the artist in our db
  $internalArtistSearch = "select artistID from Artists where ". 
                            "artistName='{$artistName}';";
  $artistCheck = array();

  get_sql_results(
    $artistCheck,
    $sqlConnection,
    $internalArtistSearch
    );

  if(!count($artistCheck))
  {//insert artist since we dont have them
    $artistInsert = "INSERT INTO Artists (artistName, artistGenres) VALUES ('{$artistName}', '{$genreString}');";
    mysqli_real_query($sqlConnection, $artistInsert);

    //get their ID to use in the album insert
    get_sql_results(
      $artistCheck,
      $sqlConnection,
      $internalArtistSearch
      );
  }
  
  $internalArtistID = $artistCheck[0]['artistID'];

  echo $internalAlbumSearch = "select albumID from Albums where ".
                          "albumName='{$albumInfo['albumName']}' and ".
                          "artistID='$internalArtistID' and ".
                          "released={$albumInfo['released']};";
  $albumCheck = array();
  get_sql_results(
    $albumCheck,
    $sqlConnection,
    $internalAlbumSearch
    );
  echo json_encode($albumCheck);
  echo "<BR>insert: ";

  if(!count($albumCheck))
  {//new album, insert into Albums and AlbumGenres
    echo $albumInsert = 'INSERT INTO Albums '.
      '(albumName, released, tracklist, artistID ) '.
      'VALUES '.
      "('{$albumInfo['albumName']}', {$albumInfo['released']}, '$tracks', $internalArtistID )";

    echo "albuminsert: ".json_encode(mysqli_real_query($sqlConnection, $albumInsert));

    get_sql_results(
      $albumCheck,
      $sqlConnection,
      $internalAlbumSearch
      );

  echo json_encode($albumCheck);
  echo "<BR>";
  
    array_walk($albumInfo['genre'],
      function ($g) use ($albumCheck, $sqlConnection) {
        $albumGenresInsert = 'INSERT INTO AlbumGenres '.
            '(albumID, genreName)'.
            'VALUES '.
            "({$albumCheck[0]['albumID']},'$g');";
        mysqli_real_query($sqlConnection, $albumGenresInsert);
    });

  }
  
  $app->redirect(
    "http://$_SERVER[HTTP_HOST]/~celaya/riptideMusic/artist.php?name=$artistName"
  );
});

require 'users.php';
require 'music-engine.php';
require 'albumsByArtist.php';


$app->run();
