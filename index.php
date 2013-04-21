<?php require "header.php"; ?>
<!-- div container -->
<div class="row-fluid inner-row-div">
    <div class='main-body span8 offset2'>
        <h1>Latest Albums added</h1>
        <div class='big-list'>
        <?php
            $internalSearchUrl =   
              "http://ww2.cs.fsu.edu/~celaya/".
              "riptideMusic/api/getLatest/";

            $internalSearchResponse = 
                json_decode(file_get_contents($internalSearchUrl), true);
            $smarty->assign('templatetype', 'index');
            
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
  <script type='text/javascript'>
    $(function(){
        console.log($('.full-album-listing').height());

          $('.big-list').masonry({
            itemSelector: ".full-album-listing",
            columnWidth: 40,
            isAnimated: true
          });

          var home = 'index.php';
          history.pushState({html:home}, null, home);
    })
  </script>
</div>
<?php require "footer.php"; ?>

