<?php
  //Recommendation for a non logged in user based off an albums page                                                                
$app->get('/recommendation/album/:id', function($id) use ($sqlConnection) {//uses slim framework                                  

    $sqlQueryResult = array();

  $album =
    get_sql_results(
		    $sqlQueryResult,
		    $sqlConnection,
      "select tagName, weight"." from AlbumTags"." where albumID=$id".
		    " group by weight limit 0, 6"
		    ); // gets the album from the database                                                                                        

  $results= array(); // creates a place for results                                                                                

  if($album)// If it exists find album with same tag                                                                             
    {foreach $sqlQueryResult as $kk => $tag) {
    $query= get_sql_results(
			    $results,
			    $sqlConnection,
        "select albumID from AlbumTags where tagName =$tag limit 0, 3"

			    );

    if(!$query){ //if no result                                                                                                       
      echo json_encode(array('err'=>'no RESULTS'));
      return;}

    echo json_encode($results)
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
