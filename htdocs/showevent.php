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
		$q = "SELECT * FROM ".TBL_EVENTS." WHERE event_id=".$event;
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
			$creator = mysql_result($result,$i,"CWID");
			$notes = mysql_result($result,$i,"notes");
		}
	?> 
	<a href="<?php echo "./editevent.php?e=$event";  ?>">Edit Event</a>
	Title: <?php echo $title; ?> <br>
	Date: <?php echo $date; ?><br>
	Start time: <?php echo $timeStart; ?><br>
	End time: <?php echo $timeEnd; ?><br>
	Room: <?php echo $room; ?><br>
	Attendees: <?php echo $seats; ?><br>
	Creator CWID: <?php echo $creator; ?> (CHANGE TO NAME LATER)<br>
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
			$q = "SELECT * FROM ".TBL_EVENTS." WHERE series=".$series." ORDER BY dateStart ASC";
			$result = $database->query($q);
			$num_rows = mysql_numrows($result);
			for($i=0; $i<$num_rows; $i++){
				
				$title = mysql_result($result,$i,"title");
				$dateStart = mysql_result($result,$i,"dateStart");
				$dateEnd = mysql_result($result,$i,"dateEnd");
				$date = date('m-d-Y',strtotime($dateStart));
				$timeStart = date('h:i A',strtotime($dateStart));
				$timeEnd = date('h:i A',strtotime($dateEnd));
				$crn = mysql_result($result,$i,"crn");
				$eventID = mysql_result($result,$i,"event_id");
				//$myfile = fopen("error.txt", "a") or die(print_r($date." ".$timeStart." ".$timeEnd." ".$q));
				echo "<tr>";
				echo "<td><a href='./showevent.php?e=".$eventID."&s=".$series."'>".$title."</a></td>";
				echo "<td>".$date."</td>";
				echo "<td>".$timeStart."</td>";
				echo "<td>".$timeEnd."</td>";
				echo "<td>".$crn."</td>";
				echo "</tr>";
			}
			
			
		?>
	</table>					
	
	<?php
	}
	include("footer.php");
			?>					