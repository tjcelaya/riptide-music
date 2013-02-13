<?php
require "header.php";

?>
<div class='inner-row-div row-fluid'>
    <div class='row-fluid'>
        <div class='main-body span6'>
            <p>Artist</p>
            <hr/>
            <h1><?php echo $_GET['name'] ?></h1>
        <?php
            if (isset($_GET['name']))
            {
                $apiUrl = 
                    "http://ww2.cs.fsu.edu/~celaya/".
                    "riptideMusic/api/albuminfo/".urlencode($_GET['name']);
                
                echo "<p>this page calls: \n".$apiUrl."</p>";

                $artistRequest = json_decode(file_get_contents($apiUrl));
                
                //no err was sent back in the res
                $smarty->assign('artistName', $_GET['name']);

                if(is_null($artistRequest->err))
                {
                    foreach ($artistRequest as $key => $anAlbum)
                    {
                        if ($key != 'tags') 
                        {
                            echo $key;
                            foreach ($anAlbum as $k => $v)
                            {   
                                $smarty->assign($k,$v);     
                                    // echo "<pre><code>\n";
                                    // var_dump($k.":\n".$v);
                                    // echo "</code></pre>\n";
                            }
                            $smarty->display('album-template.tpl');
                        }
                    }

                }
                else
                {
                    echo $artistRequest->err;
                }

            }

        ?>
        </div>
        <div class="main-body span6">
            <p>pointless</p>
            <?php

                echo "<pre><code>\n";
                    foreach ($artistRequest as $k => $v) {
                        if(is_int($k))
                            echo $v['tracklist'];
                    }
                    var_dump($artistRequest);
                echo "</code></pre>\n";
            ?>
        </div>
    </div>
</div>

</body>
</html>