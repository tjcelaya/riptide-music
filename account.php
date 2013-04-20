<?php require_once("header.php"); ?>
      
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Profile</title>
</head>

<style type="text/css">

.clearfix { display: inline-block; }
#button1 {top: 0; left: 55px;}

a { color: #3c86b7; text-decoration: none; }
a:hover { text-decoration: underline; }

h1 { font-family: Helvetica, Arial, Verdana, sans-serif; color: #444; font-weight: bold; font-size: 1.5em; line-height: 2.0em; }
h2 { font-family: Georgia, Tahoma, sans-serif; font-style: italic; font-size: 1.0em; letter-spacing: -0.04em; line-height: 1.8em; }


#userStats { display: block; width: auto;  -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px; padding: 12px; }


#userStats .data h1 { color: #474747; line-height: 1.6em; text-shadow: 0px 1px 1px #fff; }
#userStats .data h2 { color: #666; line-height: 0.9em; margin-bottom: 5px; }

#userStats .pic { float: left; display: block; margin-right: 10px; }
#userStats .pic a img {  border: 1px solid #3eaef8; }

#userStats .data .sepBorderless { clear: both; margin-top: 40px; width: 320px; height: 1px; border-bottom: 0px solid #ccc; margin-bottom: 0; }
#userStats .data .sep { clear: both; margin-top: 40px; width: 320px; height: 1px; border-bottom: 1px solid #3eaef8; margin-bottom: 0; }
#userStats .data ul.numbers { list-style: none; width: 320px; padding-top: 7px; margin-top: 0;  color: #0296f8; }
#userStats .data ul.numbers li { width: 95px; float: left; display: block; padding-left: 8px; height: 50px; border-right: 1px dotted #3eaef8; text-transform: uppercase; }
#userStats .data ul.numbers li strong { color: #434343; display: block; font-size: 3.4em; line-height: 1.1em; font-weight: bold; }
.nobrdr { border: 0px !important; }


.head { background: #3eaef8; border: 1px solid #3e82a7; padding-left: 8px; width: 100%;}
.head h1 { color: #fafcfd; font-weight: bold; font-size: .9em; }

.boxy {  border: 1px ; border-top: 0px;  }
.boxy span { font-size: 1.2em; display: block; margin-bottom: 7px; }

.boxy .friendslist { display: block; margin-bottom: 15px; }
.boxy .friend { display: block; float: left; height: 40px; padding: 5px 5px 5px 4px; width: 95% }
.boxy .friend img { border: 1px solid #3eaef8; float: left;  padding: 2px; margin-right: 4px; }
.boxy .friend .friendly { position: relative; top: 16px; font-size: .7em; }





</style>





<div class="row-fluid inner-row-div">
  <div class='span6 main-body offset2'>
     <?php 

          $apiURL =
            "http://ww2.cs.fsu.edu/~celaya/".
            "riptideMusic/api/userStats/".$loggedInUser->user_id;

          $userStatsRequest = json_decode(file_get_contents($apiURL), true);
          
          foreach ($userStatsRequest as $k => $v) {
            $smarty->assign($k, $v);
          }

          // var_dump($userStatsRequest);

          $smarty->assign('accountName', $loggedInUser->username);
          $smarty->assign('displayName', $loggedInUser->displayname);
          $smarty->assign('friendCount', 0);

          $smarty->display('account-template.tpl');
     ?>
  </div>

      <?php

        $recBoxInfo = array();

        //If user is logged in...
        if(isUserLoggedIn())
        {
          $apiURL = "http://ww2.cs.fsu.edu/~celaya/".
                  "riptideMusic/api/recommendation/user/".
                  $loggedInUser->user_id;

          $RecommendationRequest = json_decode(file_get_contents($apiURL), true);

          foreach($RecommendationRequest as $anAlbum)
            foreach($anAlbum as $key => $value)
                array_push($recBoxInfo, $value);

        } 

      ?>
  
  
  
          <div class='span3 rec'>

            <div><h1>Recommendations</h1></div>
            <div>
                <br>
   
              <div>
                <div>
                  <img src="img/<?php echo $recBoxInfo[2];?> - <?php echo $recBoxInfo[1]; ?>(<?php echo $recBoxInfo[3]; ?>).jpg" width="60" height="60" alt="Friend" /></a><span class="friendly"><a href="album.php?id=<?php echo $recBoxInfo[0];?>"><?php echo $recBoxInfo[1]; ?></a></span>
                </div>

              <?php            for($i = 0; $i < 4; $i++)
                  array_shift($recBoxInfo);
              ?>

                <div class="friend">              <img src="img/<?php echo $recBoxInfo[2];?> - <?php echo $recBoxInfo[1]; ?>(<?php echo $recBoxInfo[3]; ?>).jpg" width="60" height="60" alt="Friend" /></a><span class="friendly"><a href="album.php?id=<?php echo $recBoxInfo[0];?>"><?php echo $recBoxInfo[1]; ?></a></span>
                </div>


              <?php            for($i = 0; $i < 4; $i++)
                  array_shift($recBoxInfo);
              ?>

                <div class="friend">              <img src="img/<?php echo $recBoxInfo[2];?> - <?php echo $recBoxInfo[1]; ?>(<?php echo $recBoxInfo[3]; ?>).jpg" width="60" height="60" alt="Friend" /></a><span class="friendly"><a href="album.php?id=<?php echo $recBoxInfo[0];?>"><?php echo $recBoxInfo[1]; ?></a></span>
                </div>

              <?php
                for($i = 0; $i < 4; $i++)
                  array_shift($recBoxInfo);          ?>
                <div class="friend">              <img src="img/<?php echo $recBoxInfo[2];?> - <?php echo $recBoxInfo[1]; ?>(<?php echo $recBoxInfo[3]; ?>).jpg" width="60" height="60" alt="Friend" /></a><span class="friendly"><a href="album.php?id=<?php echo $recBoxInfo[0];?>"><?php echo $recBoxInfo[1]; ?></a></span>
                </div>


              <?php
                for($i = 0; $i < 4; $i++)
                  array_shift($recBoxInfo);          ?>            <div class="friend">
                  <img src="img/<?php echo $recBoxInfo[2];?> - <?php echo $recBoxInfo[1]; ?>(<?php echo $recBoxInfo[3]; ?>).jpg" width="60" height="60" alt="Friend" /></a><span class="friendly"><a href="album.php?id=<?php echo $recBoxInfo[0];?>"><?php echo $recBoxInfo[1]; ?></a></span>
                </div>

              </div>

              <span><a href="#">See all...</a></span>
            </div>
 
  
</div>

</div>


       
<?php require_once("footer.php"); ?>
