		<?php
  //Recommendation for a logged in user based off an albums page
$app->get('/recommendation/album/:id/:userid', function($id,$userid) use ($sqlConnection) {
  //uses slim framework
//    require("recommendation-User-EDIT.php");
    $userAlbums=array();
    $sqlQueryResult = array();
    $results=array();
    $dups = array();
  
      $user=get_sql_results(
                    $userAlbums,
                    $sqlConnection,
      "select albumID,rating"." from Rates"." where userID={$userid}".
                    " order by rating DESC limit 0, 6"
                    );
 foreach($userAlbums as $k => $uAlbum)
	{
	  $album =
    get_sql_results(

                   $sqlQueryResult,
                    $sqlConnection,
      "select tagName, weight"." from AlbumTags"." where albumID={$uAlbum['albumID']}".
                    " order by weight DESC limit 0, 6"
                    );
      $first = array("albumID" => "{$uAlbum['albumID']}");
      array_push($dups, $first);
	}

  $album =
    get_sql_results(
	
                   $sqlQueryResult,
                    $sqlConnection,
      "select tagName, weight"." from AlbumTags"." where albumID={$id}".
                    " order by weight DESC limit 0, 6"
                    ); // gets the album from the database
  
if($album)// If it exists find album with same tag
  {
	foreach ($sqlQueryResult as $kk => $tag) 
       {
        //var_dump($tag);
	// echo "<br>";
  	$myq = "select albumID,albumName,artistName,released from AlbumTags natural Join Albums natural Join Artists where tagName ='{$tag['tagName']}' limit 0, 3";
      	//  $myq = "select albumID"." from AlbumTags"." where tagName ='Jackson'";
      	// echo "trying $myq<br>";
    	$query= get_sql_results(
                            $results,
                            $sqlConnection,
                            $myq
                            );
	//var_dump($results);
	// if ($query)
	//echo "is: <br>";
	// else
	// echo "is not true<br>";
       }
      //    var_dump($results);

      $first = array("albumID" => "{$id}");
      array_push($dups, $first);
      foreach ($results as $t => $tn)
        {
          if (in_array($tn, $dups))
            unset($results[$t]);
          else
            array_push($dups,$tn);
        }
    if(!$query){ //if no result
      echo json_encode(array('err'=>'no RESULTS'));
      return;}

    echo json_encode($results);
      // encode results and return
      return;
  }
  else
          {
            // if album does not exist
            echo json_encode(array('err'=>'no ALBUMS'));
            return;
          }

  }
);
$app->get('/recommendation/user/:userid', function($userid) use ($sqlConnection) {
  //uses slim framework
    $userAlbums=array();
    $sqlQueryResult = array();
    $results=array();
    $dups = array();
    $ralbums=array();
      $user=get_sql_results(
                    $userAlbums,
                    $sqlConnection,
      "select albumID,rating"." from Rates"." where userID={$userid}".
                    " order by rating DESC limit 0, 6"
                    );
 foreach($userAlbums as $k => $uAlbum)
        {
          $album =
    get_sql_results(

                   $sqlQueryResult,
                    $sqlConnection,
      "select tagName, weight"." from AlbumTags"." where albumID={$uAlbum['albumID']}".
                    " order by weight DESC limit 0, 6"
                    );
      $first = array("albumID" => "{$uAlbum['albumID']}");
      array_push($dups, $first);
        }
if($album)// If it exists find album with same tag
  {
        foreach ($sqlQueryResult as $kk => $tag)
       {
        //var_dump($tag);
        // echo "<br>";
        $myq = "select albumID,albumName,artistName,released from AlbumTags natural Join Albums natural Join Artists where tagName ='{$tag['tagName']}' limit 0, 3";
        //  $myq = "select albumID"." from AlbumTags"." where tagName ='Jackson'";
        // echo "trying $myq<br>";
        $query= get_sql_results(
                            $results,
                            $sqlConnection,
                            $myq
                            );
        //var_dump($results);
        // if ($query)
        //echo "is: <br>";
        // else
        // echo "is not true<br>";
       }
      //    var_dump($results);

      
      array_push($dups, $first);
      foreach ($results as $t => $tn)
        {
          if (in_array($tn, $dups))
            unset($results[$t]);
          else
            array_push($dups,$tn);
        }
	
    if(!$query){ //if no result
      echo json_encode(array('err'=>'no RESULTS'));
      return;}

    echo json_encode($results);
      // encode results and return
      return;
  }
  else
          {
            // if album does not exist
            echo json_encode(array('err'=>'no ALBUMS'));
            return;
          }

  }
);
  //Recommendation for a logged in user based off an genre page
$app->get('/recommendation/genre/:id/:userid', function($id,$userid) use ($sqlConnection) {
  //uses slim framework
    $userAlbums=array();
    $examples=array();
    $sqlQueryResult = array();
    $results=array();
    $dups = array();

      $user=get_sql_results(
                    $userAlbums,
                    $sqlConnection,
      "select albumID,rating"." from Rates"." where userID={$userid}".
                    " order by rating DESC limit 0, 6"
                    );

	$album=get_sql_results(
                    $examples,
                    $sqlConnection,
      "select albumID"." from GenreExamples"." where genreName='{$id}'"
                    );
foreach($examples as $k => $eAlbum)
        {
          $genre =
    get_sql_results(

                   $sqlQueryResult,
                    $sqlConnection,
      "select tagName, weight"." from AlbumTags"." where albumID={$eAlbum['albumID']}".
                    " order by weight DESC limit 0, 6"
                    );
      
//var_dump($uAlbum['albumID']);
       }

 foreach($userAlbums as $k => $uAlbum)
        {
          $genre =
    get_sql_results(

                   $sqlQueryResult,
                    $sqlConnection,
      "select tagName, weight"." from AlbumTags"." where albumID={$uAlbum['albumID']}".
                    " order by weight DESC limit 0, 6"
                    );
      $first = array("albumID" => "{$uAlbum['albumID']}");
      array_push($dups, $first);
//var_dump($uAlbum['albumID']);
       }




if($album)// If it exists find album with same tag
  {
        foreach ($sqlQueryResult as $kk => $tag)
       {
        //var_dump($tag);
        // echo "<br>";
        $myq = "select albumID,albumName,artistName,released from AlbumTags natural Join Albums natural Join Artists  where tagName ='{$tag['tagName']}' limit 0, 4";
        //  $myq = "select albumID"." from AlbumTags"." where tagName ='Jackson'";
        // echo "trying $myq<br>";
        $query= get_sql_results(
                            $results,
                            $sqlConnection,
                            $myq
                            );
        //var_dump($results);
        // if ($query)
        //echo "is: <br>";
        // else
        // echo "is not true<br>";
       }
      //    var_dump($results);

      $first = array("albumID" => "{$id}");
      array_push($dups, $first);
      foreach ($results as $t => $tn)
        {
          if (in_array($tn, $dups))
            unset($results[$t]);
          else
            array_push($dups,$tn);
        }
    if(!$query){ //if no result
      echo json_encode(array('err'=>'no RESULTS'));
      return;}

    echo json_encode($results);
      // encode results and return
      return;
  }
  else
          {
            // if album does not exist
            echo json_encode(array('err'=>'no ALBUMS'));
            return;
          }

  }
);


