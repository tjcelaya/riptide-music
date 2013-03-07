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
