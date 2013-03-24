<?php
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

  if(is_bool($queryResult))
    return $queryResult;

  //retrieve results into array
  if($queryResult->num_rows > 0 && !is_null($arrayToAppendResults) ) {
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

function getAlbumByID(&$arrayToAppendResults, $sqlC, $id) {

  assert(0 < $id);
  
  $sqlSuccess =
    get_sql_results(
      $arrayToAppendResults,
      $sqlC,
      "select albumName, artistName, released, avgRating, tracklist, albumID ".
      "from Albums natural join Artists ".
      "where albumID=$id"
    );

  if (!isset($arrayToAppendResults[0]))
    return $sqlSuccess;

  $arrayToAppendResults = $arrayToAppendResults[0];

  if(isset($arrayToAppendResults['tracklist'])) { 
    $arrayToAppendResults['tracks'] = 
      explode('|', $arrayToAppendResults['tracklist']);
    foreach ($arrayToAppendResults['tracks'] as $k => $v) {
      $arrayToAppendResults['tracks'][$k] = explode('~',$v);
    }
    unset($arrayToAppendResults['tracklist']);
  }
  
  $arrayToAppendResults['genres'] = array();
  $arrayToAppendResults['tags'] = array();

  $genreQuerySuccess =
    get_sql_results(
      $arrayToAppendResults['genres'],
      $sqlC,
      "select genreName from AlbumGenres where albumID=$id"
    );
  //compact array of arrays into single genres array
  if($genreQuerySuccess)
    foreach ($arrayToAppendResults['genres'] as $kk => $genre) {
      $arrayToAppendResults['genres'][$kk] = $genre['genreName'];
    }

  $tagQuerySuccess =
    get_sql_results(
      $arrayToAppendResults['tags'],
      $sqlC,
      "select tagName from AlbumTags where albumID=$id"
    );

  if($tagQuerySuccess)
    foreach ($arrayToAppendResults['tags'] as $kk => $tag) {
      $arrayToAppendResults['tags'][$kk] = $tag['tagName'];
    }

  return $sqlSuccess;
};


function getAlbumsByName(&$arrayToAppendResults, $sqlC, $queryString) {

  $sqlSuccessAlbums =
    get_sql_results(
      $arrayToAppendResults,
      $sqlC,
      "select albumName, artistName, released, avgRating, tracklist, albumID ".
      "from Albums natural join Artists ".
      "where INSTR(`albumName`, '$queryString') > 0 ;"
    );
  
  $sqlSuccessArtists =
    get_sql_results(
      $arrayToAppendResults,
      $sqlC,
      "select albumName, artistName, released, avgRating, tracklist, albumID ".
      "from Albums natural join Artists ".
      "where INSTR(`artistName`, '$queryString') > 0 ;"
    );

  return $sqlSuccessAlbums | $sqlSuccessArtists;
};
