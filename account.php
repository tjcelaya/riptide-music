<?php require_once("header.php"); ?>
      <div class="row-fluid inner-row-div">
        <div class='span7 main-body offset2'>
           <?php if(isUSerLoggedIn()) { ?>
           <h1>even</h1>
           <?php } else { ?>
           <h1>odd</h1>
           <?php } ?>


           <?php echo "Hey, $loggedInUser->displayname"; ?>
        </div>
      </div>
<?php require_once("footer.php"); ?>
