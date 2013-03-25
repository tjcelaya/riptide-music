<?php require "header.php"; ?>
<!-- div container -->
<div class="row-fluid inner-row-div">
  <div class='main-body span9 offset 1'>
    <h1>Search Results</h1>
    <?php
      if (isset($_GET["q"]))
      {

        $_GET['q'] = urldecode($_GET['q']);

        echo $internalSearchUrl =   
          "http://ww2.cs.fsu.edu/~celaya/".
          "riptideMusic/api/internalSearch/".urlencode($_GET['q']);

        $internalSearchResponse = 
            json_decode(file_get_contents($internalSearchUrl), true);

        foreach ($internalSearchResponse as $album) {
          foreach ($album as $k => $v) {
            $smarty->assign($k, $v);
          }
          $smarty->display('album-template.tpl');
        }

      } 
      else
      {
        echo "no request";
      }
    ?>

    <?php
      if (isset($_GET["q"]))
      {

        echo $apiUrl =   
          "http://ww2.cs.fsu.edu/~celaya/".
          "riptideMusic/api/dsearch/".urlencode($_GET['q']);

        $APIresponse = 
            json_decode(file_get_contents($apiUrl), true);
        
        });
        

        foreach ($APIresponse as $album) {
          foreach ($album as $kk => $vv) {
            $smarty->assign($kk, $vv);
          }
          $smarty->display('album-create.tpl');
        }

      } 
      else
      {
        echo "no request";
      }
    ?>
  </div>
</div>
<?php require "footer.php"; ?>
