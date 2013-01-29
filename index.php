<?php
// NOTE: Smarty has a capital 'S'
require_once('../libsmarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = "templates";
$smarty->compile_dir = "templates/compiled";
$smarty->cache_dir = "templates/cached";
$smarty->config_dir = "smarty-config";

?>
<html lang="en"><head>
  <head>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/bootstrap-responsive.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oxygen:700,300,400">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/agility.js"></script>


  </head>
  
  <body>
    <div class="container-fluid">
      <div class="masthead">
        <ul class="nav nav-pills pull-right">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#">About</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
        <h3 class="muted">micropost-webapp-thing</h3>
      </div>
      <hr>
      <div class="row-fluid">
        <div class="span3">
          <form id="postFORM" method="post" action="api/POSTnew">
            <label for="name">name?</label>
            <input type="text" autofocus="" required="" name="name" id="name">
            <label for="comment">comment?</label>
            <input type="text" required="" name="comment" id="comment">
            <br>
            <input id="SUBMIT" type="submit" value="put it there, man!" class="btn">
          </form>
        
        </div>
        <div class="span9">
        <?php
          $getPostsURI = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'api/GETposts';
          // echo $getPostsURI;
          $posts = json_decode( file_get_contents($getPostsURI) );

          // var_dump($posts);
          foreach ($posts as $p) {

            // var_dump($p);
            $smarty->assign('name',$p->name);
            $smarty->assign('body',$p->body);
            $smarty->assign('timestamp',$p->created);

            $smarty->display('post-template.tpl');
          }
        ?>

        </div>
        
      </div>
    </div>
    <script src="js/bootstrap.js"></script>
    <script src="js/moment.js"></script>
  </body>
</html>
