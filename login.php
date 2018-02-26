<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="css/form.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>
</body>
</html>

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
     <div class="wrapper">       
	<form class="form-signin" action="process.php" method="POST">
		  <h2 class="form-signin-heading">Please login</h2>

		<!Field Username>
		<p>Username: </p>
		<p>
			<input class="form-control" type="text" required="" name="user" placeholder="Username" maxlength="30" value="<?php echo $form->value("user"); ?>"><?php echo $form->error("user"); ?>
		</p>
		

		<!Field Passowrod>
		<p>Password: </p>
		<p>
			<input class="form-control" type="password" name="pass" placeholder="Password" required="" maxlength="30" value="<?php echo $form->value("pass"); ?>"><?php echo $form->error("pass"); ?></p>
		
		<!Field Remember me checkbox>
		<p>
			<label class="checkbox">
				<input type="checkbox" name="remember" 
					<?php if($form->value("remember") != ""){ echo "checked"; } ?>>
				Remember me next time
			</label>

			<input type="hidden" name="sublogin" value="1">
		 <button class="btn btn-lg btn-primary btn-block" value="Login" type="submit">Login</button>  
		</p>
	</form>
	</div>