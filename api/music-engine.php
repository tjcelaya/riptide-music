<?php
// music-engine.php
// author: Rick Tilley
// date: 3/28/2013
// uses slim framework
// facilitates api calls to database 
// such as looking up reviews and tags,
// or updating/inserting data.
define("WEIGHTMIN", 0);
define("WEIGHTMAX", 10000);
require 'recommendationNOLOGIN-EDIT.php';
// get review by album ID
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
//	first make sure post data is there and valid
	if (!(isset($_POST["albumID"]) & isset($_POST["userID"]) & isset($_POST["key"])))
	{ echo json_encode(array('err'=>'NO params'));
	  return;
	}
	if ((checkuserlevel($_POST) > 4) & 
		isvalidAlbum($_POST["albumID"],$sqlConnection) & 
	    isvalidUser($_POST["userID"],$sqlConnection) )	
	{ $resultid = array("userID" => "1", "albumID" => "1", "review" => "1");
	  $resultid['userID'] = $_POST['userID'];
	  $resultid['albumID'] = $_POST['albumID'];
      $resultid['review'] = sqlsanitize($_POST['review']);
	  $queryDetails = array();
	  // search to see if review exists already and is being updated
	  $sqlExistingreview = getReviewBymemid($queryDetails, $resultid, $sqlConnection);
      if ($sqlExistingreview)
	  { // replace existing review
        $sqlSuccess = editReview($resultid, $sqlConnection);
		if ($sqlSuccess)
              echo json_encode(array('done'=>'Review Updated'));
	    else
    		  echo json_encode(array('err'=>'SQLerr'));
		return;
	  } 
	    else 
	  { // post new review
          $sqlSuccess = addReview($resultid, $sqlConnection);
		if ($sqlSuccess)
          echo json_encode(array('done'=>'Review Added'));
	    else
    		  echo json_encode(array('err'=>'SQLerr'));
		return;
	  }	  	
	}
	else
	{ echo json_encode(array('err'=>'Bad user, album, or privledges'));
	  return;
	}
});

// get rating by album ID and user ID
$app->get('/rating/:albumID/:userID',
		function($albumID, $userID) use ($sqlConnection)
		{
			if(!(isset($albumID) & isset($userID)) )
			{ echo json_encode(array('err'=>'NO params'));
			return;
			}
			$params = array('userID' => "{$userID}", 'albumID' => "{$albumID}");
			$queryDetails = array();
			$sqlSuccess = getRating($queryDetails, $params, $sqlConnection);
			if ($sqlSuccess)
				echo json_encode($queryDetails);
			else
				echo json_encode(array('err'=>'SQLerr'));
		});


$app->post('/rate', function() use ($sqlConnection, $app)
{ 
	if (!(isset($_POST["albumID"]) & isset($_POST["userID"]) & isset($_POST["key"]) & isset($_POST["rating"])) )
	{ echo json_encode(array('err'=>'NO params'));
	return;
	}
	if ((checkuserlevel($_POST) > 4) &
			isvalidAlbum($_POST["albumID"],$sqlConnection) &
			isvalidUser($_POST["userID"],$sqlConnection) )
	{ $resultid = array("userID" => "1", "albumID" => "1", "rating" => "1");
	$resultid['userID'] = $_POST['userID'];
	$resultid['albumID'] = $_POST['albumID'];
	$resultid['rating'] = $_POST['rating'];
	$queryDetails = array();
	// search to see if review exists already and is being updated
	$sqlExistingreview = getRating($queryDetails, $resultid, $sqlConnection);
	if ($sqlExistingreview)
	{ // replace existing review
	  $sqlSuccess = editRating($resultid, $sqlConnection);
	  if ($sqlSuccess)
		  $app->redirect($_SERVER['HTTP_REFERER']);
	  else
     	  echo json_encode(array('err'=>'SQLerr'));
	  return; 
	}
	else
	{ // post new review
	  $sqlSuccess = addRating($resultid, $sqlConnection);
	  if ($sqlSuccess)
	  	$app->redirect($_SERVER['HTTP_REFERER']);
	  else
    	echo json_encode(array('err'=>'SQLerr'));
	  return;
	}
	}
	else
	{ echo json_encode(array('err'=>'Bad user, album, or privledges'));
	return;
	}
	
}
);

// GET matching tags
// description:
// searches for a literal match, then 
// breaks tag search into individual words 
// and searches for each word invididually
// all searches including literal match allow for
// extraneous characters before or after the match
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
	// ensure post data is there, check validity of post via key
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
			  echo json_encode(array('done'=>'Added'));
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
	// ensure post data is there, and verify access level
	if (!(isset($_POST["tagName"]) & isset($_POST["albumID"]) & isset($_POST["weight"]) & isset($_POST["key"])) )
	{ echo json_encode(array('err'=>'NO params'));
	return;
	}
	if (checkuserlevel($_POST) > 6)
	{ // now check to make sure tag exists and weight is valid
	  $queryDetails = array();
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
	  	  $sqlSuccess = isTagged($tagged, $sqlConnection);
	  	  if ($sqlSuccess)
	  	  { // change weight on an existing album tag
 			$sqlSuccess = get_sql_results($result, $sqlConnection,
                "UPDATE AlbumTags SET weight=$lbs ".
    			"WHERE albumID=$aid AND ".
    			"tagName='$tagName'");
 			if ($sqlSuccess == FALSE)
 			  echo json_encode(array('err'=>'SQLerr'));
 			else
 			echo json_encode(array('done'=>'Album tagged updated'));
	  	  }
		  else
	  	  { // tag an album, new album tag
	  	  	$insertsql = "insert into AlbumTags (albumID, tagName, weight) ".
				         "values ($aid, '$tagName', $lbs)";
 			$sqlSuccess = get_sql_results($result, $sqlConnection, $insertsql);
 			if ($sqlSuccess == FALSE)
 			  echo json_encode(array('err'=>'SQLerr'));
 			else
 			  echo json_encode(array('done'=>'Album tagged'));
	  	  }

	    }
	  }
	}
	else
	{ echo json_encode(array('err'=>'No privledges'));
	}
	return;
});

// replaces spaces and other non-characters with wildcard %
function tagspaces($t)
{
  $tolkiens = array(' ', '(', ')', '-', '+');
  $result = str_replace($tolkiens, '%', $t);
  return $result;
} 
 
// escapes special characters such as quotes
// sql injection attacks
function sqlsanitize($sql)
{
	$trysan = str_replace("\\", "\\\\", $sql);
	$trysan = str_replace("\"", "\"\"", $trysan);
	$trysan = str_replace("'", "''", $trysan);
	return $trysan;
}
function sql_sanitize_total($sql)
{
	$trysan = str_replace("\\", "", $sql);
	$trysan = str_replace("\"", "", $trysan);
	$trysan = str_replace("'", "", $trysan);
	return $trysan;
}
// changes back to regular?
function sql_desanitize($sql)
{
	$trysan = str_replace("\\\\", "\\", $sql);
	$trysan = str_replace("\"\"", "\"", $trysan);
	$trysan = str_replace("''", "'", $trysan);
	return $trysan;
}

// tag searches usually have duplicates
// this function removes the duplicates
// pass by reference, modifies array $queryDetails
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

// returns true or false, if a tag exists
function isTag($tag, $sqlConnection)
{ $result = array();
$sqlSuccess = get_sql_results($result, $sqlConnection,
		"select * from Tags ".
		"where tagName = '$tag'");
return $sqlSuccess;
}

// checks to see if an AlbumTags already exists
// returns true or false
function isTagged($params, $sqlConnection)
{ $result = array();
$sqlrequest = "select tagName from AlbumTags ".
		      "where tagName = '{$params['tagName']}' ".
		      "AND albumID = {$params['albumID']}";
// echo "trying $sqlrequest<br>...<br>"; 
$sqlSuccess = get_sql_results($result, $sqlConnection, $sqlrequest);
return $sqlSuccess;
}

// similar to isTag, returns Tag data
// depreciated
function findTag(&$result,$tag,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Tags ".
			"where tagName like '$tag'");
	return $sqlSuccess;
}

// returns true or false if album ID is a valid one
function isvalidAlbum($aid, $sqlConnection)
{ $result = array();
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Albums ".
			"where albumID = '$aid'");
	return $sqlSuccess;
}

// returns true or false if user ID is a valid one
function isvalidUser($uid, $sqlConnection)
{  $result = array();
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from uc_users ".
			"where id = '$uid'");
	return $sqlSuccess;
}

// updates an existing review
function editReview($param, $sqlConnection)
{
  $uid = intval($param['userID']);
  $aid = intval($param['albumID']);
  $sqlSuccess = get_sql_results($result, $sqlConnection,
    "UPDATE Reviews SET review='{$param['review']}' ".
    "WHERE userID=$uid AND ".
    "albumID=$aid");
  return $sqlSuccess;
}

// creates a new review
function addReview($param, $sqlConnection)
{
  $uid = intval($param['userID']);
  $aid = intval($param['albumID']);
  $sqlSuccess = get_sql_results($result, $sqlConnection,
    "INSERT INTO Reviews (userID, albumID, review) ".
    "VALUES ($uid,$aid,'{$param['review']}')");
  return $sqlSuccess;
}

// updates an existing Rating
function editRating($param, $sqlConnection)
{
	$uid = intval($param['userID']);
	$aid = intval($param['albumID']);
	$rating = floatval($param['rating']);
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"UPDATE Rates SET rating=$rating ".
			"WHERE userID=$uid AND ".
			"albumID=$aid");
	return $sqlSuccess;
}

// creates a new Rating
function addRating($param, $sqlConnection)
{  
	$uid = intval($param['userID']);
	$aid = intval($param['albumID']);
	$rating = floatval($param['rating']);
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"INSERT INTO Rates (userID, albumID, rating) ".
			"VALUES ($uid,$aid,$rating)");
	return $sqlSuccess;
}


// retrieve all reviews of an album by albumID
function getReviewByid(&$result,$albumID,$sqlConnection)
{   
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select R.review, U.display_name, U.id from Reviews R, uc_users U ".
			"where R.albumID = {$albumID} ".
			"AND U.id = R.userID");
	return $sqlSuccess;  
}       

// retrieve a specific review of an album by albumID and userID
// returns false if none exists
function getReviewBymemid(&$result,$parameters,$sqlConnection)
{
	$qtry = "select review from Reviews ".
			"where albumID='{$parameters['albumID']}' ".
			"AND userID='{$parameters['userID']}'";
	 
	$sqlSuccess = get_sql_results($result, $sqlConnection, $qtry);
	return $sqlSuccess; 
}

// retrieve a specific rating of an album by albumID and userID
// returns false if none exists
function getRating(&$result,$parameters,$sqlConnection)
{
	$sqlSuccess = get_sql_results($result, $sqlConnection,
			"select * from Rates ".
			"where albumID = '{$parameters['albumID']}' ".
			"AND userID = '{$parameters['userID']}'");
	return $sqlSuccess;
}

// searches for a review by artist and album, returns any results
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

// returns albumID number by artist and album search
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
// not implemented yet, should check validity
// of post request either by userID or $_POST['key']
// returns an integer security level
function checkuserlevel($params)
{ // verifies user is logged in and returns security level
  
  return 7;
}
