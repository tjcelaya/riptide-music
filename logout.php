<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

//Log the user out
if(isUserLoggedIn())
{
	$loggedInUser->userLogOut();
}

header("Location: http://ww2.cs.fsu.edu/~celaya/riptideMusic/");
die();
?>

