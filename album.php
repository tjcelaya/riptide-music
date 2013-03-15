<?php require "header.php"; ?>
    <div class='inner-row-div row-fluid'>
        <div class='row-fluid'>
            <div class='main-body span5'>
                <p>Artist</p>
                <hr/>
                <h1><?php  ?></h1>
            <?php
                if (isset($_GET['id'])) {
                    // echo $_GET['id']."<BR>";

                    $apiUrl = 
                        "http://ww2.cs.fsu.edu/~celaya/".
                        "riptideMusic/api/i/".urlencode($_GET['id']);
                    
                    // echo "<p>this page calls: \n".$apiUrl."</p>";

                    $albumRequest = json_decode(file_get_contents($apiUrl), true);

                    if (isset($albumRequest['err']))
                        echo "retrieval error";
                    else {
                        foreach ($albumRequest as $k => $v)
                        {   
                            echo $k.": ".$v."<BR>";
                            if($k == 'tracklist') {
                                $tracksArray = array();
                                foreach (explode('|', $v) as $piece) {
                                    $tracksArray[] = explode('~', $piece);
                                }
                                $v = $tracksArray;
                            }
                            $smarty->assign($k,$v);     
                        }
                        $smarty->display('album-template.tpl');
                    }
                } else {
                    echo "noalbum";
                }   
            ?>
            <div class="review"></div>


            </div>
            <div class="main-body span7">
                <p> <pre><code><?php echo print_r($albumRequest); ?></code></pre> recommendations will go here</p>
            </div>
        </div>
    </div>
<?php require "footer.php"; ?>
