<?php
  //Recommendation for a non logged in user based off an albums page                                                                
$app->get('/recommendation/album/:id', function($id) use ($sqlConnection) {
//uses slim framework                                  

    $sqlQueryResult = array();
    $results=array();
  $album =
    get_sql_results(
		    $sqlQueryResult,
		    $sqlConnection,
      "select tagName, weight"." from AlbumTags"." where albumID={$id}".
		    " order by weight DESC limit 0, 6"
		    ); // gets the album from the database                                                                                        
  if($album)// If it exists find album with same tag                                                                                                                                  
    {foreach ($sqlQueryResult as $kk => $tag) {
	//var_dump($tag);
    // echo "<br>";
      $myq = "select albumID from AlbumTags where tagName ='{$tag['tagName']}' limit 0, 3";
      //  $myq = "select albumID"." from AlbumTags"." where tagName ='Jackson'";
      // echo "trying $myq<br>";
    $query= get_sql_results(
			    $results,
			    $sqlConnection,
                            $myq
			    );
    //    var_dump($results);
    // if ($query)
      //echo "is: <br>";
    // else
    // echo "is not true<br>";    
  }
      //    var_dump($results);                                                             
      $dups = array();
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

  });
