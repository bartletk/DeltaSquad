<?php
include("top_header.php");
include("header.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
        <link style rel="stylesheet" type="text/css" href="css/navbar.css"></style>
</head>
<body>
<body>

<?php
/**
 * User has submitted form without errors and user's
 * account has been edited successfully.
 */
if(isset($_SESSION['useredit'])){
   unset($_SESSION['useredit']);
   
   echo "<h1>User Account Edit Success!</h1>";
   echo "<p><b>$session->username</b>, your account has been successfully updated. "
       ."<a href=\"main.php\">Main</a>.</p>";
}
else{
	/**
 * If user is not logged in, then do not display anything.
 * If user is logged in, then display the form to edit
 * account information, with the current email address
 * already in the field.
 */
if($session->logged_in){
	?>
<div class="card card1">
  <h5><strong>User Account Edit: <?php echo $session->username; ?></strong></h5>
 <div class="row row1">
  <form class="col s12" action="process.php" method="POST">
    <div class="row">
      <div class="input-field col s12">
        <input id="fname" name="name" type="text" class="validate" required="" aria-required="true" value="<?php
			if($form->value("name") == ""){
				echo $session->userinfo['name'];
			}else{
				echo $form->value("name");
			}
			?>">
        <label for="name">Name</label>
		<?php echo $form->error("name"); ?>
      </div>
      <div class="input-field col s12">
        <input id="email2" type="email" class="validate" required="" aria-required="true" value="<?php
			if($form->value("email") == ""){
				echo $session->userinfo['email'];
			}else{
				echo $form->value("email");
			}
			?>">
        <label for="email">Email</label>
		<?php echo $form->error("email"); ?>
      </div>
      <div class="input-field col s12">
        <input id="current-password" name="curpass" type="password" class="validate" required="" aria-required="true" value="<?php echo $form->value("curpass"); ?>">
        <label for="current-password">Current Password</label>
		<?php echo $form->error("curpass"); ?>
      </div>
      <div class="input-field col s12">
        <input id="new-password" name="newpass" type="password" class="validate" required="" aria-required="true" value="<?php echo $form->value("newpass"); ?>">
        <label for="new-password">New Password</label>
		<?php echo $form->error("newpass"); ?>
      </div>
     <input type="hidden" name="subedit" value="1" />
      <div class="input-field col s12">
        <button class="btn waves-effect waves-light" type="submit" name="action">Submit</button>
      </div>
    </div>	
  				</form>
			</div>
		</div>
		<?php

}
}

?>
	</body>
</html>
<?php
	include("footer.php");
?>