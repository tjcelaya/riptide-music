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
                    "riptideMusic/api/albumsByArtist/".urlencode($_GET['name']);
                
                echo "<p>this page calls: \n".$apiUrl."</p>";

                $artistRequest = json_decode(file_get_contents($apiUrl), true);
                
                $smarty->assign('artistName', $artistRequest['artist']);
                unset($artistRequest['artist']);
                
                foreach ($artistRequest as $anAlbum)
                {
                    // $test = explode("|", $anAlbum['tracklist']);
                    foreach ($anAlbum as $k => $v)
                    {   
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
            }
        ?>
        </div>
        <div class="main-body span6">
            <p>pointless</p>
            <?php
                echo "<pre><code>\n";
                    var_dump($artistRequest);
                echo "</code></pre>\n";
            ?>
        </div>
    </div>
</div>

</body>
</html>