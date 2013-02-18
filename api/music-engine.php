<?php

//route returns album info
//if given artist, gets all albums by that artist, 
//if given artist+album(expressed as /albuminfo/SOMEARTIST/SOMEALBUM[/], 
//    gets that album info 
//captures /api/albuminfo/x0/x1/x2... as
// $params[0] => x0
// $params[1] => x1 etc
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
      "select albumName, released, avgRating, tracklist, imageURL from Albums ".
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

// get album review test -- rick
$app->get('/review/:parameters+',
  function($parameters) use ($sqlConnection)
  {
    if(!isset($parameters[0]))
    { echo json_encode(array('err'=>'NO params'));
      return;
    }
    $queryDetails = array();
    // maybe select 5 at a time, index selections
    $sqlSuccess = get_sql_results($queryDetails, $sqlConnection,
//    "select * from Reviews");
 //   $sqlSuccess = get_sql_results($queryDetails, $sqlConnection,
    "select R.* from Reviews R, Albums A, Artists B ".
    "where B.artistName = '{$parameters[0]}' ".
    "AND A.albumName = '{$parameters[1]}' ".
    "AND A.artistID = B.artistID ".
    "AND R.albumID = A.albumID");
    if ($sqlSuccess)
      echo json_encode($queryDetails);
    else
      echo json_encode(array('err'=>'SQLerr'));
});

// get album review test -- rick
$app->get('/blah/:parameters+',
  function($parameters) use ($sqlConnection)
  {
    if(!isset($parameters[0]))
    { echo json_encode(array('err'=>'NO params'));
      return;
    }
    $queryDetails = array();
    // maybe select 5 at a time, index selections
    $sqlSuccess = get_sql_results($queryDetails, $sqlConnection,
    "select * from Albums B, Artists A");
    if ($sqlSuccess)
      echo json_encode($queryDetails);
    else
      echo json_encode(array('err'=>'SQLerr'));
});

