<?php require "header.php"; ?>
<!-- div container -->
<div class="row-fluid inner-row-div">
  <div class='main-body span5 offset1'>
    <h1>Search Results</h1>
    <?php
      if (isset($_GET["q"]))
      {

        $_GET['q'] = urldecode($_GET['q']);

        echo $apiUrl =   
          "http://ww2.cs.fsu.edu/~celaya/".
          "riptideMusic/api/dsearch/".urlencode($_GET['q']);

        $APIresponse = 
            json_decode(file_get_contents($apiUrl), true);

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
