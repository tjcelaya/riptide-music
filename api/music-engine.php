<?php

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

// get album grabber test -- rick
// grabs from mySQL, or if missing album, looks at discogs
$app->get('/go/:params+', function($params) use ($discogs, $sqlConnection) {


  if(!isset($params[0]))
  {
    echo json_encode(array('err'=>'NO params'));
    return;
  }

  $queryDetails = array();

  $sqlSuccess =
    get_sql_results(
      $queryDetails,
      $sqlConnection,
      "select albumName, released, avgRating, tracklist from Albums ".
      "where albumName = '{$params[1]}' AND artistID in ".
        "(select artistID from Artists where ". 
          "artistName='{$params[0]}'".
          ");"
      );


  if ($sqlSuccess)
  {
    echo json_encode($queryDetails);
    return;
  } // else we don't have that song in our database, go to discogs
  else
  {
    $sqlQueryResult = array();
    $artist = $params[0];
    if(isset($params[1]))
      $album = $params[1];
    else
      $album = null;

 //  echo "you sent:<BR>";
 //  print_r($params);
 //  echo "<BR>";

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
          echo json_encode($result);
          return;
          // exit with matched result
        }
        else
        {//returned from result but name doesnt match
          //add to results and continue
          $foundMaster = $discogs->getMaster($v->getId());
          get_discogs_albumdata($result, $foundMaster);
echo "else $sqlQueryResult";
        }
      }
  }
echo "spit 2: ";
  echo json_encode($result);
  //no direct match, return all
  }
});
