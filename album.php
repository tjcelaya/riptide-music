<?php require "header.php"; ?>
    <div class='inner-row-div row-fluid'>
        <div class='row-fluid'>
            <div class='main-body span5 offset2'>
                <p>Album</p>
                <hr/>
            <?php
                if (isset($_GET['id'])) {
                    // echo $_GET['id']."<BR>";

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


            </div>
<!--<div class="main-body span7">
                <p> <pre><code><?php echo print_r($albumRequest); ?></code></pre> recommendations will go here</p>
            </div> -->
        </div>
    </div>
<?php require "footer.php"; ?>
