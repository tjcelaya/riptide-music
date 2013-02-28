<?php

//route returns album info
//if given artist, gets all albums by that artist, 
//if given artist+album(expressed as /albuminfo/SOMEARTIST/SOMEALBUM[/], 
//    gets that album info 
//captures /api/albuminfo/x0/x1/x2... as
// $params[0] => x0
// $params[1] => x1 etc
$app->get('/albumsByArtist/:parameters+', 
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

  $nameCheck = array();

  $nameCheckSql =
    get_sql_results(
      $nameCheck,
      $sqlConnection,
      "select artistName from Artists where ". 
          "artistName='{$parameters[0]}';"
    );

  $exactArtistName = $nameCheck[0]['artistName'];

  $queryDetails = array();
  $sqlSuccess =
    get_sql_results(
      $queryDetails,
      $sqlConnection,
      "select albumName, released, avgRating, tracklist, albumID from Albums ".
      "where artistID in ".
        "(select artistID from Artists where ". 
          "artistName='{$exactArtistName}');"
    );

  if(!$sqlSuccess){
    echo json_encode(array('err'=>'no ALBUMS'));
    return;
  }

  foreach ($queryDetails as $k => $albumReturned) {
    $queryDetails[$k]['genres'] = array();

    $genreQuerySuccess =
      get_sql_results(
        $queryDetails[$k]['genres'],
        $sqlConnection,
        "select genreName from AlbumGenres where albumID={$queryDetails[$k]['albumID']}"
      );
    //compact array of arrays into single genres array
    if($genreQuerySuccess)
      foreach ($queryDetails[$k]['genres'] as $kk => $genre) {
        $queryDetails[$k]['genres'][$kk] = $genre['genreName'];
      }

    $tagQuerySuccess =
      get_sql_results(
        $queryDetails[$k]['tags'],
        $sqlConnection,
        "select tagName from AlbumTags where albumID={$queryDetails[$k]['albumID']}"
      );
  }

  $queryDetails['artist'] = $exactArtistName;

  if ($sqlSuccess)
    echo json_encode($queryDetails);
  else
    echo json_encode(array('err'=>'SQLerr'));

});
