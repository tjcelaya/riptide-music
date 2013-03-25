<?php require "header.php"; ?>
    <div class='inner-row-div row-fluid'>
        <div class='row-fluid'>
            <div class='main-body span7 offset1'>
                <p>Album</p>
                <hr/>
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
                            // echo $k.": ".$v."<BR>";
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
                <form>
                  <fieldset>
                    <legend>Review this Album</legend>
                    <textarea 
                        rows="7"
                        placeholder="Review this album..." ></textarea>
                    <br>
                    <button type="submit" class="btn pull-right">Submit</button>
                  </fieldset>
                </form>
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
