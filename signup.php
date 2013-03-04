<?php require "header.php"; ?>
    
	<style type="text/css">
			#signup input:not(:focus){
				opacity:0.6;
			}

			#signup  input:required{
			}

			#signup  input:valid {
				opacity:0.8;
			}		

			#signup input:focus:invalid{
				border:1px solid blue;
				background-color:#1322e7;
			}

	</style>





    <!-- div container -->
      <div class="row-fluid inner-row-div">
        <div class='main-body span5 offset1'>
          <h1>Join Riptide Music</h1>
          <h4>Insert credentials, receive bacon.</h4>

          <div class= "signup">

          	<form action="" method="post">
				   <div id='name' class='outerDiv'>
					<label for="name">Full name:</label>
					<input type="text" name="name" required  />

				   <div id='username' class='outerDiv'>
					<label for="number">Username:</label>
					<input type="text" name="username" required  />

				   <div id='password' class='outerDiv'>
					<label for="password">Password:</label>
					<input type="password" name="password" required />

				   <div id='email' class='outerDiv'>
					<label for="email">Email:</label>
					<input type="email" name="email" required />
					<br>
					<div class='message' id='emailDiv'> We'll send you a confirmation. </div>
				   </div>
				   <br>
				   <div id='submit' class='outerDiv'>
				        <input type="submit" value="Create my account" />
				   </div>
		 	</form>

          </div>


         </div>
      </div>

  <?php require "footer.php"; ?>