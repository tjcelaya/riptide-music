    <?php
      if (isset($_GET["q"]))
      {

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
    
