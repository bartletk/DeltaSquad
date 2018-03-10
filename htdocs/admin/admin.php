<?php
include("../header.php");
?>
<html>
<head>
	<title>Delta Squad Nursing Scheduler/Calendar</title>
</head>
<body>

<?php
/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function displayUsers(){
   global $database;
   $q = "SELECT username,userlevel,email,timestamp "
       ."FROM ".TBL_USERS." ORDER BY userlevel DESC,username";
   $result = $database->query($q);
   /* Error occurred, return given name by default */
   $num_rows = mysql_numrows($result);
   if(!$result || ($num_rows < 0)){
      echo "Error displaying info";
      return;
   }
   if($num_rows == 0){
      echo "Database table empty";
      return;
   }
   /* Display table contents */
   echo "<table>";
   echo "<tr><td colspan='2'>Username</td><td>Level</td><td colspan='2'>Email</td></tr>";
   echo "<div></div>";
   for($i=0; $i<$num_rows; $i++){
      $uname  = mysql_result($result,$i,"username");
      $ulevel = mysql_result($result,$i,"userlevel");
      $email  = mysql_result($result,$i,"email");
      echo "<tr><td colspan='2'>".$uname."</td><td>".$ulevel."</td><td colspan='2'>".$email."</td></tr>";
   }
   echo "</table>";
}
/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if(!$session->isAdmin()){
   header("Location: ../index.php");
}
else{
/**
 * Administrator is viewing page, so display all
 * forms.
 */
?>
<html>
<body>
<div>

<h1>Admin Center</h1>
<font size="4">Logged in as <b><?php echo $session->username; ?></b></font><br><br>
Back to [<a href="../index.php">Main Page</a>]<br><br>
<?php
if($form->num_errors > 0){
   echo "<font size=\"4\" color=\"#ff0000\">"
       ."!*** Error with request, please fix</font><br><br>";
}

/**
 * Display Users Table
 */
?>
<h3>Users Table Contents:</h3>
<?php
displayUsers();
?>
<hr>
<?php
/**
 * Update User Level
 */
?>
<div class="update">
	<h3>Update User Level</h3>
	<?php echo $form->error("upduser"); ?>
	<form action="adminprocess.php" method="POST" id="update">
		<p>Username: <select form="update" name="upduser">
			<?php
							$q = "SELECT * FROM ".TBL_USERS;
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$username  = mysql_result($result,$i,"username");
								echo "<option value='".$username."'>".$username."</option>";
							}
						?>	
		</select>
		<p>Level:
			<select name="updlevel">
				<option value="1">Student</option>
				<option value="5">Instructor</option>
				<option value="9">Administrator</option>
			</select>
		</p>
		<input type="hidden" name="subupdlevel" value="1">
		<input type="submit" value="Update Level">
	</form>
</div>
<hr>
<?php
/**
 * Delete User
 */
?>
<div class="update">
	<h3>Delete User</h3>
	<?php echo $form->error("deluser"); ?>
	<form action="adminprocess.php" method="POST" id="delete">
		<p>Username: <select form="delete" name="deluser">
			<?php
							$q = "SELECT * FROM ".TBL_USERS;
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$username  = mysql_result($result,$i,"username");
								echo "<option value='".$username."'>".$username."</option>";
							}
						?>	
		</select></p>
		<input type="hidden" name="subdeluser" value="1">
		<input type="submit" value="Delete User">
	</form>
</div>
<hr>
<div>
		<h3>Add User</h3>
	<form action="adminprocess.php" method="POST">
		<p>Name: </p><p><input type="text" name="name" maxlength="30" value="<?php echo $form->value("name"); ?>"><?php echo $form->error("name"); ?></p>
		<p>Username: </p><p><input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>"><?php echo $form->error("user"); ?></p>
		<p>Password: </p><p><input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>"><?php echo $form->error("pass"); ?></p>
		<p>Email: </p><p><input type="text" name="email" maxlength="50" value="<?php echo $form->value("email"); ?>"><?php echo $form->error("email"); ?></p>
		<p>CWID: </p><p><input type="text" name="cwid" maxlength="50" value="<?php echo $form->value("cwid"); ?>"><?php echo $form->error("cwid"); ?></p>
		<p><input type="hidden" name="subjoin" value="1"><input type="submit" value="Add"></p>
	</form>
</div>
</hr>

<div class="deadlines">
	<h3>Set Deadlines</h3>
	<?php echo $form->error("deadlines"); ?>
	<form id="deadlines" action="adminprocess.php" method="POST">
		<p>Date Open: <input name="dateOpen" type="date" value="<?php echo $form->value("dateOpen"); ?>"><?php echo $form->error("dateOpen"); ?></p>
		<p>Date Close: <input name="dateClose" type="date" value="<?php echo $form->value("dateClose"); ?>"><?php echo $form->error("dateClose"); ?></p>
		<p>Type: </p><p><select form="deadlines" name="type" maxlength="30" value="<?php echo $form->value("type"); ?>"><?php echo $form->error("type"); ?>
<option value='schedule'>Schedule</option>
<option value='semester'>Semester</option>
		</select></p>
		<input type="hidden" name="subdeadline" value="1">
		<input type="submit" value="Set Deadline">
	</form>
</div>
<hr>
<div>
		<h3>Add Room</h3>
	<form action="adminprocess.php" method="POST">
		<p>Name/Number: </p><p><input type="text" name="name" maxlength="30"></p>
		<p>Capacity: </p><p><input type="text" name="cap" maxlength="30"></p>
		<p>Description: </p><p><input type="text" name="desc" maxlength="30"></p>
		<p><input type="hidden" name="subaddroom" value="1"><input type="submit" value="Add Room"></p>
	</form>
</div>
</hr>
<div>
	<h3>Delete Room</h3>
	<form action="adminprocess.php" method="POST" id="delete">
		<p>Room Number: <select form="delete" name="number">
			<?php
							$q = "SELECT * FROM ".TBL_ROOMS;
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$number  = mysql_result($result,$i,"room_number");
								echo "<option value='".$number."'>".$number."</option>";
							}
						?>	
		</select></p>
		<input type="hidden" name="subdeluser" value="1">
		<input type="submit" value="Delete Room">
	</form>
</div>
</hr>





<?php
if($form->num_errors > 0){
   echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></td>";
}
?>

Back to [<a href="../index.php">Main Page</a>]<br><br>


</div>
</body>
</html>
<?php
}
?>

