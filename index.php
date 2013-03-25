<?php require "header.php"; ?>
<!-- div container -->
<div class="row-fluid inner-row-div">
    <div class='main-body span10'>
        <h1>Latest Albums added</h1>
        <div class='big-list'>
        <?php

            echo $internalSearchUrl =   
              "http://ww2.cs.fsu.edu/~celaya/".
              "riptideMusic/api/getLatest/";

            $internalSearchResponse = 
                json_decode(file_get_contents($internalSearchUrl), true);

            foreach ($internalSearchResponse as $album) {
              foreach ($album as $k => $v) {
                $smarty->assign($k, $v);
              }
              $smarty->display('album-template.tpl');
            }
        ?>
        </div>
    </div>
    <style>
        .big-list {
            overflow: auto;
        }
        .full-album-listing {
            width: 150px;
            float: left;
            padding: 1em 2em;
            margin: 1em;
         }
        .full-album-listing  img {
            width: 100%;
        }


    </style>
  <script type='text/javascript' src='js/jq-masonry.js'></script>
  <script type='text/javascript'>
//    $(function(){
  //      $('.big-list').masonry({
    //        itemSelector: ".full-album-listing",
      //      columnWidth: 210,
        //    isAnimated: true
    //    });
  //  })
  </script>
</div>
<?php require "footer.php"; ?>

