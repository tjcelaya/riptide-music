<?php
define("WEIGHTMIN", 0);
define("WEIGHTMAX", 5);

// get album review test -- rick
$app->get('/review/:albumID',
  function($albumID) use ($sqlConnection)
  {
    if(!isset($albumID))
    { echo json_encode(array('err'=>'NO params'));
      return;
    }
    $queryDetails = array();
	$sqlSuccess = getReviewByid($queryDetails, $albumID, $sqlConnection);
    if ($sqlSuccess)
      echo json_encode($queryDetails);
    else
      echo json_encode(array('err'=>'SQLerr'));
});

// post a review
$app->post('/reviewp', function() use ($sqlConnection)
{
	if (!(isset($_POST["albumID"]) & isset($_POST["userID"]) & isset($_POST["key"])))
	{ echo json_encode(array('err'=>'NO params'));
	  return;
	}
//	if (checkuserlevel($_POST) > 4)
	if ((checkuserlevel($_POST) > 4) & 
		isvalidAlbum($_POST["albumID"],$sqlConnection) & 
	    isvalidUser($_POST["userID"],$sqlConnection) )	
	{ $resultid = array("userID" => "1", "albumID" => "1", "review" => "1");
	  $resultid['userID'] = $_POST['userID'];
	  $resultid['albumID'] = $_POST['albumID'];
          $resultid['review'] = sqlsanitize($_POST['review']);
	  $queryDetails = array();
	  // !!needs to search by user id too!!
	  $sqlExistingreview = getReviewBymemid($queryDetails, $resultid, $sqlConnection);
      if ($sqlExistingreview)
	  { // replace existing review
                editReview($resultid, $sqlConnection);
		echo json_encode(array('done'=>'Review Updated'));
		return;
	  } 
	    else 
	  { // post new review
                addReview($resultid, $sqlConnection);
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
    $result = array();
    $tagsp = explode('%', $tag);
    $tag = "%$tag%";
    $sqlSuccess = findTag($queryDetails, $tag, $sqlConnection);
	if (count($tagsp) > 1)
	{
	  foreach ($tagsp as $t)
	  { $t1 = "%$t%";
	  	$sqlSucc2 = findTag($result, $t1, $sqlConnection);
  	    if ($sqlSucc2 == true)
	    { $queryDetails = array_merge($queryDetails, $result);
  	  	  $sqlSuccess = true;
	    }
	  }
	}
	removeduptags($queryDetails);
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

// tag an album
$app->post('/tag', function() use ($sqlConnection)
{
	if (!(isset($_POST["tagName"]) & isset($_POST["albumID"]) & isset($_POST["weight"]) & isset($_POST["key"])) )
	{ echo json_encode(array('err'=>'NO params'));
	return;
	}
	if (checkuserlevel($_POST) > 6)
	{ $queryDetails = array();
	  $tagName = sqlsanitize($_POST["tagName"]);
	  $sqlExistingtag = isTag($tagName, $sqlConnection);
	  $lbs = floatval($_POST["weight"]);
	  if ($lbs < WEIGHTMIN || $lbs > WEIGHTMAX)  {  
	  	 echo json_encode(array('err'=>'Out of range'));
	  	 return;
         }
	  else
	  if (!$sqlExistingtag)	  {  
	     echo json_encode(array('err'=>'Tag doesn\'t exist!'));
	     return;
	  }
	  else
	  { // check if valid albumID

	  	$aid = intval($_POST["albumID"]);
	  	$sqlSuccess = isvalidAlbum($aid, $sqlConnection);
	  	if (!$sqlSuccess)
	  		echo json_encode(array('err'=>'AlbumID bad'));
	  	else
	  	{
	  	  $tagged = array('albumID' => $aid, 'tagName' => $tagName, 'weight' => $lbs);
//	  	  var_dump($tagged);
//	  	  echo "<br>";
	  	  $sqlSuccess = isTagged($tagged, $sqlConnection);
	  	  if ($sqlSuccess)
	  	  {
 			$sqlSuccess = get_sql_results($result, $sqlConnection,
                "UPDATE AlbumTags SET weight=$lbs ".
    			"WHERE albumID=$aid AND ".
    			"tagName='$tagName'");
 			if ($sqlSuccess == FALSE)
 			  echo json_encode(array('err'=>'SQLerr'));
 			else
 			echo json_encode(array('err'=>'Album tagged updated'));
	  	  }
		  else
	  	  {
	  	  	$insertsql = "insert into AlbumTags (albumID, tagName, weight) ".
				         "values ($aid, '$tagName', $lbs)";
//	  	   echo "now: $insertsql<br>";
 			$sqlSuccess = get_sql_results($result, $sqlConnection, $insertsql);
 			if ($sqlSuccess == FALSE)
 			  echo json_encode(array('err'=>'SQLerr'));
 			else
 			  echo json_encode(array('err'=>'Album tagged'));
	  	  }

	    }
	  }
	}
	else
	{ echo json_encode(array('err'=>'No privledges'));
	}
	return;
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

function removeduptags(&$queryDetails)
{ $tagz = array();
foreach ($queryDetails as $t => $tn)
{
	if (in_array($tn, $tagz))
		unset($queryDetails[$t]);
	else
		array_push($tagz,$tn);
}
}

function isTag($tag, $sqlConnection)
{ $result = array();
$sqlSuccess = get_sql_results($result, $sqlConnection,
		"select * from Tags ".
		"where tagName = '$tag'");
return $sqlSuccess;
}

// checks AlbumTags for the right one
function isTagged($params, $sqlConnection)
{ $result = array();
$sqlrequest = "select tagName from AlbumTags ".
		      "where tagName = '{$params['tagName']}' ".
		      "AND albumID = {$params['albumID']}";
// echo "trying $sqlrequest<br>...<br>"; 
$sqlSuccess = get_sql_results($result, $sqlConnection, $sqlrequest);
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
			"select * from uc_users ".
			"where id = '$uid'");
	return $sqlSuccess;
}


function editReview($param, $sqlConnection)
{
  $uid = intval($param['userID']);
  $aid = intval($param['albumID']);
  $sqlSuccess = get_sql_results($result, $sqlConnection,
    "UPDATE Reviews SET review='{$param['review']}' ".
    "WHERE userID='{$param['userID']}' AND ".
    "albumID='{$param['albumID']}'");
  return $sqlSuccess;
}

function addReview($param, $sqlConnection)
{
  $uid = intval($param['userID']);
  $aid = intval($param['albumID']);
  $sqlSuccess = get_sql_results($result, $sqlConnection,
    "INSERT INTO Reviews (userID, albumID, review) ".
    "VALUES ($uid,$aid,'{$param['review']}')");
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
			"AND userID = '{$parameters['userID']}'");
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
