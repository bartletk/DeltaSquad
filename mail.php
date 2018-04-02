<?php
	include("header.php");
$page = "mail.php";
	
	if(!$session->logged_in){
		header("Location: ".$session->referrer);
	}
	
	if($_POST){
	   $_POST = $session->cleanInput($_POST);
	}
?>


<div>
	<h1>User Message System</h1>
	<form method="post" action="mail.php">
		<input type="submit" name="mailAction" value="Compose" /><input type="submit" name="mailAction" value="Inbox" />
	</form>

<?php

	if(!empty($_POST['mailAction']) && isset($_POST['mailAction'])){
		$action = $_POST['mailAction'];
	} else {
		$action = 'Inbox';
	}



	if(($action == 'Compose') || ($action == 'Reply')) {
	
		if(isset($_POST['mailSubject']) && !empty($_POST['mailSubject'])){
			$mailSubject = 'Re: '.$_POST['mailSubject'];
		} else {
			$mailSubject = "";
		}
		
		if(isset($_POST['mailFrom']) && !empty($_POST['mailFrom'])){
			$mailTo = $_POST['mailFrom'];
		} else {
			$mailTo = "";
		}
		
		
		?>
			<form action="mail.php" method='post'>
				<div id="compose">
					<p>To:</p><p><input type='text' name='mailTo' size='20' value='<?php echo $mailTo; ?>'></p>
					<div></div>
					<p>Subject:</p><p><input type='text' name='mailSubject' size='20' value='<?php echo $mailSubject; ?>'></p>
					<div></div>
					<p>Message:</p><p class="grid_4"><textarea rows='16' cols='45' name='mailMessage'></textarea></p>
					<div></div>
					<p><input type="submit" name="mailAction" value="Send" /></p>
				</div>
			</form>
		<?php
	}
	
	
	if($action == 'Send') {
			
		if(empty($_POST['mailSubject']) || !isset($_POST['mailSubject'])){
			echo "Subject Blank";
		} else {
			$subject = $_POST['mailSubject'];
		}
		
		if(empty($_POST['mailTo']) || !isset($_POST['mailTo'])){
			echo "To Blank";
		} else {
			$mailTo = $_POST['mailTo'];
		}
		
		if(empty($_POST['mailMessage']) || !isset($_POST['mailMessage'])){
			echo "Message Blank";
		} else {
			$message = $_POST['mailMessage'];
		}
		
		$date = date('m/d/Y')." at ".date('g:i.s')." ".date('a');
		
		$q = sprintf("INSERT INTO mail (UserTo, UserFrom, Subject, Message, SentDate, status) VALUES ('%s','$session->username','%s','%s','%s','unread')", 
               mysql_real_escape_string($mailTo),
               mysql_real_escape_string($subject),
               mysql_real_escape_string($message),
               mysql_real_escape_string($date));
		if(!($send = $database->query($q))){
			echo "A letter could not be sent to ".$mailTo."!";
		} else {
			echo "Message Sent to ".$mailTo."!";
		}
		
	}
	
	
	if($action == "Inbox") {
	
		$user = $session->username;
		$q = sprintf("SELECT * FROM mail WHERE UserTo = '%s' ORDER BY SentDate DESC",
		      mysql_real_escape_string($user));
		$getMail = $database->query($q) or die(mysql_error());

		echo "<div id='inbox'>";
		
		if(mysql_num_rows($getMail) == 0){
			echo "<p>you have no mail</p><br /><br />";
		} else {			
			?>
			<table>
				<tr class="title">
					<td colspan="2" align="center">Action</td>
					<td>Status</td>
					<td>From</td>
					<td>Subject</td>
					<td>Time</td>
				</tr>
			</div>
			<?php
			echo "<form action='mail.php' method='post'>";
			while($mail = mysql_fetch_array($getMail)){
				?>
					<tr>
						<input type="hidden" name="mail_id" value="<?php echo $mail['mail_id']; ?>" />
						<td align="center"><input type="submit" name="mailAction" value='View' /></td>
						<td align="center"><input type="submit" name="mailAction" value="Delete" /></td>
						<td><?php echo $mail['status']; ?></td>
						<td><?php echo $mail['UserFrom']; ?></td>
						<td><?php echo $mail['Subject']; ?></td>
						<td><?php echo $mail['SentDate']; ?></td>
					</tr>
				<?php
			}

			echo "</form>";
		}			
		echo "</table>";
	
	}
	
	
	if($action == "View") {
	
		
		$mail_id = $_POST['mail_id'];
		$user = $session->username;
		$q = sprintf("SELECT * FROM mail WHERE UserTo = '%s' AND mail_id = '%s'",
		      mysql_real_escape_string($user),
		      mysql_real_escape_string($mail_id));
		$result = $database->query($q) or die (mysql_error());
		$row = mysql_fetch_array($result);
		
		
		if($row['UserTo'] != $session->username) {
			echo "<font face=verdana><b>This isn't your mail!";
			exit;
		}
		$q = "UPDATE mail SET status='read' WHERE UserTo='$session->username' AND mail_id='$row[mail_id]'";
		$database->query($q) or die("An error occurred resulting that this message has not been marked read.");
		
		?>
			<form method="post" action="mail.php">
				<div id="single">
					<p>From: </p><p><?php echo $row['UserFrom']; ?><input type="hidden" name="mailFrom" value="<?php echo $row['UserFrom']; ?>" /></p>
					<p>Subject: </p><p><?php echo $row['Subject']; ?><input type="hidden" name="mailSubject" value="<?php echo$row['Subject']; ?>" /></p>
					<p>body: <br /><?php echo $row['Message']; ?><br /></p>
					<p><input type="submit" name="mailAction" value="Reply" /></p>
				</div>
			</form>
		<?php
	}
	
	
	if($action == 'Delete') {
		$id = $_POST['mail_id'];
		$query = sprintf("UPDATE mail SET 'Deleted' = 1 WHERE mail_id='%s' LIMIT 1",
		            mysql_real_escape_string($id));
		
		if(!$query) {
			echo "The message wasn\'t deleted";
		} else {
			header("Location: mail.php");
		}
	}

?>

</div>