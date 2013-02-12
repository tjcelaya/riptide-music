<?php require "header.php"; ?>
      <div class="row-fluid inner-row-div">
          <?php
            
            $returnedObject = array(
              array(
                'albumName' => 'Thriller',
                'imageSrc' => "http://upload.wikimedia.org/wikipedia/en/thumb/5/51/Michaeljacksonthrilleralbum.jpg/220px-Michaeljacksonthrilleralbum.jpg",
                'artistName' => 'Michael Jackson',
                'year' => '1982',
                'genre' => 'Pop',
                'avgRating' => 4.5,
                'tracks' => array(
                    array('name'=>"Wanna be Startin' Somethin",
                    'duration'=>"6:02"),
                    array('name'=>"Baby Be Mine",
                    'duration'=>"4:20")
                ),
                'tags' => array(
                    'undead',
                    'riveting',
                    'classic'
                )
              ),
              array(
                'albumName' => 'Californication',
                'imageSrc' => "http://upload.wikimedia.org/wikipedia/en/d/df/RedHotChiliPeppersCalifornication.jpg",
                'artistName' => 'Red Hot Chili Peppers',
                'year' => '1999',
                'genre' => 'Rock',
                'avgRating' => 4,
                'tracks' => array(
                    array('name'=>"Around the World",
                    'duration'=>"3:58"),
                    array('name'=>"Parallel Universe",
                    'duration'=>"4:30"),
                    array('name'=>"Scar Tissue",
                    'duration'=>"3:35"),
                    array('name'=>"Otherside",
                    'duration'=>"4:15"),
                    array('name'=>"Get on Top",
                    'duration'=>"3:18"),
                ),
                'tags' => array(
                    'awesome',
                    'funky',
                    'rock'
                )
              )
            );

              // echo '<code style="white-space:pre;">';
              // var_dump($returnedObject);
              // echo '</code>';

          ?>
        <div class='span2'></div>
        <div class='span7 main-body'>
          <?php
            foreach ($returnedObject as $key => $anAlbum) {
              foreach ($anAlbum as $k => $v)
              {   $smarty->assign($k,$v);  }
              $smarty->display('album-template.tpl');
            }


          ?>
        </div>
        <div class='span2 rec'>
          <p>recommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommend</p>
          <p>recommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommend</p>
          <p>recommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommend</p>
          <p>recommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommend</p>
          <p>recommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommend</p>
          <p>recommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommend</p>
          <p>recommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommendrecommend</p>
        </div>
      </div>
    </div>
    <script src="js/bootstrap.js"></script>
    <script src="js/moment.js"></script>
  </body>
</html>
