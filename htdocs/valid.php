<?php
	include("include/session.php");
	global $database;
?>

<html>
<head>
	<title>Delta Squad Nursing Scheduler/Calendar</title>
</head>
<body>

<div>

<?php	
	/* 
	 * If the someone accesses this page without the correct variables
	 * passed, assume they are want to fill out a form asking for a 
	 * confirmation email.
	 */	
	if(!(isset($_GET['qs1']) && isset($_GET['qs2']))){
		?>
			<div>
				<h1>Send Confirmation Email</h1>
				<form action="process.php" method="POST">
					<p>Username: <input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>"><?php echo $form->error("user"); ?></p>
					<p>Password: <input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>"><?php echo $form->error("pass"); ?></p>
					<p><input type="hidden" name="subConfirm" value="1"><input type="submit" value="Send!"></p>
					<p><a href="main.php">Back to Main</a></p>
				</form>			</div>
		<?php
	}

	/* If the correct variables are passed, define and check them. */
	else{
	
		$v_username		=	$_GET['qs1'];
		$v_userid		=	$_GET['qs2'];
		$field			=	'valid';
				
		$q 				=	"SELECT userid from ".TBL_USERS." WHERE username='$v_username'";
		$query			=	$database->query($q) or die(mysql_error());
		$query			=	mysql_fetch_array($query);
		
		
		/* 
		 * if the userid associated with the passed username does not
		 * exactly equal the passed userid automatically redirect
		 * them to the main page.
		 */
		if(!($query['userid'] == $v_userid)){
			echo "confirmation failed, username and UIN do not match";
		}
		/* 
		 * If the userid's match go ahead and change the value in
		 * the valid field to 1, display a 'success' message, and
		 * redirect to main.php.
		 */
		else{
			
			$database->updateUserField($v_username, $field, '1') or die(mysql_error());
			
			echo $v_username."'s account has been successfully verified.  You can now <a href='main.php'>login</a>.";
			
		}
	}
?>
</div>
</body>
</html>