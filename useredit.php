<?php

include("header.php");
$page = "useredit.php";
?>
<div>
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
?>

<?php
/**
 * If user is not logged in, then do not display anything.
 * If user is logged in, then display the form to edit
 * account information, with the current email address
 * already in the field.
 */
if($session->logged_in){
?>

<h1>User Account Edit : <?php echo $session->username; ?></h1>
<?php
if($form->num_errors > 0){
   echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></td>";
}
?>
<div>
	<form action="process.php" method="POST">
		<p>Name: </p>
		<p>
			<input type="text" name="name" maxlength="50" value="<?php
			if($form->value("name") == ""){
				echo $session->userinfo['name'];
			}else{
				echo $form->value("name");
			}
			?>">
			<?php echo $form->error("name"); ?>
		</p>
		<div></div>
		<p>Current Password: </p>
		<p>
			<input type="password" name="curpass" maxlength="30" value="<?php echo $form->value("curpass"); ?>">
			<?php echo $form->error("curpass"); ?>
		</p>
		<div></div>
		<p>New Password: </p>
		<p>
			<input type="password" name="newpass" maxlength="30" value="<?php echo $form->value("newpass"); ?>">
			<?php echo $form->error("newpass"); ?>
		</p>
		<div></div>
		<p>Email: </p>
		<p>
			<input type="text" name="email" maxlength="50" value="<?php
			if($form->value("email") == ""){
				echo $session->userinfo['email'];
			}else{
				echo $form->value("email");
			}
			?>">
			<?php echo $form->error("email"); ?>
		</p>
		<div></div>
		<p>
			<input type="hidden" name="subedit" value="1" />
			<input type="submit" value="Edit Account" />
		</p>
	</form>
</div>
<?php

}
}

?>
</div>
</body>
</html>
