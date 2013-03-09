<?php
// 
// get album review test -- rick
$app->get('/review/:userID',
  function($userID) use ($sqlConnection)
  {
    if(!isset($userID))
    { echo json_encode(array('err'=>'NO params'));
      return;
    }
    $queryDetails = array();
	$sqlSuccess = getReviewByid($queryDetails, $userID, $sqlConnection);
    if ($sqlSuccess)
      echo json_encode($queryDetails);
    else
      echo json_encode(array('err'=>'SQLerr'));
});

// post a review
$app->post('/reviewp', function() use ($sqlConnection)
{  echo "testing post review...";
	if (!(isset($_POST["albumID"]) & isset($_POST["memName"]) & isset($_POST["key"])))
	{ echo json_encode(array('err'=>'NO params'));
	  return;
	}
//	if (checkuserlevel($_POST) > 4)
	if ((checkuserlevel($_POST) > 4) & 
		isvalidAlbum($_POST["albumID"],$sqlConnection) & 
	    isvalidUser($_POST["memName"],$sqlConnection) )	
	{ $resultid = array("memName" => "1", "albumID" => "1");
	  $resultid['memName'] = $_POST['memName'];
	  $resultid['albumID'] = $_POST['albumID'];
	  $queryDetails = array();
	  // !!needs to search by user id too!!
	  $sqlExistingreview = getReviewBymemid($queryDetails, $resultid, $sqlConnection);
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
	{ echo json_encode(array('err'=>'Bad user, album, or privledges'));
	  return;
	}
});

// get matching tags
$app->get('/findtag/:parameters+', function($parameters) use ($sqlConnection)
{

});


// save new tag
$app->get('/findtag/:parameters+', function($parameters) use ($sqlConnection)
{

});


// suggest new genre
$app->get('/findtag/:parameters+', function($parameters) use ($sqlConnection)
{

});


// save genre
$app->get('/findtag/:parameters+', function($parameters) use ($sqlConnection)
{

});


function isvalidAlbum($aid, $sqlConnection)
{ $result = array();
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Albums ".
			"where albumID = '$aid'");
	return $sqlSuccess;
}

function isvalidUser($uid, $sqlConnection)
{  $result = array();
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Members ".
			"where memName = '$uid'");
	return $sqlSuccess;
}


function editReview($param, $sqlConnection)
{
  $sqlSuccess = get_sql_results($result, $sqlConnection,
    "UPDATE Reviews SET review='{$param['review']}' ".
    "WHERE memName='{$param['memName']}' AND ".
    "albumID='{$param['albumID']}'");
  return $sqlSuccess;
}

function addReview($param, $sqlConnection)
{
  $sqlSuccese = get_sql_results($result, $sqlConnection,
    "INSERT INTO Reviews (memName, albumID, review) ".
    "VALUES ('{$param['memName']}','{$param['albumID']}','{$param['review']}')");
  return $sqlSuccess;
}

function getReviewByid(&$result,$parameters,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Reviews ".
			"where albumID = '{$parameters['albumID']}'");
	return $sqlSuccess;
}

function getReviewBymemid(&$result,$parameters,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Reviews ".
			"where albumID = '{$parameters['albumID']}' ".
			"AND memName = '{$parameters['memName']}'");
	return $sqlSuccess;
}

function searchReview(&$result,$parameters,$sqlConnection)
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

// uses $_POST data in $params
function checkuserlevel($params)
{ // verifies user is logged in and returns security level
  return 5;
}
