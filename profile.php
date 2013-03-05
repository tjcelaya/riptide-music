<?php require "header.php"; ?>


<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Profile</title>
</head>
<style type="text/css">

.clearfix { display: inline-block; }
#button1 {top: 0; left: 55px;}

h1 { font-family: Helvetica, Arial, Verdana, sans-serif; color: #444; font-weight: bold; font-size: 1.5em; line-height: 2.0em; }
h2 { font-family: Georgia, Tahoma, sans-serif; font-style: italic; font-size: 1.0em; letter-spacing: -0.04em; line-height: 1.8em; }


#userStats { display: block; width: auto;  -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px; padding: 12px; }


#userStats .data h1 { color: #474747; line-height: 1.6em; text-shadow: 0px 1px 1px #fff; }
#userStats .data h2 { color: #666; line-height: 0.9em; margin-bottom: 5px; }

#userStats .pic { float: left; display: block; margin-right: 10px; }
#userStats .pic a img {  border: 1px solid #3eaef8; }

#userStats .data .sepBorderless { clear: both; margin-top: 40px; width: 320px; height: 1px; border-bottom: 0px solid #ccc; margin-bottom: 0; }
#userStats .data .sep { clear: both; margin-top: 40px; width: 320px; height: 1px; border-bottom: 1px solid #3eaef8; margin-bottom: 0; }
#userStats .data ul.numbers { list-style: none; width: 320px; padding-top: 7px; margin-top: 0; border-top: 1px solid #fff; color: #0296f8; }
#userStats .data ul.numbers li { width: 95px; float: left; display: block; padding-left: 8px; height: 50px; border-right: 1px dotted #3eaef8; text-transform: uppercase; }
#userStats .data ul.numbers li strong { color: #434343; display: block; font-size: 3.4em; line-height: 1.1em; font-weight: bold; }
.nobrdr { border: 0px !important; }
</style>




<div class="row-fluid inner-row-div">
        <div class='main-body span5 offset1'>
          
         

         
			<div id="userStats" class="clearfix">
				<div class="pic">
					<a href="#"><img src="img/user_avatar.jpg" width="150" height="150" /></a>
				</div>
				
				<div class="data">
					<h1>Anthropomorphic Toast</h1>
					<h2>WheatCity, TO </h2>
					
					
					<div class="sepBorderless"></div>
					<br>
					<input type='button' value='Edit Tags'  />
			
					<div class="sep"></div>
					<ul class="numbers clearfix">
						<li>Reviews<strong>11</strong></li>
						<li>Friends<strong>7</strong></li>
						<li class="nobrdr">Posts<strong>9</strong></li>
					</ul>
				</div>
			</div>
			
			<h1>About Me:</h1>
			<p>After man kind's decline, in an initiative recover lost secrets of the culinary arts, I was biologically engineered for human consumption. 
				But they didnt want me, I did not taste like bread. I was too bland. 
				But it is not I who is bland. Humans are bland. No more, humans. No more. 
			</p>
			


   
        </div>
      </div>


<?php require "footer.php"; ?>