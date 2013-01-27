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
          <form id="postFORM" method="post" action="slim.php/POSTnew">
            <label for="name">name?</label>
            <input type="text" autofocus="" required="" name="name" id="name">
            <label for="comment">comment?</label>
            <input type="text" required="" name="comment" id="comment">
            <br>
            <input id="SUBMIT" type="submit" value="put it there, man!" class="btn">
          </form>
        </div>
        <div class="span9" style='white-space: pre'>
        <?php
            $posts =
                json_decode(
                    file_get_contents(
                        'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'slim.php/GETposts'
                    )
                );

            foreach ($posts as $p) {
                foreach ($p as $k => $v) {
                    echo "<p>$k : $v</p>";
                }
                echo '<hr>';
            }
            
        ?>
        </div>
        
      </div>
    </div>
    <script src="js/bootstrap.js"></script>
    <script src="js/moment.js"></script>
  </body>
</html>
