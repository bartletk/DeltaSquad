<?php
	include("top_header.php");
	$page = "showevent.php";
	if(!isset($_GET['e']) || !isset($_GET['s'])){
	?>
	You have made it here by mistake. Please go back to the calendar and select an event to view.
	<?php
		} else {
		global $database;
		$event = $_GET['e'];
		$series = $_GET['s'];
		$q = "SELECT * FROM ".TBL_EVENTS." JOIN ".TBL_USERS." ON ".TBL_EVENTS.".CWID = ".TBL_USERS.".CWID WHERE event_id=".$event;
		$result = $database->query($q);
		$num_rows = mysql_numrows($result);
		for($i=0; $i<$num_rows; $i++){
			$title = mysql_result($result,$i,"title");
			$dateStart = mysql_result($result,$i,"dateStart");
			$dateEnd = mysql_result($result,$i,"dateEnd");
			$date = date('m-d-Y',strtotime($dateStart));
			$timeStart = date('h:i A',strtotime($dateStart));
			$timeEnd = date('h:i A',strtotime($dateEnd));
			$room = mysql_result($result,$i,"room_number");
			$seats = mysql_result($result,$i,"attendees");
			$creator = mysql_result($result,$i,"name");
			$notes = mysql_result($result,$i,"notes");
			$status = mysql_result($result,$i,"status");
		}
	?> 
	<a href="<?php echo "./editevent.php?e=$event";  ?>">Edit Event</a>
	
	<?php
		$q3 = "select count(*) conflicts from ".TBL_EVENTS." where series = $series AND status != 'approved' group by status";
		$result3 = $database->query($q3);
		if (mysql_numrows($result3)){
		$conflictsInSeries = mysql_result($result3,0,"conflicts");
		} else {
		$conflictsInSeries = 0;
		}
		if($session->isAdmin() && $status != 'approved'){
		?>
		<form action="process.php" method="POST" id="approve" class="col s12">
			<input type="hidden" name="approve" value="1">
			<input type="hidden" name="eventid" value="<?php echo $event; ?>">
			<button class="btn waves-effect waves-light" type="submit" name="action">Approve this event
				<i class="material-icons right">send</i>
			</button>
		</form>
		<?php
		} else if ($session->isAdmin() && $conflictsInSeries > 0){
		?>
		<form action="process.php" method="POST" id="approveall" class="col s12">
			<input type="hidden" name="approveall" value="1">
			<input type="hidden" name="seriesid" value="<?php echo $series; ?>">
			<button class="btn waves-effect waves-light" type="submit" name="action">Approve all events in series
				<i class="material-icons right">send</i>
			</button>
		</form>
		<?php
		}
	?>
	
	Title: <?php echo $title; ?> <br>
	Date: <?php echo $date; ?><br>
	Start time: <?php echo $timeStart; ?><br>
	End time: <?php echo $timeEnd; ?><br>
	Room: <?php echo $room; ?><br>
	Attendees: <?php echo $seats; ?><br>
	Event Creator: <?php echo $creator; ?><br>
	Notes: <?php echo $notes; ?><br>
	
	Other events in this series:
	<table style="width:100%">
		<tr>
			<th>Title</th>
			<th>Date</th> 
			<th>Start time</th>
			<th>End time</th>
			<th>CRN</th>
		</tr>
		<?php
			$q2 = "SELECT * FROM ".TBL_EVENTS." WHERE series=".$series." ORDER BY dateStart ASC";
			//$myfile = fopen("error.txt", "a") or die(print_r($q2));
			$result2 = $database->query($q2);
			if ($result2){
			$num_rows2 = mysql_numrows($result2);
			if ($num_rows2 > 0){
			for($j=0; $j<$num_rows2; $j++){
				$title2 = mysql_result($result2,$j,"title");
				$dateStart2 = mysql_result($result2,$j,"dateStart");
				$dateEnd2 = mysql_result($result2,$j,"dateEnd");
				$date2 = date('m-d-Y',strtotime($dateStart2));
				$timeStart2 = date('h:i A',strtotime($dateStart2));
				$timeEnd2 = date('h:i A',strtotime($dateEnd2));
				$crn2 = mysql_result($result2,$j,"crn");
				$eventID2 = mysql_result($result2,$j,"event_id");
				echo "<tr>";
				echo "<td><a href='./showevent.php?e=".$eventID2."&s=".$series."'>".$title2."</a></td>";
				echo "<td>".$date2."</td>";
				echo "<td>".$timeStart2."</td>";
				echo "<td>".$timeEnd2."</td>";
				echo "<td>".$crn2."</td>";
				echo "</tr>";
			}
			}
			}
		?>
	</table>					
	
	<?php
	}
	include("footer.php");
?>					