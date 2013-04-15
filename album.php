<?php require "header.php"; ?> 
    <div class='inner-row-div row-fluid'>
        <div class='row-fluid'>
            <div class='main-body span7 offset1'>
                <p>Album</p> 
                <hr>

                 
                
            <?php
                if (isset($_GET['id'])) {
                    // echo $_GET['id']."<BR>";

                    $smarty->assign('templatetype', 'album');

                    $apiUrl = 
                        "http://ww2.cs.fsu.edu/~celaya/".
                        "riptideMusic/api/albumByID/".urlencode($_GET['id']);
                    
                    // echo "<p>this page calls: \n".$apiUrl."</p>";

                    $albumRequest = json_decode(file_get_contents($apiUrl), true);

                    if (isset($albumRequest['err']))
                        echo "retrieval error";
                    else {
                        foreach ($albumRequest as $k => $v)
                        {   
           //                  echo $k.": ".$v."<BR>";
                            $smarty->assign($k,$v);     
                        }
                        $smarty->display('album-template.tpl');
                    }
                } else {
                    echo "noalbum";
                }   
            ?> 
            
            <?php if (isUSerLoggedIn()) {  

          $apiURL = "http://ww2.cs.fsu.edu/~celaya/".
                  "riptideMusic/api/rating/".
                  $_GET['id'] . "/" . $loggedInUser->user_id;

          $userrating = json_decode(file_get_contents($apiURL), true);
          $urating = $userrating[0]['rating'];
//          echo "rating $urating <br>"; 
//          var_dump($userrating);
          echo "<script>$('#albumstars').html('User Rating: $urating');</script>";		  
//		  $html->getElementById('albumstars')->value = $userrating['rating']; 
 ?>
 
            
            
            <div class="review-form">
                <!--<?php echo urlencode($_GET['id']) ?> -->

                <form method="post" action="./api/reviewp">
                <input type='hidden' name='key' value='1,kqed4017g' >
                <table>
                <tr>
                <td colspan="2" style="padding-bottom:8px; padding-top:10px; padding-left:25px;">
                <strong>Review this Album</strong>
                </td>
                </tr>
                <tr>
                <input type="hidden" name="userID" size="30" value="<?php echo $loggedInUser->user_id; ?>">
                <input type="hidden" name="albumID" size="30" value="<?php echo urlencode($_GET['id']) ?>">
                </tr>
                
                <tr>
                
                <td>
                <textarea name="review" cols="100" rows="10" wrap="virtual"></textarea>
                
                </td>
                </tr>
                <tr>
                <td style="padding-left:30px;">
                 <input type="submit" name="submit" value="Submit Review" >
                 </td> 
                </tr>
                
                </table>
                </form>
                    

            
                <form  method="post" action="./api/rate">
                <input type='hidden' name='key' value='1,kqed4017g' >
                <table>
                
                <input type="hidden" name="userID" size="30" value="<?php echo $loggedInUser->user_id; ?>">
                <input type="hidden" name="albumID" size="30" value="<?php echo urlencode($_GET['id']) ?>">
                

                <tr>
                
                <td colspan="2" style="padding-bottom:8px; padding-top:10px; padding-left:25px;">
                <input type="hidden" name="rating" size="10" value="" >
                 
                <input id= "ratesubmit" type="submit" name="submit" value="Rate" style="display: none;"  >
                </td>
                </tr>        </table>
                </form> 
                    
            </div>
            <?php } else { ?>
            <h2>you need an account to review things :(</h2>
            <?php } ?>

            <?php 
                if (isset($_GET['id'])) {

                    $smarty->assign('templatetype', 'review');
 
                    $apiUrl = 
                        "http://ww2.cs.fsu.edu/~celaya/".
                      "riptideMusic/api/review/".urlencode($_GET['id']);
                    
                                         

                    $aReview = json_decode(file_get_contents($apiUrl), true);
 
                    if (isset($aReview['err']))
                        echo "retrieval error";
                    else {
                        foreach ($aReview as $k => $v)
                        {   
							$vkey = array();  
                             $vkey = array_keys($v);
                            $smarty->assign($vkey[0],$v[$vkey[0]]);     
                            $smarty->assign($vkey[1],$v[$vkey[1]]);     
                            $smarty->assign($vkey[2],$v[$vkey[2]]);     
                            $smarty->display('review-template.tpl');
                        }
                    
                    }  
                } else { 
                    echo "noreview";
                }   
            ?>
            
            </div>

<?php

        $recBoxInfo = array();

        //If user is logged in...
        if(isUserLoggedIn())
        {
          $apiURL = "http://ww2.cs.fsu.edu/~celaya/".
                  "riptideMusic/api/recommendation/album/".
                  $_GET['id'] . "/" . $loggedInUser->user_id;

          $RecommendationRequest = json_decode(file_get_contents($apiURL), true);

          foreach($RecommendationRequest as $anAlbum)
            foreach($anAlbum as $key => $value)
                array_push($recBoxInfo, $value);

        }

      ?>

        </div>


<style type="text/css">

.clearfix { display: inline-block; }
#button1 {top: 0; left: 55px;}

h1 { font-family: Helvetica, Arial, Verdana, sans-serif; color: #444; font-weight: bold; font-size: 1.5em; line-height: 2.0em; }
h2 { font-family: Georgia, Tahoma, sans-serif; font-style: italic; font-size: 1.0em; letter-spacing: -0.04em; line-height: 1.8em; }


.head { background: #3eaef8; border: 1px solid #3e82a7; padding-left: 8px; width: 100%;}
.head h1 { color: #fafcfd; font-weight: bold; font-size: .9em; }

.boxy {  border: 1px ; border-top: 0px;  }
.boxy span { font-size: 1.2em; display: block; margin-bottom: 7px; }

.boxy .friendslist { display: block; margin-bottom: 15px; }
.boxy .friend { display: block; float: left; height: 80px; padding: 5px 5px 5px 4px; width: 95% }
.boxy .friend img { border: 1px solid #3eaef8; float: left;  padding: 2px; margin-right: 4px; }
.boxy .friend .friendly { position: relative; top: 16px; font-size: 1.0em; }

</style>
 

      <div class='span3 rec offset7' style="position:absolute; top:75px; right: 25px; float:right">

        <div class="head"><h1>Recommendations</h1></div>
        <div class="boxy">
          <br>

          <div class="friendslist clearfix">
            <div class="friend">
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
    </div>
<?php require "footer.php"; ?>
