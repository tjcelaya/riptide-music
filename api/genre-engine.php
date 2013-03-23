<?php


// view genre
$app->get('/genre/:genreName', function($genreName) use ($sqlConnection)
  { 
    if(!isset($genreName))
    { echo json_encode(array('err'=>'NO params'));
      return;
    }

    $queryDetails = array();
    $sqlGenreSuccess = getGenre($queryDetails, $genreName, $sqlConnection);

    if($sqlGenreSuccess)
      $sqlAlbumSuccess = getAlbums($queryDetails, $genreName, $sqlConnection);
    else
      echo json_encode(array('err'=>'SQLerr'));

    if($sqlAlbumSuccess)
      echo json_encode($queryDetails);
    else
      echo json_encode(array('err'=>'SQLerr'));


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


function getGenre(&$result,$parameters,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Genres ".
			"where genreName = '{$parameters}'");
	return $sqlSuccess;
}

function getAlbums(&$result, $parameters, $sqlConnection)
{
  $sqlSuccess = get_sql_results($result, $sqlConnection,
    "select * from GenreExamples ".
    "where genreName = '{$parameters}'");
  return $sqlSuccess;
}
