<?php
	include("top_header.php");
	$page = "reply.php";
	
	
	if(isset($_GET['s']) && !empty($_GET['s'])){
			$mailSubject = 'Re: '.$_GET['s'];
		} else {
			$mailSubject = "";
		}
		
		if(isset($_GET['f']) && !empty($_GET['f'])){
			$mailTo = $_GET['f'];
		} else {
			$mailTo = "";
		}
		
		
		?>
			<form action="process.php" method='post'>
			<?php
			if (isset($mailTo) && $mailTo != NULL && $mailTo != ''){
				echo "<p>To:</p><p>$mailTo<input type='hidden' name='mailTo' size='20' value='$mailTo'></p>";
			} else {
				echo '<p>To:</p><select name="mailTo" class="drop wid">';
										$q = "SELECT * FROM ".TBL_USERS;
										$result = $database->query($q);
										$num_rows = mysql_numrows($result);
										for($i=0; $i<$num_rows; $i++){
											$username  = mysql_result($result,$i,"username");
											echo "<option value='".$username."'>".$username."</option>";
										}
								echo '</select>';
			}
			
			?>
					
					<p>Subject:</p><p><input type='text' name='mailSubject' size='20' value='<?php echo $mailSubject; ?>'></p>
					<p>Message:</p><p class="grid_4"><textarea rows='16' cols='45' name='mailBody'></textarea></p>
					<input type="hidden" name="subsendreply" value="1">
					<button class="btn waves-effect waves-light" type="submit" name="action">Send
					<i class="material-icons right">send</i>
				</button>
			</form>
		<?php
	include "footer.php";
?>