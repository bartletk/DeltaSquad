<?php
	include("top_header.php");
	$page = "editevent.php";
	if(!isset($_GET['e'])){
	?>
	You have made it here by mistake. Please go back to the calendar and select an event to edit.
	<?php
		} else if(!$session->isInstructor() && !$session->isAdmin()){
		?>
		Only instructors & administrators may edit events. Please contact an administrator if you think you should be able to edit events and you are not able to.
		
		<?php
	} else {
		global $database;
		$event = $_GET['e'];
		$q = "SELECT * FROM ".TBL_EVENTS." WHERE event_id=".$event;
		$result = $database->query($q);
		$num_rows = mysql_numrows($result);
		for($i=0; $i<$num_rows; $i++){
			$title = mysql_result($result,$i,"title");
			$type = mysql_result($result,$i,"type");
			$dateStart = mysql_result($result,$i,"dateStart");
			$dateEnd = mysql_result($result,$i,"dateEnd");
			$date = date('m-d-Y',strtotime($dateStart));
			$timeStart = date('h:i A',strtotime($dateStart));
			$timeEnd = date('h:i A',strtotime($dateEnd));
			$room = mysql_result($result,$i,"room_number");
			$seats = mysql_result($result,$i,"attendees");
			$creator = mysql_result($result,$i,"CWID");
			$notes = mysql_result($result,$i,"notes");
			$crn = mysql_result($result,$i,"crn");
		}
	?> 
<div>
			<h1>Edit Event</h1>
			<?php
				if($form->num_errors > 0){
					echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
				}
			?>
			
			<form action="process.php" method="POST" id="editevent">
				<p>Title: </p><p><input type="text" name="title" maxlength="30" value="<?php echo $title; ?>"></p>
				
				<p>Event Type: </p><p>			
					<input type="radio" name="type" value="0" <?php if ($type == 0){echo "checked";} ?>> Class 
					<input type="radio" name="type" value="1" <?php if ($type == 1){echo "checked";} ?>> Clinical
					<input type="radio" name="type" value="2" <?php if ($type == 2){echo "checked";} ?>> Exam
					<input type="radio" name="type" value="3" <?php if ($type == 3){echo "checked";} ?>> Event
				</p>
				
				<p>Seats Needed: </p><p><input type="text" name="seats" maxlength="30" value="<?php echo $seats; ?>"></p>
				<p>Notes: </p><p><input type="text" name="notes" maxlength="255" value="<?php echo $notes; ?>"></p>	
				<p>Date: </p><p><input name="date" type="date" value="<?php echo $date; ?>"></p>
				<p>Start Time: </p><p><input type="time" name="starttime" maxlength="30" value="<?php echo $timeStart; ?>"></p>
				<p>End Time: </p><p><input type="time" name="endtime" maxlength="30" value="<?php echo $timeEnd; ?>"></p>
				<p>Course CRN: </p><p><?php echo $crn; ?> (Not editable)</p>
					</p> 
					<p>
						<input type="hidden" name="editevent" value="1">
						<input type="submit" value="Pick Location">
					</p>
				</form>
				<form action="process.php" method="POST" id="editeventB">
					
					<p>Rooms Available at the specified date/time: </p><p><select form="editeventB" name="room" maxlength="30" value="<?php echo $form->value("room"); ?>"><?php echo $form->error("room"); ?></p>
						<?php
							$datetimeStart = "".$_GET['d']." ".$_GET['st'].":00";
							$datetimeEnd = "".$_GET['d']." ".$_GET['et'].":00";
							$q = "SELECT DISTINCT room_number FROM ".TBL_ROOMS." WHERE room_number = 'Offsite' OR NOT EXISTS (SELECT * FROM ".TBL_ROOMS.", ".TBL_EVENTS." where ".TBL_EVENTS.".dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND ".TBL_EVENTS.".dateStart <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s') AND ".TBL_ROOMS.".room_number = ".TBL_EVENTS.".room_number)";
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$room  = mysql_result($result,$i,"room_number");
								echo "<option value='".$room."'>".$room."</option>";
							}
							
						?>
						
					</select>
					<p>
						<?php
							$q = sprintf("select MAX(series) AS Max from ".TBL_EVENTS." where series<9000");
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$max  = mysql_result($result,$i,"Max")+1;
								echo "<input type='hidden' name='series' value='".$max."'>";
							}
						?>
						<input type="hidden" name="editeventB" value="1">
						<input type="hidden" name="title" value="<?php echo $_GET['t']; ?>">
						<input type="hidden" name="type" value="<?php echo $_GET['ty']; ?>">
						<input type="hidden" name="course" value="<?php echo $_GET['c']; ?>">
						<input type="hidden" name="crn" value="<?php echo $_GET['crn']; ?>">
						<input type="hidden" name="seats" value="<?php echo $_GET['s']; ?>">
						<input type="hidden" name="notes" value="<?php echo $_GET['n']; ?>">
						<input type="hidden" name="dateStart" value="<?php echo $datetimeStart; ?>">
						<input type="hidden" name="dateEnd" value="<?php echo $datetimeEnd; ?>">
						<input type="hidden" name="repeat" value="<?php echo $_GET['repeat']?>">
						<input type="hidden" name="repeatm" value="<?php echo $_GET['repeatm']?>">
						<input type="hidden" name="repeatt" value="<?php echo $_GET['repeatt']?>">
						<input type="hidden" name="repeatw" value="<?php echo $_GET['repeatw']?>">
						<input type="hidden" name="repeatth" value="<?php echo $_GET['repeatth']?>">
						<input type="hidden" name="repeatf" value="<?php echo $_GET['repeatf']?>">
						<input type="hidden" name="re" value="<?php echo $_GET['re']?>">
						
						<input type="submit" value="Edit Event">
					</p>
				</form>
<form action="process.php" method="POST" id="deleteEvent">
	<input type="submit" value="Delete Event">
	<input type="hidden" name="deleteEvent" value="1">
	<input type="hidden" name="eventID" value="<?php echo $event;?>">
	
	</form>
		</div>

	<?php
	}
	// dont forget to check deadlines for editting!
	include("footer.php");
			?>		