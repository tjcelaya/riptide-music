<?php

//route returns album info
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

  //here we need k because we are destructively modifying and creating arrays which
  //doesnt work with the v in foreach $arr as $v for some reason
  foreach ($queryDetails as $k => $albumReturned) {

    if(isset($queryDetails[$k]['tracklist'])) { 
      $queryDetails[$k]['tracks'] = 
        explode('|', $queryDetails[$k]['tracklist']);
      foreach ($queryDetails[$k]['tracks'] as $kk => $v) {
        $queryDetails[$k]['tracks'][$kk] = explode('~',$v);
      }
      unset($queryDetails[$k]['tracklist']);
    }

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

    $queryDetails[$k]['tags'] = array();

    $tagQuerySuccess =
      get_sql_results(
        $queryDetails[$k]['tags'],
        $sqlConnection,
        "select tagName from AlbumTags where albumID={$queryDetails[$k]['albumID']}"
      );
    //compact array of arrays into single tags array
    if($tagQuerySuccess)
      foreach ($queryDetails[$k]['tags'] as $kk => $tag) {
        $queryDetails[$k]['tags'][$kk] = $tag['tagName'];
      }
  }

  $queryDetails['artist'] = $exactArtistName;

  if ($sqlSuccess)
    echo json_encode($queryDetails);
  else
    echo json_encode(array('err'=>'SQLerr'));

});
