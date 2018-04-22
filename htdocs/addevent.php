<?php
	include("top_header.php");
	$page = "addevent.php";
	echo "<main>";
	if(!$session->isInstructor() && !$session->isAdmin()){
		header("Location: index.php");
		} else {
		global $database;
		$q = "select CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END AS tail FROM ".TBL_DEADLINES." WHERE (CURRENT_TIME() BETWEEN open AND close) AND type='schedule'";
		$result = $database->query($q);
		$tail = mysql_result($result,0,"tail");
	

	?>

	<div class="Card card1">
		
		<h5><strong>Add Event<?php if ($tail==0){ echo " - Note: Only requests can be made during this time period."; } ?></strong></h5>
		
		<div class="row">
			<?php
				if (!isset($_GET['c'])){
				?>
				
				<form action="process.php" method="POST" class="col s12" id="addevent">
					
					
					<!-- gg-->
					
					<div class="row">
						<div class="input-field col s12">
						<input type="text" name="title" maxlength="30"  required value="<?php if (isset($_GET['t'])) echo $_GET['t']; ?>"></p>
						<label for="title">Title</label>
					</div>
					
					<label for="event_type">Event Type:<label>
						
						<p>
							<input type="radio" id="class1" name="type" value="0" required <?php if (isset($_GET['ty']) && $_GET['ty'] == 0){echo "checked";} ?> />
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
					
					
					
					<div class="row" >
						<div id = "seatsmessage" hidden = "true" style = "margin-left: 10px; font-family: Times, serif; font-size: 18px;">For an event with no seating requirements, please enter 0</div>
						<div class="input-field col s12"  onClick="displayMessage()">
							<input placeholder="Seats Needed" id="seats" type="number" onKeyDown="if(this.value.length==3 && event.keyCode!=8) return false;" name="seats"   required value="<?php if (isset($_GET['s'])) echo $_GET['s']; ?>" >
							
						</div>
						
					</div>
					
					<div class="row">
						<div class="input-field col s12">
							<input placeholder="Note" id="notes" type="text" name="notes" maxlength="255" value="<?php if (isset($_GET['n'])) echo $_GET['n']; ?>" >
						</div>
					</div>
					<div class="row col s12" >
						<input type="text" placeholder="Date" class = "datepicker" name="date" required ;>
						
					</div>
					
					<div class="row col s12">
						<input placeholder="Start Time" id = "startTime" class="timepicker" type="text"  name="starttime" maxlength="30" required >
						
					</div>
					
					<div class="row col s12">
						<input placeholder="End Time" id="endTime" class="timepicker" type="text" onchange="validate()" name="endtime" maxlength="30" required  >
						
					</div>
					
					
					<div>
						
						<p>
							<input type="checkbox" id="repeat" name="repeat" maxlength="30" value="1" <?php if (isset($_GET['repeat']) && $_GET['repeat']== 1){echo "checked";}?> />
							<label for="repeat">Check if you would like this to repeat.</label>
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
							<input name="re" value="<?php if (isset($_GET['re'])) echo $_GET['re']; ?>" type="date" placeholder="Repeat Until" class="datepicker">
							
						</div>
						
						
					</div>
					
					<br>
					
					<!--gg-->			
					<!--dropdown to course-->
					<div class="input-field col s12">
						<select form="addevent" name="course" value="<?php if (isset($_GET['c'])) echo $_GET['c']?>">
						<option value="" disabled selected>Select a course</option>
							<?php
							if ($session->isAdmin()){
								$q = "SELECT * FROM ".TBL_COURSE;
								} else {
								$q = "SELECT * FROM ".TBL_COURSE." WHERE Lead_Instructor = ".$session->getCWID()." OR course_number=0";
								}
								$result = $database->query($q);
								$num_rows = mysql_numrows($result);
								for($i=0; $i<$num_rows; $i++){
									$num = mysql_result($result,$i,"course_number");
									$title = mysql_result($result,$i,"title");
									echo "<option value='".$num."'>".$num." - ".$title."</option>";
								}
							?>		
						</select>
						
						<label id = "courseLabel">Course</label>
					</div>
					
					<input type="hidden" name="addeventA" value="1">
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
					<div class="row col s12" >
						Title: <?php echo $_GET['t']; ?> <br>
					</div>
					<div class="row col s12" >
						Date: <?php echo date('F d, Y', strtotime($_GET['d'])); ?><br>
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
					<div class="row col s12" >
						Notes: <?php echo $_GET['n']; ?><br>
					</div>
					<div class="row col s12" >
						Course: NURS <?php echo $_GET['c']; ?><br>
					</div>
					<form action="process.php" method="POST" id="addeventB">
						<div class="input-field col s12">
							<select name="crn[]" size=5  multiple> 
							<option value="" disabled selected>Select sections</option>
								<?php
									$q = "SELECT * FROM ".TBL_CRN." WHERE course_number = ".$_GET['c'];
									$result = $database->query($q);
									$num_rows = mysql_numrows($result);
									for($i=0; $i<$num_rows; $i++){
										$crn  = mysql_result($result,$i,"crn");
										echo "<option required value='".$crn."'>".$crn."</option>";
										
										
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
					<div class="row col s12" >
						Title: <?php echo $_GET['t']; ?> <br>
					</div>
					<div class="row col s12" >
						Date: <?php echo date('F d, Y', strtotime($_GET['d'])); ?><br>
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
					<div class="row col s12" >
						Notes: <?php echo $_GET['n']; ?><br>
					</div>
					<div class="row col s12" >
						Course: NURS <?php echo $_GET['c']; ?><br>
					</div>
					<div class="row col s12" >
						CRNs:
					<?php
						
						$crnPrint = $_GET['crn'];
						$crnPrint = substr($crnPrint, 1);
						$crns = explode(" ",$crnPrint);
						foreach ($crns as $c){
							echo "<br>".$c;			
						}
					?>
					</div>
					<form action="process.php" method="POST" id="addeventC">
						<div class="fudge">
							<div class="input-field col s12">
								<?php
									$datetimeStart = "".$_GET['d']." ".$_GET['st'].":00";
									$datetimeEnd = "".$_GET['d']." ".$_GET['et'].":00";
								$seats=0;
								if (isset($_GET['s']) && $_GET['s'] != NULL) { 
									$seats =  $_GET['s']; 
								}
								// capacity & no conflicts
								$q = "SELECT * FROM ".TBL_ROOMS." r WHERE r.capacity >= $seats AND r.room_number != 'Offsite' AND r.room_number NOT IN
(
SELECT room_number FROM ".TBL_EVENTS." e WHERE 
('$datetimeStart' > e.dateStart AND '$datetimeStart' < e.dateEnd)
OR
('$datetimeEnd' > e.dateStart AND '$datetimeEnd' < e.dateEnd)
OR
('$datetimeStart' < e.dateStart AND '$datetimeEnd' > e.dateEnd)
OR
('$datetimeStart' > e.dateStart AND '$datetimeEnd' < e.dateEnd)
AND series != 9100 AND room_number != 'Offsite' 
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
								$q2 = "SELECT room_number, description, COUNT(room_number) 'Conflicts' FROM ".TBL_EVENTS." NATURAL JOIN ".TBL_ROOMS." WHERE capacity >= $seats AND series != 9100 AND room_number != 'Offsite' AND (
(dateStart <= '$datetimeStart' AND dateEnd >= '$datetimeStart')
OR
(dateStart >= '$datetimeStart' AND dateEnd <= '$datetimeEnd')
OR
(dateStart >= '$datetimeStart' AND dateStart <= '$datetimeEnd')
OR
(dateStart <= '$datetimeStart' AND dateEnd >= '$datetimeEnd'))
GROUP BY room_number
HAVING COUNT(room_number>0)";
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
								$q3 = "SELECT * FROM ".TBL_ROOMS." r WHERE r.capacity < $seats AND r.room_number != 'Offsite' AND r.room_number NOT IN
(
SELECT room_number FROM ".TBL_EVENTS." e WHERE 
('$datetimeStart' > e.dateStart AND '$datetimeStart' < e.dateEnd)
OR
('$datetimeEnd' > e.dateStart AND '$datetimeEnd' < e.dateEnd)
OR
('$datetimeStart' < e.dateStart AND '$datetimeEnd' > e.dateEnd)
OR
('$datetimeStart' > e.dateStart AND '$datetimeEnd' < e.dateEnd)
AND series != 9100 AND room_number != 'Offsite' 
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
SELECT room_number, description, capacity, COUNT(room_number) 'Conflicts' FROM ".TBL_EVENTS." NATURAL JOIN ".TBL_ROOMS." WHERE capacity < $seats AND series != 9100 AND room_number != 'Offsite' AND (
(dateStart <= '$datetimeStart' AND dateEnd >= '$datetimeStart')
OR
(dateStart >= '$datetimeStart' AND dateEnd <= '$datetimeEnd')
OR
(dateStart >= '$datetimeStart' AND dateStart <= '$datetimeEnd')
OR
(dateStart <= '$datetimeStart' AND dateEnd >= '$datetimeEnd'))
GROUP BY room_number
HAVING COUNT(room_number>0)";								
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
								</div></div>
								
								<?php
									
									$q = sprintf("select MAX(series) AS Max from ".TBL_EVENTS." where series<9000");
									$result = $database->query($q);
									$num_rows = mysql_numrows($result);
									if ($num_rows == 0){
										echo "<h1><b>WARNING: EVENTS TABLE IS FULL. EVENTS CAN NO LONGER BE ADDED. ERRORS WILL OCCUR IF YOU CONTINUE<br>CONTACT AN ADMINISTRATOR ASAP. THE EVENTS TABLE MUST BE CLEARED, AND OLD SEMESTERS MUST BE ARCHIVED.</b></h1>";
									}
									for($i=0; $i<$num_rows; $i++){
										$max  = mysql_result($result,$i,"Max")+1;
										echo "<input type='hidden' name='series' value='".$max."'>";
									}
									
									if ($tail == 0 && !$session->isAdmin()){
										echo '<input type="hidden" name="conflict" value="1">';
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
				}
										echo "</main>";
			include("footer.php");
				
				function makeTable($num, $desc, $msg, $bg, $date, $conflict){
					if ($bg == 1){ $style = 'style="background-color:#eee;"'; }
					else { $style = ''; }
					echo '<div class="room" '.$style.'>';
					echo '<div class="cutoff"><input class="breaking_css" type="radio" required name="room" value="'.$num.'#'.$conflict.'"><label>Room: '.$num.'';
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
				
				
				<script>
				
				function validate(){
					
				}
				
				function displayMessage(){
					document.getElementById("seatsmessage").hidden = false;
				}
				
				$( document ).ready(function(){
				$(".button-collapse").sideNav();
				
				
				$('.datepicker').pickadate({
				selectMonths: true, // Creates a dropdown to control month
				selectYears: 15, // Creates a dropdown of 15 years to control year,
				today: 'Today',
				clear: 'Clear',
				close: 'Ok',
				closeOnSelect: false, // Close upon selecting a date,
				format: "mm/dd/yyyy",
				formatSubmit: "yyyy/mm/dd",

				//editable:true
				})
				
				$('.timepicker').pickatime({
				default: 'now',
				twelvehour: true, // change to 12 hour AM/PM clock from 24 hour
				donetext: 'OK',
				autoclose: false,
				editable: true
				})
				
				$(document).ready(function() {
				$('select').material_select();
				});
				
				});
				
			
				
				</script>																