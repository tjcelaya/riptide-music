<?php
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
$app->get('/findtag/:tag', function($tag) use ($sqlConnection)
  {
    if(!isset($tag))
    { echo json_encode(array('err'=>'NO params'));
      return;
    }
    $tag = tagspaces($tag);
    $tag = sqlsanitize($tag);
    $queryDetails = array();
    
	$sqlSuccess = findTag($queryDetails, $tag, $sqlConnection);
    if ($sqlSuccess)
      echo json_encode($queryDetails);
    else
      echo json_encode(array('err'=>'SQLerr'));
});


// save new tag
$app->post('/savetag', function() use ($sqlConnection)
{
	if (!(isset($_POST["tagName"]) & isset($_POST["key"])) )
	{ echo json_encode(array('err'=>'NO params'));
	  return;
	}
	if (checkuserlevel($_POST) > 6) 
	{ $queryDetails = array();
	  $tagName = sqlsanitize($_POST["tagName"]);
	  $sqlExistingtag = isTag($tagName, $sqlConnection);
	  $result = array();
      if ($sqlExistingtag)
	  {  echo json_encode(array('err'=>'Tag already exists!'));
   	 	 return;
	  }
	    else 
	  { 
			$sqlSuccess = get_sql_results($result, $sqlConnection,
					"insert into Tags (tagName) ".
					"values ('$tagName')");
			if ($sqlSuccess)
			  echo json_encode(array('err'=>'Added'));
			else
			  echo json_encode(array('err'=>'Error Adding'));
			return;
	  }
	}
	else
	{ echo json_encode(array('err'=>'No privledges'));
	  return;
	}
});

// view genre
$app->get('/genre/:genreName', function($genreName) use ($sqlConnection)
  { 
    if(!isset($genreName))
    { echo json_encode(array('err'=>'NO params'));
      return;
    }
    $queryDetails = array();
	$sqlSuccess = getGenre($queryDetails, $genreName, $sqlConnection);
    if ($sqlSuccess)
      echo json_encode($queryDetails);
    else
      echo json_encode(array('err'=>'SQLerr'));
});


// save suggest new genre
$app->post('/newgenre', function() use ($sqlConnection)
{
	if (!(isset($_POST["genreName"]) & isset($_POST["description"]) & isset($_POST["uid"])) )
	{ echo json_encode(array('err'=>'NO params'));
	  return;
	}
	if (checkuserlevel($_POST) > 4) 
	{ $queryDetails = array();
	  $uid = intval($_POST["uid"]);
	  $genreName = sqlsanitize($_POST["genreName"]);
	  echo "changed:  ";
	  $params = array("genreName" => "$genreName", "uid" => $uid);
	  $sqlExistinggenre = getNewGenre($queryDetails, $params, $sqlConnection);
	  $result = array();
	  $gdesc = sqlsanitize($_POST["description"]);
	  echo "$sqlExistinggenre : [$result] : $genreName, $gdesc, $uid   ::   ";
      if ($sqlExistinggenre)
	  { echo "// replace existing genre";
			$sqlSuccess = get_sql_results($result, $sqlConnection,
			"update NewGenre set description='$gdesc' ".
			"where genreName = '$genreName' ". 
			"and uid = $uid");
			
			if ($sqlSuccess)
	  		  echo json_encode(array('err'=>'Updated'));
			else
	  		  echo json_encode(array('err'=>'Error Updating'));
			return;
	  }
	    else 
	  { echo "// post new genre";
			$sqlSuccess = get_sql_results($result, $sqlConnection,
					"insert into NewGenre (genreName, description, uid) ".
					"values ('$genreName','$gdesc',$uid)");
			if ($sqlSuccess)
			  echo json_encode(array('err'=>'Added'));
			else
			  echo json_encode(array('err'=>'Error Adding'));
			return;
	  }
	}
	else
	{ echo json_encode(array('err'=>'Bad user, album, or privledges'));
	  return;
	}
});

// view new genre
$app->get('/newgenre/:parameters', function($parameters) use ($sqlConnection)
{

});


// approve new genre
$app->post('/savegenre', function($parameters) use ($sqlConnection)
{

});

// replaces spaces and other non-characters with %
function tagspaces($t)
{
  $tolkiens = array(' ', '(', ')', '-', '+');
  $result = str_replace($tolkiens, '%', $t);
  return $result;
}

// escapes special characters such as quotes
function sqlsanitize($s)
{
  return $s;
}

function isTag($tag, $sqlConnection)
{ $result = array();
$sqlSuccess = get_sql_results($result, $sqlConnection,
		"select * from Tags ".
		"where tagName = '%$tag%'");
return $sqlSuccess;
}

function findTag(&$result,$tag,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Tags ".
			"where tagName like '$tag'");
	return $sqlSuccess;
}

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

function getGenre(&$result,$parameters,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Genres ".
			"where genreName = '{$parameters}'");
	return $sqlSuccess;
}

function getNewGenre(&$result,$parameters,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from NewGenre ".
			"where genreName = '{$parameters['genreName']}' ".
			"and uid = {$parameters['uid']}");
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
  return 7;
}
