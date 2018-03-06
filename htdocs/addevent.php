<?php
	include("header.php");
	$page = "addevent.php";
	if(!$session->isInstructor() && !$session->isAdmin()){
		header("Location: main.php");
		} else {
		global $database;
		
		
		
		$q = "select CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END AS tail FROM ".TBL_DEADLINES." WHERE (CURRENT_TIME() BETWEEN open AND close) AND type='schedule'";
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
				
				<p>Event Type: </p><p>			
					<input type="radio" name="type" value="0" <?php if (isset($_GET['ty']) && $_GET['ty'] == 0){echo "checked";} ?>> Class 
					<input type="radio" name="type" value="1" <?php if (isset($_GET['ty']) && $_GET['ty'] == 1){echo "checked";} ?>> Clinical
					<input type="radio" name="type" value="2" <?php if (isset($_GET['ty']) && $_GET['ty'] == 2){echo "checked";} ?>> Exam
					<input type="radio" name="type" value="3" <?php if (isset($_GET['ty']) && $_GET['ty'] == 3){echo "checked";} ?>> Event
				</p>
				
				<p>Seats Needed: </p><p><input type="text" name="seats" maxlength="30" value="<?php echo $_GET['s']; ?>"></p>
				<p>Notes: </p><p><input type="text" name="notes" maxlength="255" value="<?php echo $_GET['n']; ?>"></p>	
				<p>Date: </p><p><input name="date" type="date" value="<?php echo $_GET['d']; ?>"></p>
				<p>Start Time: </p><p><input type="time" name="starttime" maxlength="30" value="<?php echo $_GET['st']; ?>"></p>
				<p>End Time: </p><p><input type="time" name="endtime" maxlength="30" value="<?php echo $_GET['et']; ?>"></p>
				<p>Course: </p><p><select form="addevent" name="course" maxlength="30" value="<?php echo $_GET['c']?>">
					<?php
						$q = "SELECT * FROM ".TBL_COURSE;
						$result = $database->query($q);
						$num_rows = mysql_numrows($result);
						for($i=0; $i<$num_rows; $i++){
							$num = mysql_result($result,$i,"course_number");
							$title = mysql_result($result,$i,"title");
							echo "<option value='".$num."'>".$num." - ".$title."</option>";
						}
					?>		
				</select></p>
				<input type="hidden" name="addeventA" value="1">
				<input type="submit" value="Pick CRNs">
				</form>
				<?php
					if (isset($_GET['c'])){
						$courses[] = explode(" ", trim($_GET['c']));
						
						
					?>
					<form action="process.php" method="POST" id="addeventB">
					<p>
						<label>CRN: </label><br/>
			            <select name="crn[]" size=5 multiple>
							<?php
								$q = "SELECT * FROM ".TBL_CRN." WHERE course_number = ".$_GET['c'];
								$result = $database->query($q);
								$num_rows = mysql_numrows($result);
								for($i=0; $i<$num_rows; $i++){
									$crn  = mysql_result($result,$i,"crn");
									echo "<option value='".$crn."'>".$crn."</option>";
									
									
								}
							?>	
						</select>
					</p> 
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
				<?php 
				}
				if (isset($_GET['crn'])){
				?>
				<form action="process.php" method="POST" id="addeventC">
					
					<p>Rooms Available at the specified date/time: </p><p><select form="addeventC" name="room" maxlength="30" value="<?php echo $form->value("room"); ?>"><?php echo $form->error("room"); ?></p>
						<?php
							$datetimeStart = "".$_GET['d']." ".$_GET['st'].":00";
							$datetimeEnd = "".$_GET['d']." ".$_GET['et'].":00";
							echo "Start: ".$datetimeStart." End: ".$datetimeEnd."";
							$q = "SELECT DISTINCT * FROM ".TBL_ROOMS." WHERE number = 'Offsite' OR NOT EXISTS (SELECT * FROM ".TBL_ROOMS.", ".TBL_EVENTS." where ".TBL_EVENTS.".dateStart >= STR_TO_DATE('$dateStart', '%Y-%m-%d %H:%i:%s') AND ".TBL_EVENTS.".dateStart <= STR_TO_DATE('$dateEnd', '%Y-%m-%d %H:%i:%s') AND ".TBL_ROOMS.".id = ".TBL_EVENTS.".room)";
							
							$result = $database->query($q);
							
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$id  = mysql_result($result,$i,"id");
								$room  = mysql_result($result,$i,"number");
								echo "<option value='".$id."'>".$room."</option>";
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
						<input type="hidden" name="addeventC" value="1">
						<input type="hidden" name="title" value="<?php echo $_GET['t']; ?>">
						<input type="hidden" name="type" value="<?php echo $_GET['ty']; ?>">
						<input type="hidden" name="course" value="<?php echo $_GET['c']; ?>">
						<input type="hidden" name="crn" value="<?php echo $_GET['crn']; ?>">
						<input type="hidden" name="seats" value="<?php echo $_GET['s']; ?>">
						<input type="hidden" name="notes" value="<?php echo $_GET['n']; ?>">
						<input type="hidden" name="dateStart" value="<?php echo $datetimeStart; ?>">
						<input type="hidden" name="dateEnd" value="<?php echo $datetimeEnd; ?>">
						<input type="submit" value="Add Event">
					</p>
				</form>
				<?php
					
				echo $dateStart;
				echo "<br>";
				echo $dateEnd;
				}
				} else {
				echo "This form is not available at the current time. Requests will be implemented later. We apologize for the inconvenience.";
				?>
		</div>
		
	</body>
</html>
<?php
} 
}

?>						