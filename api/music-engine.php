<?php
// 
// get album review test -- rick
$app->get('/review/:parameters+',
  function($parameters) use ($sqlConnection)
  {
    if(!isset($parameters[0]))
    { echo json_encode(array('err'=>'NO params'));
      return;
    }
    $queryDetails = array();
	$sqlSuccess = getReview($queryDetails, $parameters, $sqlConnection);
    if ($sqlSuccess)
      echo json_encode($queryDetails);
    else
      echo json_encode(array('err'=>'SQLerr'));
});

// post a review
$app->get('/reviewp/:parameters+', function($parameters) use ($sqlConnection)
{
	if (!isset($parameters[0]) & !isset($parameters[1]))
	{ echo json_encode(array('err'=>'NO params'));
	  return;
	}
	if (checkuserlevel() > 4)
	{ $resultid = array();
	  $sqlSuccess = getAlbumId($resultid, $parameters, $sqlConnection); 
	  if ($sqlSuccess)
	  { $queryDetails = array();
	    // !!needs to search by user id too!!
	  	$sqlExistingreview = getReviewByid($queryDetails, $resultid[0], $sqlConnection);
		if ($sqlExistingreview)
		{ // replace existing review
			echo json_encode(array('done'=>'Review Updated'));
			return;
		}
		else 
		{ // post new review
			echo json_encode(array('done'=>'Review Added'));
			return;
		}	  	
	  }
	  else
	  	{ echo json_encode(array('err'=>'Invalid Album'));
	  	return;
	  	}	
	}
	else
	{ echo json_encode(array('err'=>'No User Privledges'));
	  return;
	}
});

function getReviewByid(&$result,$parameters,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Reviews ".
			"where albumID = '{$parameters['albumID']}'");
	return $sqlSuccess;
}


function getReview(&$result,$parameters,$sqlConnection)
{
    $sqlSuccess = get_sql_results($result, $sqlConnection,
    "select R.* from Reviews R, Albums A, Artists B ".
    "where B.artistName = '{$parameters[0]}' ".
    "AND A.albumName = '{$parameters[1]}' ".
    "AND A.artistID = B.artistID ".
    "AND R.albumID = A.albumID");
	return $sqlSuccess;
}


function getAlbumId(&$result,$parameters,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select A.albumID from Reviews R, Albums A, Artists B ".
			"where B.artistName = '{$parameters[0]}' ".
			"AND A.albumName = '{$parameters[1]}' ".
			"AND A.artistID = B.artistID ");
	return $sqlSuccess;
}


function checkuserlevel()
{ // verifies user is logged in and returns security level
  return 5;
}
