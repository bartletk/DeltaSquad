<?php
	include("top_header.php");
	$page = "messages.php";
	if(!$session->logged_in && (!$session->isInstructor() || !$session->isAdmin())){
		header("Location: ".$session->referrer);
	}
	global $database;
	$user = $session->username;
	$q = sprintf("SELECT * FROM mail WHERE UserTo = '%s' ORDER BY SentDate DESC",
	mysql_real_escape_string($user));
	$result = $database->query($q);
	$num_rows = mysql_numrows($result);
	
	echo "<div id='inbox' class='card card-2'>";
	if($num_rows == 0){
	?>
	<a href="/reply.php">
	<button class="btn waves-effect waves-light" type="submit" name="action">Compose
					<i class="material-icons right">send</i>
				</button>
				</a>
	<?php
		echo "<p>You have no messages!</p><br /><br />";
		} else {	
	?>
<a href="/reply.php">
	<button class="btn waves-effect waves-light" type="submit" name="action">Compose
					<i class="material-icons right">send</i>
				</button>
				</a>
	<table class="highlight stripped">
		<thead>
		<tr class="title">
			<td>Status</td>
			<td>From</td>
			<td>Subject</td>
			<td>Received</td>
			<td colspan="2" align="center">Action</td>
		</tr>
	</thead>
	</div>
	<?php
		
		for($i=0; $i<$num_rows; $i++){
			$status = mysql_result($result,$i,"status");
			$from = mysql_result($result,$i,"UserFrom");
			$subject = mysql_result($result,$i,"Subject");
			$sent = mysql_result($result,$i,"SentDate");
			$id = mysql_result($result,$i,"mail_id");
			echo "<form action='process.php' method='post'>";
		?>
		<tbody>
		<tr>


			<td><?php echo $status; ?></td>
			<td><?php echo $from; ?></td>
			<td><?php echo $subject; ?></td>
			<td><?php echo $sent; ?></td>
						<td>
			<form action="process.php" method="POST" id="readMail" class="col s12">
				<input type="hidden" name="readMail" value="1">
				<input type="hidden" name="mail_id" value="<?php echo $id; ?>" />	
				<button class="btn waves-effect waves-light" type="submit" name="action">View
					<i class="material-icons right">send</i>
				</button>
			</form>
			</td>
			<td>
			<form action="process.php" method="POST" id="deleteMail" class="col s12">
				<input type="hidden" name="deleteMail" value="1">
				<input type="hidden" name="mail_id" value="<?php echo $id; ?>" />	
				<button class="btn waves-effect waves-light" type="submit" name="action">Delete
					<i class="material-icons right">send</i>
				</button>
			</form>
			</td>
		</tr>
	</tbody>

		<?php
			
			
		}	
		echo "</table>";
		
		
	}
	echo '</div>';
	include "footer.php";
	
?>