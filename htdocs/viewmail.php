<?php
	include("top_header.php");
	$page = "viewmail.php";
	
	if(!$session->logged_in && (!$session->isInstructor() || !$session->isAdmin())){
		header("Location: ".$session->referrer);
	}
	global $database;
	if (isset($_GET['m']) && $_GET['m'] != 0){
	$user = $session->username;
	$q = sprintf("SELECT * FROM mail WHERE UserTo = '%s' AND mail_id = %s ORDER BY SentDate DESC",
	mysql_real_escape_string($user),
	mysql_real_escape_string($_GET['m']));
	$result = $database->query($q);
	$num_rows = mysql_numrows($result);
	$row = mysql_fetch_array($result);
	echo "<div id='inbox'>";
	if($num_rows == 0){
		echo "<p>This isn't your message to read!!</p><br /><br />";
		} else {	
	?>
			<form method="post" action="process.php" name="reply">
				<div id="single">
					<p>From: <?php echo $row['UserFrom']; ?><input type="hidden" name="mailFrom" value="<?php echo $row['UserFrom']; ?>" /></p>
					<p>Subject: <?php echo $row['Subject']; ?><input type="hidden" name="mailSubject" value="<?php echo$row['Subject']; ?>" /></p>
					<p>Body: <br /><?php echo $row['Message']; ?><br /></p>
					<input type="hidden" name="subreply" value="1">
					<button class="btn waves-effect waves-light" type="submit" name="action">Reply
					<i class="material-icons right">send</i>
				</button>
				</div>
			</form>
	<?php
		}
	} else {
	echo "<p>You must select a message to view!!</p><br /><br />";
	}
		include "footer.php";
		
		?>