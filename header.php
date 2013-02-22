<?php
header('Cache-Control: max-age=28800');
// header('Content-Encoding: gzip');
error_reporting(E_ALL);
ini_set("display_errors", 1);
// NOTE: Smarty has a capital 'S'
require_once('../../libsmarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = "templates";
$smarty->compile_dir = "templates/compiled";
$smarty->cache_dir = "templates/cached";
$smarty->config_dir = "smarty-config";
$smarty->error_unassigned = false;
$smarty->debugging = true;


?>
<html lang="en"><head>
  <head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
  <link rel="stylesheet" href="css/style.css" type="text/css">
  <!-- <link rel="stylesheet/less" href="css/style.less" type="text/css"> -->
  <link href="http://fonts.googleapis.com/css?family=Oxygen:700,300,400" rel="stylesheet">
  <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700italic' rel='stylesheet' type='text/css'>  
  <!-- // <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->
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
    <div class="navbar navbar-fixed-top">
        <div class="container">
          <a class="brand" href="/~celaya/riptideMusic/">riptide music</a>
          <div class='pull-right'>
            <form action="/~celaya/riptideMusic/search.php" method='GET'>
                <input 
                    tabindex='1' 
                    value='<?php if(isset($_GET['q'])) echo $_GET['q']; ?>' 
                    name='q' 
                    type="text"/>
                <button tabindex='2' value='!' type="submit" class='search-button'>
                  <i class="icon-search icon-white"></i>
                </button>
            </form>
          </div>
        </div>
    </div>
    <div class="container-fluid main-container">
