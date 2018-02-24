<?php
include("header.php");
$page = "login.php";
?>
<div>
<h1>Login</h1>
<?php
/**
 * User not logged in, display the login form.
 * If user has already tried to login, but errors were
 * found, display the total number of errors.
 * If errors occurred, they will be displayed.
 */
if($form->num_errors > 0){
   echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
}
?>

	<form action="process.php" method="POST">
		<p>Username: </p><p><input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>"><?php echo $form->error("user"); ?></p>
		<p>Password: </p><p><input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>"><?php echo $form->error("pass"); ?></p>
		<p>
			<input type="checkbox" name="remember" <?php if($form->value("remember") != ""){ echo "checked"; } ?>>Remember me next time
			<input type="hidden" name="sublogin" value="1">
			<input type="submit" value="Login">
		</p>
	</form>
</div>