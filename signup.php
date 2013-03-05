<?php require "header.php";

//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: account.php"); die(); }

//Forms posted
if(!empty($_POST))
{
  $errors = array();
  $email = trim($_POST["email"]);
  $username = trim($_POST["username"]);
  $displayname = trim($_POST["displayname"]);
  $password = trim($_POST["password"]);
  $confirm_pass = trim($_POST["passwordc"]);
  $captcha = md5($_POST["captcha"]);
  
  
  if ($captcha != $_SESSION['captcha'])
  {
    $errors[] = lang("CAPTCHA_FAIL");
  }
  if(minMaxRange(5,25,$username))
  {
    $errors[] = lang("ACCOUNT_USER_CHAR_LIMIT",array(5,25));
  }
  if(!ctype_alnum($username)){
    $errors[] = lang("ACCOUNT_USER_INVALID_CHARACTERS");
  }
  if(minMaxRange(5,25,$displayname))
  {
    $errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
  }
  if(!ctype_alnum($displayname)){
    $errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
  }
  if(minMaxRange(8,50,$password) && minMaxRange(8,50,$confirm_pass))
  {
    $errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(8,50));
  }
  else if($password != $confirm_pass)
  {
    $errors[] = lang("ACCOUNT_PASS_MISMATCH");
  }
  if(!isValidEmail($email))
  {
    $errors[] = lang("ACCOUNT_INVALID_EMAIL");
  }
  //End data validation
  if(count($errors) == 0)
  { 
    //Construct a user object
    $user = new User($username,$displayname,$password,$email);
    
    //Checking this flag tells us whether there were any errors such as possible data duplication occured
    if(!$user->status)
    {
      if($user->username_taken) $errors[] = lang("ACCOUNT_USERNAME_IN_USE",array($username));
      if($user->displayname_taken) $errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
      if($user->email_taken)    $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));   
    }
    else
    {
      //Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
      if(!$user->userCakeAddUser())
      {
        if($user->mail_failure) $errors[] = lang("MAIL_ERROR");
        if($user->sql_failure)  $errors[] = lang("SQL_ERROR");
      }
    }
  }
  if(count($errors) == 0) {
    $successes[] = $user->success;
  }
}

?>
	<style type="text/css">
      body {
        background: url("img/bg/4.jpg");
        background-size: cover;
        background-attachment: fixed;
 
      }

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

          	<form name="newUser" action="signup.php" method="post">
  				   <div id='username' class='outerDiv'>
    					<label for="username">Username:</label>
    					<input type="text" name="username" required  />
             </div>

  				   <div id='displayname' class='outerDiv'>
    					<label for="displayname">Display name:</label>
    					<input type="text" name="displayname" required  />
             </div>

             <div id='password' class='outerDiv'>
              <label for="password">Password:</label>
              <input type="password" name="password" required />
             </div>

             <div id='passwordc' class='outerDiv'>
              <label for="passwordc">Password:</label>
              <input type="password" name="passwordc" required />
             </div>

             <div id='securitycode' class='outerDiv'>
              <label>Security Code:</label>
              <img src='models/captcha.php'>
              </p>
              <label>Enter Security Code:</label>
              <input name='captcha' type='text'>
              </p>
             </div>

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