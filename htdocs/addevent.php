<?php
	// When uncommented, the php doesnot shows
	include("top_header.php");
	//include("header.php");
	$page = "addevent.php";
	if(!$session->isInstructor() && !$session->isAdmin()){
		header("Location: index.php");
		} else {
		global $database;
		
		
		
		$q = "select CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END AS tail FROM ".TBL_DEADLINES." WHERE (CURRENT_TIME() BETWEEN open AND close) AND type='schedule'";
		$result = $database->query($q);
		$tail = mysql_result($result,0,"tail");
		if ($tail == 0 || $session->isAdmin()) {
		?>
		<div class="Card card1">
			
			<h5><strong>Add Event</strong></h5>
			
			<div class="row">
				<?php
					if($form->num_errors > 0){
						echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
					}
					if (!isset($_GET['c'])){
					?>
					
					<form action="process.php" method="POST" class="col s12" id="addevent">
						
						
						<!-- gg-->
						
						<div class="row">
							<div class="input-field col s12">
							<input type="text" name="title" maxlength="30" value="<?php if (isset($_GET['t'])) echo $_GET['t']; ?>"></p>
							<label for="title">Title</label>
						</div>
						
						<label for="eveny_type">Event Type:<label>
							
							<p>
								<input type="radio" id="class1" name="type" value="0" <?php if (isset($_GET['ty']) && $_GET['ty'] == 0){echo "checked";} ?> />
								<label for="class1">Class</label>
							</p>
							<p>
								<input type="radio" id="Clinical" name="type" value="1" <?php if (isset($_GET['ty']) && $_GET['ty'] == 1){echo "checked";} ?> />
								<label for="Clinical">Clinical</label>
							</p>
							<p>
								<input type="radio" id="exam" name="type" value="2" <?php if (isset($_GET['ty']) && $_GET['ty'] == 2){echo "checked";} ?> />
								<label for="exam">Exam</label>
							</p>
							<p>
								<input type="radio" id="event" name="type" value="3" <?php if (isset($_GET['ty']) && $_GET['ty'] == 3){echo "checked";} ?>  />
								<label for="event">Event</label>
							</p>
							
						</div>
						
						
						
						<div class="row">
							<div class="input-field col s12">
								<input placeholder="seats needed" id="seats" type="text"  maxlength="30" value="<?php if (isset($_GET['s'])) echo $_GET['s']; ?>">
							</div>
						</div>
						
						<div class="row">
							<div class="input-field col s12">
								<input placeholder="note" id="notes" type="text" name="notes" maxlength="255" value="<?php if (isset($_GET['n'])) echo $_GET['n']; ?>" >
							</div>
						</div>
						<div class="row col s12" >
							<input type="text" placeholder="date" class="datepicker" name="date">
							
						</div>
						
						<div class="row col s12">
							<input placeholder="start time" type="text" class="timepicker" name="starttime" maxlength="30" >
							
						</div>
						
						<div class="row col s12">
							<input placeholder="end time" type="text" class="timepicker" name="endtime" maxlength="30" >
							
						</div>
						
						
						<div>
							
							<p>
								<input type="checkbox" id="repeat" name="repeat" maxlength="30" value="1" <?php if (isset($_GET['repeat']) && $_GET['repeat']== 1){echo "checked";}?> />
								<label for="repeat">check if you want to repeat</label>
							</p>
							
							<p>
								<input type="checkbox" name="repeatm" maxlength="30" value="1" <?php if (isset($_GET['repeatm']) && $_GET['repeatm'] == 1){echo "checked";}?> id="repeatm" />
								<label for="repeatm">Monday</label>
							</p>
							
							<p>
								<input type="checkbox" name="repeatt" maxlength="30" value="1" <?php if (isset($_GET['repeatt']) && $_GET['repeatt']== 1){echo "checked";}?> id="repeatt" />
								<label for="repeatt">Tuesday</label>
							</p>
							
							<p>
								<input type="checkbox" name="repeatw" maxlength="30" value="1" <?php if (isset($_GET['repeatw']) && $_GET['repeatw']== 1){echo "checked";}?> id="repeatw" />
								<label for="repeatw">Wednesday</label>
							</p>
							
							<p>
								<input type="checkbox" name="repeatth" maxlength="30" value="1" <?php if (isset($_GET['repeatth']) && $_GET['repeatth']== 1){echo "checked";}?> id="repeatth"/>
								<label for="repeatth">Thursday</label>
							</p>
							
							<p>
								<input type="checkbox" name="repeatf" maxlength="30" value="1" <?php if (isset($_GET['repeatf']) && $_GET['repeatf']== 1){echo "checked";}?> id="repeatf"/>
								<label for="repeatf">Friday</label>
							</p>
							
							
						</div>
						
						<br>
						<div>
							<div class="row col s12" >
								<input name="re" value="<?php if (isset($_GET['re'])) echo $_GET['re']; ?>" type="text" placeholder="Repeat Until" class="datepicker">
								
							</div>
							
							
						</div>
						
						<br>
						
						<!--gg-->			
						<!--dropdown to course-->
						<div class="input-field col s12">
							<select form="addevent" name="course" value="<?php if (isset($_GET['c'])) echo $_GET['c']?>">
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
							</select>
							
							<label>Course</label>
						</div>
						
						<input type="hidden" name="addeventA" value="1">
						<input type="button" name="submit" value="Submit">
						<button class="btn waves-effect waves-light" type="submit" name="action">Next
							<i class="material-icons right">send</i>
						</button>
						<hr>
						<hr>
						
						<!--dropdown to select crn-->	
						</form>
						
						
						<?php
						}
						if (isset($_GET['c']) & !isset($_GET['crn'])){
							$courses[] = explode(" ", trim($_GET['c']));
							
							
						?>
						
						<form action="process.php" method="POST" id="addeventB">
							<div class="input-field col s12">
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
								<label>CRN</label>
								
								<input type="hidden" name="title" value="<?php echo $_GET['t']?>">
								<input type="hidden" name="type" value="<?php echo $_GET['ty']?>">
								<input type="hidden" name="course" value="<?php echo $_GET['c']?>">
								<input type="hidden" name="seats" value="<?php echo $_GET['s']?>">
								<input type="hidden" name="notes" value="<?php echo $_GET['n']?>">
								<input type="hidden" name="date" value="<?php echo $_GET['d']?>">
								<input type="hidden" name="starttime" value="<?php echo $_GET['st']?>">
								<input type="hidden" name="endtime" value="<?php echo $_GET['et']?>">
								<input type="hidden" name="repeat" value="<?php echo $_GET['repeat']?>">
								<input type="hidden" name="repeatm" value="<?php echo $_GET['repeatm']?>">
								<input type="hidden" name="repeatt" value="<?php echo $_GET['repeatt']?>">
								<input type="hidden" name="repeatw" value="<?php echo $_GET['repeatw']?>">
								<input type="hidden" name="repeatth" value="<?php echo $_GET['repeatth']?>">
								<input type="hidden" name="repeatf" value="<?php echo $_GET['repeatf']?>">
								<input type="hidden" name="re" value="<?php echo $_GET['re']?>">
								<input type="hidden" name="addeventB" value="1">
							</div>
							<button class="btn waves-effect waves-light" type="submit" name="action">Pick Room
								<i class="material-icons right">send</i>
							</button>
							<hr>
							<hr>
						</form>
						<?php 
						}
						if (isset($_GET['crn'])){
						?>
						
						<form action="process.php" method="POST" id="addeventC">
							<div class="input-field col s12">
								<?php
									$datetimeStart = "".$_GET['d']." ".$_GET['st'].":00";
									$datetimeEnd = "".$_GET['d']." ".$_GET['et'].":00";
									$q = "SELECT DISTINCT room_number, description FROM ".TBL_ROOMS." WHERE room_number = 'Offsite' OR NOT EXISTS (SELECT * FROM ".TBL_ROOMS.", ".TBL_EVENTS." where ".TBL_EVENTS.".dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND ".TBL_EVENTS.".dateStart <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s') AND ".TBL_ROOMS.".room_number = ".TBL_EVENTS.".room_number)";
									$result = $database->query($q);
									$num_rows = mysql_numrows($result);
									for($i=0; $i<$num_rows; $i++){
										$num  = mysql_result($result,$i,"room_number");
										$desc = mysql_result($result,$i,"description");
										$msg = "";
										makeTable($num, $desc, $msg, 0);
									}
									$q2 = "SELECT COUNT(room_number) 'Conflicts',room_number, description FROM ".TBL_ROOMS." WHERE room_number != 'Offsite' AND EXISTS (SELECT * FROM ".TBL_ROOMS.", ".TBL_EVENTS." where ".TBL_EVENTS.".dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND ".TBL_EVENTS.".dateStart <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s') AND ".TBL_ROOMS.".room_number = ".TBL_EVENTS.".room_number) GROUP BY room_number ORDER BY COUNT(room_number)";
									$myfile = fopen("error.txt", "a") or die(print_r($q2));
									$result2 = $database->query($q2);
									$num_rows2 = mysql_numrows($result2);
									for($j=0; $j<$num_rows2; $j++){
										$num  = mysql_result($result2,$j,"room_number");
										$desc = mysql_result($result2,$j,"description");
										$conflicts = mysql_result($result2, $j, "Conflicts");
										$msg = $conflicts." conflicts";
										makeTable($num, $desc, $msg, 1);
									}	
								?>
								
								<label>Room</label>
							</div>
							
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
							<input type="hidden" name="repeat" value="<?php echo $_GET['repeat']?>">
							<input type="hidden" name="repeatm" value="<?php echo $_GET['repeatm']?>">
							<input type="hidden" name="repeatt" value="<?php echo $_GET['repeatt']?>">
							<input type="hidden" name="repeatw" value="<?php echo $_GET['repeatw']?>">
							<input type="hidden" name="repeatth" value="<?php echo $_GET['repeatth']?>">
							<input type="hidden" name="repeatf" value="<?php echo $_GET['repeatf']?>">
							<input type="hidden" name="re" value="<?php echo $_GET['re']?>">
							
							
							<button class="btn waves-effect waves-light" type="submit" name="action">Add Event
								<i class="material-icons right">send</i>
							</button>								
						</form>
						<?php
							
						}
						} else {
						echo "This form is not available at the current time. Requests will be implemented later. We apologize for the inconvenience.";
					?>
				</div>
			</div>
			
			
			
			
			
		</body>
	</html>
	<?php
	} 
}


function makeTable($num, $desc, $msg, $bg){
	if ($bg == 1){ $style = 'style="background-color:#eee;"'; }
	else if ($bg == 0){ $style = ''; }
	echo '<div class="room" '.$style.'>';
	echo '<div class="cutoff"><input type="radio" name="room" value="'.$num.'"><label>Room Number: '.$num.' </label>';
	echo '<br> <label>Description: '.$desc.' </label><br> <label>'.$msg.'</label></div>';
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
	</table>'.'placeholder'.'</div>';
	
}

?>		
<script>
	
	$( document ).ready(function(){
		$(".button-collapse").sideNav();
		
		
		$('.datepicker').pickadate({
			selectMonths: true, // Creates a dropdown to control month
			selectYears: 15, // Creates a dropdown of 15 years to control year,
			today: 'Today',
			clear: 'Clear',
			close: 'Ok',
			closeOnSelect: false, // Close upon selecting a date,
			formatSubmit: 'yyyy/mm/dd'
			
		})
		$('.timepicker').pickatime({
			default: 'now',
			twelvehour: true, // change to 12 hour AM/PM clock from 24 hour
			donetext: 'OK',
			autoclose: false
			//vibrate: true // vibrate the device when dragging clock hand
		})
		$(document).ready(function() {
			$('select').material_select();
		});
        
	});
	
	
	
	</script>			