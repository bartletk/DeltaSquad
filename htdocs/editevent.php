<?php
	include("top_header.php");
	
	echo '<script>
	
	$( document ).ready(function(){
	$(".button-collapse").sideNav();
	
	
	$(\'.datepicker\').pickadate({
	selectMonths: true, // Creates a dropdown to control month
	selectYears: 15, // Creates a dropdown of 15 years to control year,
	today: \'Today\',
	clear: \'Clear\',
	close: \'Ok\',
	closeOnSelect: false, // Close upon selecting a date,
	format: "mm/dd/yyyy",
	formatSubmit: "yyyy/mm/dd"
	
	
	})
	$(\'.timepicker\').pickatime({
	default: \'now\',
	twelvehour: true, // change to 12 hour AM/PM clock from 24 hour
	donetext: \'OK\',
	autoclose: false
	//vibrate: true // vibrate the device when dragging clock hand
	})
	$(document).ready(function() {
	$(\'select\').material_select();
	});
	
	});
	
	
	
	</script>';
	$page = "editevent.php";
	if(!isset($_GET['e'])){
	?>
	<main>
	You have made it here by mistake. Please go back to the calendar and select an event to edit.
	</main>
	<?php
		} else if(!$session->isInstructor() && !$session->isAdmin()){
	?>
	<main>
	Only instructors & administrators may edit events. Please contact an administrator if you think you should be able to edit events and you are not able to.
	</main>
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
			$date = date_format(new DateTime($dateStart), 'Y-m-d');
			$timeStart = date('h:i A',strtotime($dateStart));
			$timeEnd = date('h:i A',strtotime($dateEnd));
			$room = mysql_result($result,$i,"room_number");
			$seats = mysql_result($result,$i,"attendees");
			$creator = mysql_result($result,$i,"CWID");
			$notes = mysql_result($result,$i,"notes");
			$crn = mysql_result($result,$i,"crn");
			$status = mysql_result($result, $i, "status");
		}
	?> 
	<main>
	<div class="Card card1">
		<h1>Edit Event</h1>
		<?php
			if ($session->isAdmin() && $status != "approved"){
			?>
			<form action="process.php" method="POST" id="approve" class="col s12">
				<input type="hidden" name="approve" value="1">
				<input type="hidden" name="eventid" value="<?php echo $event; ?>">
				<button class="btn waves-effect waves-light" type="submit" name="action">Approve
					<i class="material-icons right">send</i>
				</button>
			</form>
			<form action="process.php" method="POST" id="reject" class="col s12">
				<input type="hidden" name="reject" value="1">
				<input type="hidden" name="eventid" value="<?php echo $event; ?>">
				<button class="btn waves-effect waves-light" type="submit" name="action">Reject
					<i class="material-icons right">send</i>
				</button>
			</form>
			<?php
			}
			if($form->num_errors > 0){
				echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
			}
			$lead = 0;
			$instructor = 0;
			
			$q2 = "select instructor from ".TBL_CRN." where crn = ".$crn." AND instructor != 0";
			//$myfile = fopen("error.txt", "a") or die(print_r($q2));
			$result2 = $database->query($q2);
			$num_rows2 = mysql_numrows($result2);
			for($i2=0; $i2<$num_rows2; $i2++){
				$instructor = mysql_result($result2,$i2,"instructor");
			}
			
			$q3 = "select ".TBL_COURSE.".lead_instructor from ".TBL_COURSE." join ".TBL_CRN." on ".TBL_COURSE.".course_number = ".TBL_CRN.".course_number where crn = $crn and lead_instructor != 0";
			$result3 = $database->query($q3);
			$num_rows3 = mysql_numrows($result3);
			for($i3=0; $i3<$num_rows3; $i3++){
				$lead = mysql_result($result3,$i3,"lead_instructor");
			}
			//$myfile = fopen("error.txt", "a") or die(print_r($lead." ".$instructor));
			if ($session->CWID == $lead || $session->isAdmin()){
				if (!isset($_GET['t'])){
				?>
				
				<form action="process.php" method="POST" id="editevent" class="col s12">
					<div class="row">
						<div class="row col s12">
							<label for="title">Title</label><p><input type="text" name="title" maxlength="30" readonly value="<?php echo $title; ?>"></p>
							
							<label for="event_type">Event Type:<label><p>			
								<input type="radio" name="type" value="0" required <?php if ($type == 0){echo "checked";} ?>> <label for="class1">Class</label>
								<input type="radio" name="type" value="1" <?php if ($type == 1){echo "checked";} ?>> <label for="Clinical">Clinical</label>
								<input type="radio" name="type" value="2" <?php if ($type == 2){echo "checked";} ?>> <label for="exam">Exam</label>
								<input type="radio" name="type" value="3" <?php if ($type == 3){echo "checked";} ?>> <label for="event">Event</label>
							</p>
							</div>
							
							
							
							<div class="row col s12">
								<div class="input-field col s12">
									<p>Seats Needed: </p><p><input placeholder="seats needed" id="seats" type="number"  name="seats" maxlength="30" required value="<?php echo $seats; ?>"></p>
								</div>
							</div>
							<div class="row col s12">
								<div class="input-field col s12">
									<p>Notes: </p><p><input placeholder="note" id="notes" type="text" name="notes" maxlength="255" value="<?php echo $notes; ?>"></p>	
								</div>
							</div>
							<div class="row col s12" >
								<p>Date: </p><p><input name="date" type="date" class = "datepicker" value="<?php echo $date; ?>"></p>
							</div>
							
							<div class="row col s12">
								<p>Start Time: </p><p><input type="text" class="timepicker" name="starttime" maxlength="30" required value="<?php echo $timeStart; ?>"></p>
							</div>
							
							<div class="row col s12">
								<p>End Time: </p><p><input type="text" class="timepicker" name="endtime" maxlength="30" required value="<?php echo $timeEnd; ?>"></p>
							</div>
							<div class="row col s12">
								<p>Course CRN: </p><p><?php echo $crn; ?> (Not editable)</p>
							</p>
							</div>
							<p>
								<input type="hidden" name="eventid" value="<?php echo $event ?>">
								<input type="hidden" name="editeventA" value="1">
								<button class="btn waves-effect waves-light" type="submit" name="action">Pick Room
									<i class="material-icons right">send</i>
								</button>
							</p>
						</form>
						<form action="process.php" method="POST" id="deleteEvent">
							<button class="btn waves-effect waves-light" type="submit" name="action">Delete Event
								<i class="material-icons right">send</i>
							</button>
						</p>
						<input type="hidden" name="deleteEvent" value="1">
						<input type="hidden" name="eventid" value="<?php echo $event;?>">
						
					</form>
					
					<?php 
					}
					if (isset($_GET['t'])){
					?>
					
					<div class="row col s12" >
						Title: <?php echo $_GET['t']; ?> <br>
					</div>
					<div class="row col s12" >
						Date: <?php echo $_GET['d']; ?><br>
					</div>
					<div class="row col s12" >
						Start time: <?php echo $_GET['st']; ?><br>
					</div>
					<div class="row col s12" >
						End time: <?php echo $_GET['et']; ?><br>
					</div>
					<div class="row col s12" >
						Attendees: <?php echo $_GET['s']; ?><br>
					</div>
					<form action="process.php" method="POST" id="editeventB">
						
						<p>Rooms Available at the specified date/time: </p><p>
							<?php
								$datetimeStart = "".$_GET['d']." ".$_GET['st'].":00";
								$datetimeEnd = "".$_GET['d']." ".$_GET['et'].":00";
								$seats=0;
								if (isset($_GET['s']) && $_GET['s'] != NULL) { 
									$seats =  $_GET['s']; 
								}
								// capacity & no conflicts
								$q = "SELECT * FROM ".TBL_ROOMS." r WHERE r.Capacity >= $seats AND r.ROOM_NUMBER != 'Offsite' AND r.ROOM_NUMBER NOT IN (
								SELECT count(*) FROM ".TBL_EVENTS." AS e WHERE series != 9100 AND room_number != 'Offsite' AND (
								(e.dateStart <= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateStart <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart <= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd >= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s')))
								)
								";
								$result = $database->query($q);
								//$myfile = fopen("error.txt", "a") or die(print_r($q));
								$num_rows = mysql_numrows($result);
								if ($num_rows > 0){
									for($i=0; $i<$num_rows; $i++){
										$num  = mysql_result($result,$i,"room_number");
										$desc = mysql_result($result,$i,"description");
										$msg = "No conflicts!";
										$date = date("Y-m-d",strtotime($datetimeStart));
										makeTable($num, $desc, $msg, 2, $date, 0);
									}
								}
								// capacity, but conflicts, in order of least 2 most conflicts ( i hope )
								$q2 = "SELECT *, count(room_number) Conflicts FROM ".TBL_ROOMS." r WHERE r.Capacity >= $seats AND r.ROOM_NUMBER != 'Offsite' AND r.ROOM_NUMBER IN (
								SELECT count(*) FROM ".TBL_EVENTS." AS e WHERE series != 9100 AND room_number != 'Offsite' AND (
								(e.dateStart <= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateStart <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart <= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd >= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s')))
								)";
								//$myfile = fopen("error.txt", "a") or die(print_r($q2));
								$result2 = $database->query($q2);
								$num_rows2 = mysql_numrows($result2);
								if ($num_rows2 > 0){
									for($j=0; $j<$num_rows2; $j++){
										$num  = mysql_result($result2,$j,"room_number");
										$desc = mysql_result($result2,$j,"description");
										$conflicts = mysql_result($result2, $j, "Conflicts");
										$msg = $conflicts." conflicts";
										$date = date("Y-m-d",strtotime($datetimeStart));
										makeTable($num, $desc, $msg, 2, $date, 1);
									}
								}
								// not capacity, but no conflicts, not sorted yet?
								$q3 = "SELECT * FROM ".TBL_ROOMS." r WHERE r.Capacity < $seats AND r.ROOM_NUMBER != 'Offsite' AND r.ROOM_NUMBER NOT IN (
								SELECT count(*) FROM ".TBL_EVENTS." AS e WHERE series != 9100 AND room_number != 'Offsite' AND (
								(e.dateStart <= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateStart <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart <= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd >= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s')))
								)
								";
								//$myfile = fopen("error.txt", "a") or die(print_r($q3));
								$result3 = $database->query($q3);
								$num_rows3 = mysql_numrows($result3);
								if ($num_rows3 > 0){
									for($k=0; $k<$num_rows3; $k++){
										$num  = mysql_result($result3,$k,"room_number");
										$desc = mysql_result($result3,$k,"description");
										$capacity = mysql_result($result3, $k, "capacity");
										$msg = "Not enough capacity: ".$capacity;
										$date = date("Y-m-d",strtotime($datetimeStart));
										makeTable($num, $desc, $msg, 2, $date, 2);
									}
								}
								// not capacity
								$q4 = "
								SELECT *, count(room_number) Conflicts FROM ".TBL_ROOMS." r WHERE r.Capacity < $seats AND r.ROOM_NUMBER != 'Offsite' AND r.ROOM_NUMBER IN (
								SELECT count(*) FROM ".TBL_EVENTS." AS e WHERE series != 9100 AND room_number != 'Offsite' AND (
								(e.dateStart <= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateStart <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s'))
								OR
								(e.dateStart <= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND e.dateEnd >= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s')))
								)";								
								//$myfile = fopen("error.txt", "a") or die(print_r($q4));
								$result4 = $database->query($q4);
								$num_rows4 = mysql_numrows($result4);
								if ($num_rows > 0){
									for($l=0; $l<$num_rows4; $l++){
										$num  = mysql_result($result4,$l,"room_number");
										$desc = mysql_result($result4,$l,"description");
										$conflicts = mysql_result($result4, $l, "Conflicts");
										$capacity = mysql_result($result4, $l, "capacity");
										$msg = $conflicts." conflicts and not enough capacity: ".$capacity;
										$date = date("Y-m-d",strtotime($datetimeStart));
										makeTable($num, $desc, $msg, 2, $date, 3);
										
									}
								}
							?>
							<p>
								
								<input type="hidden" name="editeventB" value="1">
								<input type="hidden" name="title" value="<?php echo $_GET['t']; ?>">
								<input type="hidden" name="type" value="<?php echo $_GET['ty']; ?>">
								<input type="hidden" name="seats" value="<?php echo $_GET['s']; ?>">
								<input type="hidden" name="notes" value="<?php echo $_GET['n']; ?>">
								<input type="hidden" name="dateStart" value="<?php echo $datetimeStart; ?>">
								<input type="hidden" name="dateEnd" value="<?php echo $datetimeEnd; ?>">
								<input type="hidden" name="eventid" value="<?php echo $event; ?>">
								<button class="btn waves-effect waves-light" type="submit" name="action">Submit Changes
									<i class="material-icons right">send</i>
								</button>
							</p>
						</p>
					</form>
					
				</div>
				</main>
				
				
				
				<?php
				}
				} elseif ($session->CWID == $instructor){
				//Edit only notes!!!
			?>
			<main>
			<div class="row col s12" >
				Title: <?php echo $title; ?> <br>
			</div>
			<div class="row col s12" >
				Date: <?php echo $date; ?><br>
			</div>
			<div class="row col s12" >
				Start time: <?php echo $timeStart; ?><br>
			</div>
			<div class="row col s12" >
				End time: <?php echo $timeEnd; ?><br>
			</div>
			<div class="row col s12" >
				Room: <?php echo $room; ?><br>
			</div>
			<div class="row col s12" >
				Attendees: <?php echo $seats; ?><br>
			</div>
			<div class="row col s12" >
				<form action="process.php" method="POST" id="editeventC">
					<div class="row col s12">
						<div class="input-field col s12">
							<p>Notes: </p><p><input placeholder="note" id="notes" type="text" name="notes" maxlength="255" value="<?php echo $notes; ?>"></p>	
						</div>
					</div>
					<input type="hidden" name="editeventC" value="1">
					<input type="hidden" name="eventid" value="<?php echo $event; ?>">
					<button class="btn waves-effect waves-light" type="submit" name="action">Submit Changes
						<i class="material-icons right">send</i>
					</button>
				</p>
			</form>
			</main>
			<?php
			}
			
			
			
		?>
		
		
		<?php
		}
		echo "</main>";
		include("footer.php");
		// dont forget to check deadlines for editting!
		
		
		function makeTable($num, $desc, $msg, $bg, $date, $conflict){
			if ($bg == 1){ $style = 'style="background-color:#eee;"'; }
			else { $style = ''; }
			echo '<div class="room" '.$style.'>';
			echo '<div class="cutoff"><input type="radio" name="room" required value="'.$num.'#'.$conflict.'"><label>Room: '.$num.'';
			echo '<br>Desc: '.$desc.'<br>'.$msg.'</label></div>';
			echo '<table width="500px" class="single_day">
			<tr data-time="00:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>12am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="00:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="01:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>1am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="01:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="02:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>2am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="02:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="03:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>3am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="03:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="04:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>4am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="04:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="05:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>5am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="05:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="06:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>6am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="06:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="06:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>7am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="07:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="08:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>8am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="08:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="09:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>9am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="09:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="10:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>10am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="10:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="11:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>11am</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="11:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="12:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>12pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="12:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="13:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>1pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="13:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="14:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>2pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="14:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="15:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>3pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="15:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="16:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>4pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="16:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="17:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>5pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="17:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="18:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>6pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="18:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="19:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>7pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="19:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="20:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>8pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="20:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="21:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>9pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="21:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="22:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>10pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="22:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			
			<tr data-time="23:00:00">
			<td class="cell"  style="width: 42px; height:20px;"><span>11pm</span></td>
			<td class="cell"></td>
			</tr>
			<tr data-time="23:30:00" >
			<td class="cell"  style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
			</tr>
			</table>'.showRoomGrid($date, $num).'</div>';
			
			
			
			
		}
		
		
		function showRoomGrid($date, $room) {
			$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
			mysql_select_db(DB_NAME,$link);
			$q = sprintf("SELECT * FROM ".TBL_EVENTS." WHERE CAST(dateStart AS DATE) = CAST('$date' AS DATE) AND room_number = '$room' ORDER BY dateStart");
			//$myfile = fopen("error.txt", "a") or die(print_r($q));
			$result = mysql_query($q, $link);
			$previousEvents[] = "";
			if(!$result || (mysql_num_rows($result) < 1)){
				// NO EVENTS
				echo "<div class=\"wrap\"><div class=\"date\" style=\"";
				echo "\"><div class=\"inner\">";
				echo "<div class=\"title\"></div>\n";
				echo "<span class=\"time\"></span>\n";
				echo "</div></div></div>\n";
				} else {
				// EVENTS
				for ($i=0; $i < mysql_num_rows($result); $i++){
					$start_timeF = date("h:i A",strtotime(mysql_result($result,$i,"dateStart")));
					$end_timeF = date("h:i A",strtotime(mysql_result($result,$i,"dateEnd")));
					$start_timeH = date("H",strtotime(mysql_result($result,$i,"dateStart")));
					$end_timeH = date("H",strtotime(mysql_result($result,$i,"dateEnd")));
					$start_timeM = date("i",strtotime(mysql_result($result,$i,"dateStart")));
					$end_timeM = date("i",strtotime(mysql_result($result,$i,"dateEnd")));
					$start_time = date("H:i",strtotime(mysql_result($result,$i,"dateStart")));
					$end_time = date("H:i",strtotime(mysql_result($result,$i,"dateEnd")));
					$event = mysql_result($result,$i,"event_id");
					$series = mysql_result($result,$i,"series");
					$title = mysql_result($result,$i,"title");
					$room = mysql_result($result,$i,"room_number");
					$crn = mysql_result($result,$i,"crn");
					$fromTop = ((($start_timeH) + ($start_timeM / 60)) * (28*2))+24;
					$length = ((strtotime($end_time) - strtotime($start_time))/(60*60))*(28*2);
					$current[] = "";
					$current[0] = $start_timeF;
					$current[1] = $end_timeF;
					$current[2] = $title;
					$current[3] = $room;
					$current[4] = $fromTop;
					$current[5] = $length;
					$current[6] = $end_time;
					$current[7] = $event;
					$current[8] = $series;
					$current[9] = $crn;
					$previousEvents[$i]=$current;
				}
				
				// put the remaining array on the calendar once all events are added to it
				
			}
			if ($previousEvents[0] != "" && $previousEvents[0] != NULL){
				for($z = 0; $z < sizeof($previousEvents); $z++){
					$i = 1;
					for($y = 1; $y < sizeof($previousEvents); $y++){
						if ($previousEvents[$z][6] <= $previousEvents[$y][6]){
							// start time falls within previous time's range	
							$i++;
							} else {
							// start time is outside of previous time's range
							for ($j = 0; $j <= $i; $j++){
								$removed = array_shift($previousEvents);
								echo "<div class=\"wrap\"><div class=\"date\" style=\"";
								echo "height: ".$removed[5]."px; top: ".$removed[4]."px; width: ".(100 / $i)."%; left:".((100/$i)*$j)."%;";
								echo "\"><div class=\"inner\">";
								echo "<div class=\"title\"><a href='/showevent.php?e=".$removed[7]."&s=".$removed[8]."'>";
								echo $removed[2]."</a><br>".$removed[9]."<br>".$removed[3]."</div>\n";
								echo "<span class=\"time\">".$removed[0];
								echo " - ".$removed[1];
								echo "</span>\n";
								echo "</div></div></div>\n";
							}
						}
					}
					for ($f = 0; $f <= sizeof($previousEvents); $f++){
						$removed = array_shift($previousEvents);
						echo "<div class=\"wrap\"><div class=\"date\" style=\"";
						echo "height: ".$removed[5]."px; top: ".$removed[4]."px; width: ".(100 / $i)."%; left:".(100/$i)*$f."%;";
						echo "\"><div class=\"inner\">";
						echo "<div class=\"title\"><a href='/showevent.php?e=".$removed[7]."&s=".$removed[8]."'>";
						echo $removed[2]."</a><br>".$removed[9]."<br>".$removed[3]."</div>\n";
						echo "<span class=\"time\">".$removed[0];
						echo " - ".$removed[1];
						echo "</span>\n";
						echo "</div></div></div>\n";
					}
				}
			}
		}
		
	?>		
	
	
