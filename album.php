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
                             echo $k.": ".$v."<BR>";
                            $smarty->assign($k,$v);     
                        }
                        $smarty->display('album-template.tpl');
                    }
                } else {
                    echo "noalbum";
                }   
            ?>
            <?php if (isUSerLoggedIn()) { ?>
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
                                <?php 
                if (isset($_GET['id'])) {
                     echo $_GET['id']."<BR>";

                    $smarty->assign('templatetype', 'review');
 
                    $apiUrl = 
                        "http://ww2.cs.fsu.edu/~celaya/".
                      "riptideMusic/api/review/".urlencode($_GET['id']);
                    
                    
                                         
                     echo "<p>this page calls: \n".$apiUrl."</p>";

                    $aReview = json_decode(file_get_contents($apiUrl), true);
 
                    if (isset($aReview['err']))
                        echo "retrieval error";
                    else {
                        foreach ($aReview as $k => $v)
                        {   
//                             echo $k.": ";
//                             var_dump($v);
//                             echo "<BR>";
							$vkey = array();  
                             $vkey = array_keys($v);
//                            foreach ($vkey as $vk=>$vesa)  
//                            {
//                            	$smarty->assign($vk,$vesa]); 
//                            }      
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
            <?php } else { ?>
            <h2>you need an account to review things :(</h2>
            <?php } ?>

            </div>
<!--<div class="main-body span7">
                <p> <pre><code><?php echo print_r($albumRequest); ?></code></pre> recommendations will go here</p>
            </div> -->
        </div>
    </div>
<?php require "footer.php"; ?>
