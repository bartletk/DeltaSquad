<?php
	 include("top_header.php");
	 $page = "roomview.php";
	
	 global $database;	
//function showRooms($date){
	$date = date("Y-m-d",time() - 60 * 60 * 24);
	 echo "<div class='scrollable'><div class='roomInner'>";
	 
							$q = "SELECT * FROM ".TBL_ROOMS;
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$num = mysql_result($result,$i,"room_number");
								$desc = mysql_result($result,$i,"description");
								echo '<div class="room">';
								echo '<div class="cutoff"><label>Room Number: '.$num.' </label>';
								echo '<br> <label style="">Description: '.$desc.' </label></div>';
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
			<td class="cell" style="width: 42px; height:20px;"></td>
			<td class="cell" ></td>
		</tr>
	</table>'.showRoomGrid($date, $num).'</div>';

							}
							 
							echo "</div></div>";
//}				
// if not isset date,
// display by default, today's date
// clicky clicky arrows makes date change
// clicky clicky date brings up a date-picker
// clicky clicky room title brings up week view of that room
// should we do a month view??
	function showRoomGrid($date, $room) {
		$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
		mysql_select_db(DB_NAME,$link);
		$q = sprintf("SELECT * FROM ".TBL_EVENTS." WHERE CAST(dateStart AS DATE) = CAST('$date' AS DATE) AND room_number = '$room' ORDER BY dateStart");
		//$myfile = fopen("error.txt", "a") or die(print_r($q));
		$result = mysql_query($q, $link);
		$previousEvents[] = "";
		if(!$result || (mysql_num_rows($result) < 1)){
			// NO EVENTS
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
		if ($previousEvents[0] != ""){
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

<?php
	include "footer.php";
	?>