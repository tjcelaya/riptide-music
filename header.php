<?php
header('Cache-Control: max-age=28800');
error_reporting(E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set('America/New_York'); 
// NOTE: Smarty has a capital 'S'
require_once('../../libsmarty/Smarty.class.php');
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

$smarty = new Smarty();

$smarty->template_dir = "templates";
$smarty->compile_dir = "templates/compiled";
$smarty->cache_dir = "templates/cached";
$smarty->config_dir = "smarty-config";
$smarty->error_unassigned = false;
$smarty->debugging = true;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Riptide Music</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, max-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="hook/hook.css" type="text/css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/login.js" type="text/javascript"></script>
    <!-- <link rel="stylesheet/less" href="css/style.less" type="text/css"> -->
    <link href="http://fonts.googleapis.com/css?family=Oxygen:700,300,400" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700italic' rel='stylesheet' type='text/css'>  
    <!-- // <script src="js/agility.js"></script> -->
    <!-- // <script src="js/less.js"></script> -->
    <style>
      body { 
        background: url("img/bg/<?php 
          echo array_rand(
              array_slice(
                  scandir(
                      dirname(__FILE__)."/img/bg"
                  )
                  ,2));
        ?>.jpg"); 
        background-size: cover;
        background-attachment: fixed;

      }
    </style>

  </head>
  
  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class='navbar-inner'>

        <div class="container">
          <!--Login dropdown begin-->

          <nav>
            <ul class= "login-nav">
             <?php if(isUSerLoggedIn()) { ?>
              
              <li id="login">
                <a id="login-trigger" href="#">
                  Profile <span>▼</span>
                </a>
                <div  class="profile-content" id="login-content" >
                 
                    
                    <fieldset id="actions">
                      <form action="account.php" method="get">
                      <input type="submit" id="profile-button" value="Profile">

                      </form>
                    </fieldset>

                    <fieldset id="actions">
                      <form action="account.php" method="get">
                      <input type="submit" id="profile-button" value="Friends">
                      </form>
                    </fieldset>

                    <fieldset id="actions">
                      <form action="user_settings.php" method="get">
                      <input type="submit" id="profile-button" value="Preferences">
                      </form>
                    </fieldset>
            
                </div>                     
              </li>



              <li id="signup">
                <a href="logout.php">Logout</a>
              </li>
             <?php } else { ?>
              <li id="login">
                <a id="login-trigger" href="#">
                  Log in <span>▼</span>
                </a>
                <div id="login-content">
                  <form action="login.php" method="post">
                    <fieldset id="inputs">
                      <input id="username" type="text" name="username" placeholder="Your username" required>   
                      <input id="password" type="password" name="password" placeholder="Password" required>
                    </fieldset>
                    <fieldset id="actions">
                      <input type="submit" id="submit" value="Log in">
                      <label><input type="checkbox" checked="checked"> Keep me signed in</label>
                    </fieldset>
                  </form>
                </div>                     
              </li>
              <li id="signup">
                <a href="/~celaya/riptideMusic/signup.php">Sign up</a>
              </li>
             <?php } ?>
            </ul>
          </nav>

        <!--Login dropdown end-->


          <a class="brand" href="/~celaya/riptideMusic/">RIPTIDE MUSIC</a>
          <div class="btn-group">
            <a class="btn btn-primary" href="#"><i class="icon-user icon-white"></i> User</a>
            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#"><i class="icon-pencil"></i> Edit</a></li>
              <li><a href="#"><i class="icon-trash"></i> Delete</a></li>
              <li><a href="#"><i class="icon-ban-circle"></i> Ban</a></li>
              <li class="divider"></li>
              <li><a href="#"><i class="i"></i> Make admin</a></li>
            </ul>
          </div>
          <span class="brand"><?php echo json_encode(isUserLoggedIn()); ?></span>
          <div class='pull-right'>
            <form class="form-search" action="/~celaya/riptideMusic/search.php" method='GET'>
              <div class="input-append">
                    <input 
                        tabindex='1' 
                        value='' 
                        name='q' 
                        type="text"
                        class="search-query"/>
                    <button tabindex='2' value='!' type="submit" class='btn'>
                      <i class="icon-search"></i>
                    </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid main-container">
