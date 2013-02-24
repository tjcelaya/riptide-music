<?php require "header.php"; ?>
    <!-- div container -->
      <div class="row-fluid inner-row-div">
        <div class='main-body span7 offset2'>
          <?php
            if (isset($_GET["q"]))
            {
              echo "<pre><code>";
  
              $_GET['q'] = urldecode($_GET['q']);

              $APIrequestURL = 
                str_replace(
                  "%2F",
                  "/",
                  str_replace(
                    "search.php?q=", 
                    "api/d/",
                    "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
                    )
                  ).'/';

               echo "<BR>";
               print_r(
                $APIresponse = 
                            json_decode(
                              file_get_contents(
                                $APIrequestURL
                                  )
                              )
                          )
                ;
               echo "<BR>";

              // $smarty->assign('imgURL',"http://api.discogs.com/image/R-150-694551-1301387402.jpeg");
              // $smarty->assign('albumName',"TransDerp");
              // $smarty->assign('genre',"lame");
              // $smarty->assign('avgRating',"2");
              // $smarty->display('album-template.tpl');

              echo "</pre></code>" ;

            } 
            else
            {
              echo "no request";
            }
          ?>
        </div>
        <div class='main-body span2'>
          <?php
        
            for($i = 0; $i < count($APIresponse); $i++) {
              foreach ($APIresponse[$i] as $kk => $vv) {
                
                $smarty->assign($kk, $vv);
                // echo "<BR>";
                // var_dump($vv);
                // echo "<BR>";
                // echo ($kk).": ".gettype($vv)."<BR>";
              }
              $smarty->display('album-template.tpl');
            }

          ?>
        </div>
      </div>

<?php require "footer.php"; ?>
