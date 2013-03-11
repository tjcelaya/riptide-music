<?php require "header.php"; ?>
    <!-- div container -->
      <div class="row-fluid inner-row-div">
        <div class='main-body span5 offset1'>
          <h1>Search Results</h1>
          <?php
            if (isset($_GET["q"]))
            {
  
              $_GET['q'] = urldecode($_GET['q']);

              echo $APIrequestURL = 
                str_replace(
                  "%2F",
                  "/",
                  str_replace(
                    "search.php?q=", 
                    "api/dget/",
                    "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
                    )
                  ).'/';

              echo "<pre><code>";
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
              echo "</pre></code>" ;
            } 
            else
            {
              echo "no request";
            }
          ?>
        </div>
        <div class='main-body span5'>
          <?php
        
            for($i = 0; $i < count($APIresponse); $i++) {
              foreach ($APIresponse[$i] as $kk => $vv) {
                
                $smarty->assign($kk, $vv);
                // echo "<BR>";
                // var_dump($vv);
                // echo "<BR>";
                // echo ($kk).": ".gettype($vv)."<BR>";
              }
              $smarty->display('album-create.tpl');
            }

          ?>
        </div>
      </div>

<?php require "footer.php"; ?>
