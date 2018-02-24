<?php
include("../include/session.php");
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
   header("Location: ../main.php");
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
Back to [<a href="../main.php">Main Page</a>]<br><br>
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
	<form action="adminprocess.php" method="POST">
		<p class="grid_4">Username: <input type="text" name="upduser" maxlength="30" value="<?php echo $form->value("upduser"); ?>"></p>
		<p>Level:
			<select name="updlevel">
				<option value="1">1</option>
				<option value="5">5</option>
				<option value="9">9</option>
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
	<form action="adminprocess.php" method="POST">
		<p class="grid_4">Username: <input type="text" name="deluser" maxlength="30" value="<?php echo $form->value("deluser"); ?>"></p>
		<input type="hidden" name="subdeluser" value="1">
		<input type="submit" value="Delete User">
	</form>
</div>
<hr>

Back to [<a href="../main.php">Main Page</a>]<br><br>


</div>
</body>
</html>
<?php
}
?>

