<?php
	include("header.php");
	$page = "addevent.php";
	if(!$session->isInstructor()){
		header("Location: main.php");
		} else {
		global $database;
		
		
		
		$q = "select CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END AS tail FROM ".TBL_DEADLINES." WHERE (CURRENT_TIME() BETWEEN open AND close) AND type=1";
		$result = $database->query($q);
		$tail = mysql_result($result,0,"tail");
		if ($tail == 0 || $session->isAdmin()) {
		?>
		<div>
			<h1>Add Event</h1>
			<?php
				if($form->num_errors > 0){
					echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
				}
			?>
			
			<form action="process.php" method="POST" id="addevent">
				<p>Title: </p><p><input type="text" name="title" maxlength="30" value="<?php echo $_GET['t']; ?>"></p>
				
				<p>Event Type: </p><p><select form="addevent" name="type" maxlength="30" value="<?php echo $_GET['ty']; ?>">
					<?php
						$q = "SELECT * "
						."FROM ".TBL_TYPES." ";
						$result = $database->query($q);
						$num_rows = mysql_numrows($result);
						for($i=0; $i<$num_rows; $i++){
							$id  = mysql_result($result,$i,"id");
							$title = mysql_result($result,$i,"title");
							echo "<option value='".$id."'>".$title."</option>";
						}
					?>
				</select></p>
				
				<p>Seats Needed: </p><p><input type="text" name="seats" maxlength="30" value="<?php echo $_GET['s']; ?>"></p>
				<p>Notes: </p><p><input type="text" name="notes" maxlength="255" value="<?php echo $_GET['n']; ?>"></p>	
				<p>Date: </p><p><input name="date" type="date" value="<?php echo $_GET['d']; ?>"></p>
				<p>Start Time: </p><p><select form="addevent" name="starttime" maxlength="30" value="<?php echo $_GET['st']; ?>">
					<option value='7:30'>7:30am</option>
					<option value='7:45'>7:45am</option>
					<option value='8:00'>8:00am</option>
					<option value='8:15'>8:15am</option>
					<option value='8:30'>8:30am</option>
					<option value='8:45'>8:45am</option>
					<option value='9:00'>9:00am</option>
					<option value='9:15'>9:15am</option>
					<option value='9:30'>8:30am</option>
					<option value='9:45'>9:45am</option>
					<option value='10:00'>10:00am</option>
					<option value='10:15'>10:15am</option>
					<option value='10:30'>10:30am</option>
					<option value='10:45'>10:45am</option>
					<option value='11:00'>11:00am</option>
					<option value='11:15'>11:15am</option>
					<option value='11:30'>11:30am</option>
					<option value='11:45'>11:45am</option>
					<option value='12:00'>12:00pm</option>
					<option value='12:15'>12:15pm</option>
					<option value='12:30'>12:30pm</option>
					<option value='12:45'>12:45pm</option>
					<option value='13:00'>1:00pm</option>
					<option value='13:15'>1:15pm</option>
					<option value='13:30'>1:30pm</option>
					<option value='13:45'>1:45pm</option>
					<option value='14:00'>2:00pm</option>
					<option value='14:15'>2:15pm</option>
					<option value='14:30'>2:30pm</option>
					<option value='14:45'>2:45pm</option>
					<option value='15:00'>3:00pm</option>
					<option value='15:15'>3:15pm</option>
					<option value='15:30'>3:30pm</option>
					<option value='15:45'>3:45pm</option>
					<option value='16:00'>4:00pm</option>
					<option value='16:15'>4:15pm</option>
					<option value='16:30'>4:30pm</option>
					<option value='16:45'>4:45pm</option>
					<option value='17:00'>5:00pm</option>
					<option value='17:15'>5:15pm</option>
					<option value='17:30'>5:30pm</option>
					<option value='17:45'>5:45pm</option>
				</select></p>
				<p>End Time: </p><p><select form="addevent" name="endtime" maxlength="30" value="<?php echo $_GET['et']; ?>">
					<option value='7:30'>7:30am</option>
					<option value='7:45'>7:45am</option>
					<option value='8:00'>8:00am</option>
					<option value='8:15'>8:15am</option>
					<option value='8:30'>8:30am</option>
					<option value='8:45'>8:45am</option>
					<option value='9:00'>9:00am</option>
					<option value='9:15'>9:15am</option>
					<option value='9:30'>8:30am</option>
					<option value='9:45'>9:45am</option>
					<option value='10:00'>10:00am</option>
					<option value='10:15'>0:15am</option>
					<option value='10:30'>10:30am</option>
					<option value='10:45'>10:45am</option>
					<option value='11:00'>11:00am</option>
					<option value='11:15'>11:15am</option>
					<option value='11:30'>11:30am</option>
					<option value='11:45'>11:45am</option>
					<option value='12:00'>12:00pm</option>
					<option value='12:15'>12:15pm</option>
					<option value='12:30'>12:30pm</option>
					<option value='12:45'>12:45pm</option>
					<option value='13:00'>1:00pm</option>
					<option value='13:15'>1:15pm</option>
					<option value='13:30'>1:30pm</option>
					<option value='13:45'>1:45pm</option>
					<option value='14:00'>2:00pm</option>
					<option value='14:15'>2:15pm</option>
					<option value='14:30'>2:30pm</option>
					<option value='14:45'>2:45pm</option>
					<option value='15:00'>3:00pm</option>
					<option value='15:15'>3:15pm</option>
					<option value='15:30'>3:30pm</option>
					<option value='15:45'>3:45pm</option>
					<option value='16:00'>4:00pm</option>
					<option value='16:15'>4:15pm</option>
					<option value='16:30'>4:30pm</option>
					<option value='16:45'>4:45pm</option>
					<option value='17:00'>5:00pm</option>
					<option value='17:15'>5:15pm</option>
					<option value='17:30'>5:30pm</option>
					<option value='17:45'>5:45pm</option>
				</select></p>
				
				
				<p>Course: </p><p><select form="addevent" name="course" maxlength="30" value="<?php echo $_GET['c']?>">
					<?php
						$q = "SELECT * FROM ".TBL_COURSE;
						$result = $database->query($q);
						$num_rows = mysql_numrows($result);
						for($i=0; $i<$num_rows; $i++){
							$id  = mysql_result($result,$i,"id");
							$num = mysql_result($result,$i,"number");
							$title = mysql_result($result,$i,"title");
							echo "<option value='".$id."'>".$num." - ".$title."</option>";
						}
					?>		
				</select></p>
				<input type="hidden" name="addevent" value="1">
				<input type="submit" value="Pick CRNs">
			</form>
			<?php
				if (isset($_GET['c'])){
					$course = $_GET['c']; 
				?>
				<form action="process.php" method="POST" id="addeventB">
					
					
					<p>CRN: </p><p><select form="addeventB" name="crn" maxlength="30" value="<?php echo $form->value("crn"); ?>"><?php echo $form->error("crn"); ?>
						<?php
							$q = "SELECT * "
							."FROM ".TBL_CRN." WHERE courseid = $course";
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$crn  = mysql_result($result,$i,"crn");
								echo "<option value='".$crn."'>".$crn."</option>";
							}
						?>		
					</select></p>
					
					
					
					
					<p>
						<input type="hidden" name="title" value="<?php echo $_GET['t']?>">
						<input type="hidden" name="type" value="<?php echo $_GET['ty']?>">
						<input type="hidden" name="course" value="<?php echo $_GET['c']?>">
						<input type="hidden" name="seats" value="<?php echo $_GET['s']?>">
						<input type="hidden" name="notes" value="<?php echo $_GET['n']?>">
						<input type="hidden" name="date" value="<?php echo $_GET['d']?>">
						<input type="hidden" name="starttime" value="<?php echo $_GET['st']?>">
						<input type="hidden" name="endtime" value="<?php echo $_GET['et']?>">
						
						
						<input type="hidden" name="addeventB" value="1">
						<input type="submit" value="Pick Location">
					</p>
				</form>
			<?php } ?>
		</div>
		
	</body>
</html>
<?php
	} else {
	echo "This form is not available at the current time. Requests will be implemented later. We apologize for the inconvenience.";
}
}
?>						