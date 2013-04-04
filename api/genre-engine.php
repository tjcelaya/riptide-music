<?php

// view genre name & description
$app->get('/genre/:genreName', function($genreName) use ($sqlConnection)
  { 
    if(!isset($genreName))
    { echo json_encode(array('err'=>'No parameters'));
      return;
    }

    $queryDetails = array();
    $sqlGenreSuccess = getGenreInformation($queryDetails, $genreName, $sqlConnection);

    if($sqlGenreSuccess)
      echo json_encode($queryDetails);
    else
      echo json_encode(array('err'=>'Genre was not retrieved'));
      
});


// view album examples for genre
$app->get('/genre/albums/:genreName', function($genreName) use ($sqlConnection)
{
    if(!isset($genreName))
    {
      echo json_encode(array('err'=>'No paramters'));
      reutrn;
    }
  
    $queryDetails = array();
    $sqlAlbumSuccess = getAlbumExamplesByGenre($queryDetails, $genreName, $sqlConnection);
    
    if($sqlAlbumSuccess)
      echo json_encode($queryDetails);
    else
      echo json_encode(array('err'=>'Albums were not retrieved'));
});


// save new genre
$app->post('/newgenre', function() use ($sqlConnection)
{
	if (!(isset($_POST["genreName"]) & isset($_POST["description"])) )
	{ echo json_encode(array('err'=>'NO params'));
	  return;
	}
	  $queryDetails = array();
	  $uid = intval($_POST["uid"]);
	  $genreName = sqlsanitize($_POST["genreName"]);
	  $gdesc = sqlsanitize($_POST["description"]);
  	  $sqlSuccess = get_sql_results($result, $sqlConnection,
					"insert into Genres (genreName, description) ".
					"values ('$genreName','$gdesc')");
		if ($sqlSuccess)
		  echo json_encode(array('err'=>'Added'));
		else
		  echo json_encode(array('err'=>'Error Adding'));
		return; 
});


function getGenreInformation(&$result,$parameters,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Genres ".
			"where genreName = '{$parameters}'");
	return $sqlSuccess;
}

function getAlbumExamplesByGenre(&$result, $parameters, $sqlConnection)
{
  
  //First, find the ids of each qualifying album
  $idResult = array();

  $sqlSuccess = get_sql_results($idResult, $sqlConnection,
    		"select albumID from Albums ".
      		"where albumID in ".
        		"(select albumID from GenreExamples where ".
       			"genreName = '{$parameters}')");
  
  if(!$sqlSuccess)
  {
    printf("Error: %s\n", $sqlConnection->error);
    echo json_encode(array('err'=>'Cannot retrieve Album id'));
    return;
  }


  //Then, for each of these ids, return album info
  //Use a temporary array to store the returned album object
  //then push it onto the main $result array.
  $tempResult = array();  

  foreach($idResult as $value)
    foreach($value as $id)
    {
      $sqlSuccess = getAlbumById($tempResult, $sqlConnection, $id);
      array_push($result, $tempResult);
    }

    
  if(!$sqlSuccess)
  {
    echo json_encode(array('err'=>'Cannot retrieve Album info'));
    return;
  }

  return $sqlSuccess;
}

