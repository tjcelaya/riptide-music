<?php require "header.php"; ?>
<div class='inner-row-div row-fluid'>
    <div class='row-fluid'>
        <div class='main-body span8 offset1'>
	<p>Genre</p>
	<hr/>
	<h1><?php echo $_GET['name'] ?></h1>
	<?php
	  if(isset($_GET['name']))
	  {
	    $apiURL =
		"http://ww2.cs.fsu.edu/~celaya/".
		"riptideMusic/api/genre/".urlencode($_GET['name']);

	    $genreRequest = json_decode(file_get_contents($apiURL), true);
   //USE GENRE REQUEST OBJECT WITH GENRE SMARTY TEMPLATE HERE

         }  
	?>


	<?php
	  if(isset($_GET['name']))
	  {
	    $apiURL =
                "http://ww2.cs.fsu.edu/~celaya/".
                "riptideMusic/api/genre/albums/".urlencode($_GET['name']);

            $genreAlbumsRequest = json_decode(file_get_contents($apiURL), true);


	    foreach($genreAlbumsRequest as $anAlbum)
	    {
	      foreach($anAlbum as $k => $v)
	      {
		$smarty->assign($k,$v);
	      }
		$smarty->display('album-template.tpl');
	    }
          }
	?>

	</div>
    </div>
</div>
<?php require "footer.php"; ?>
