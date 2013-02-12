<?php
require "header.php";
?>
<div class='inner-row-div row-fluid'>
	<div class='row-fluid'>
		<div style='' class='main-body span6'>
		<?php
		if (isset($_GET['name']))
		{
			$apiUrl = 
				"http://ww2.cs.fsu.edu/~celaya/".
				"riptideMusic/api/albuminfo/".urlencode($_GET['name']);
			
			echo "<p>this page calls: \n".$apiUrl."</p>";

			$artistRequest = json_decode(file_get_contents($apiUrl));
			echo "<pre><code>\n";
			var_dump($artistRequest);
			echo "</code></pre>\n";
		}
		?>
		</div>
	</div>
</div>