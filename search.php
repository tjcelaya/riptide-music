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
          if(is_array($album)) {
            foreach ($album as $k => $v) {
              $smarty->assign($k, $v);
            }
            $smarty->display('album-template.tpl');
          }
        }

      } 
      else
      {
        echo "no request";
      }
      //output internal results immediately before 
      //beginning slower discogs search
      flush();
    ?>
    <hr>
    <?php
    require 'discogSearch.php';
    ?>
  </div>
</div>
<?php require "footer.php"; ?>
