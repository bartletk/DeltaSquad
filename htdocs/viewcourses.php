<?php
	include("top_header.php");
	$page = "mycourse.php";
	if(!$session->isInstructor() && !$session->isAdmin() && !isset($_GET['crn'])){
	?>
		You have reached this page in error. Either you are not an instructor or admin, or you have not selected a course to view. Please try again.
		
		<?php
		} else { 
		?>
		
			<table style="width:100%">
		<tr>
			<th>Title</th>
			<th>Date</th> 
			<th>Start time</th>
			<th>End time</th>
			<th>Room</th>
			<th>Type</th>
		</tr>
		
		<?php
		$crn = $_GET['crn'];
		global $database;
		$q = "SELECT * FROM ".TBL_EVENTS." WHERE crn=".$crn." ORDER BY dateStart ASC";
		$result = $database->query($q);
		$num_rows = mysql_numrows($result);
		for($i=0; $i<$num_rows; $i++){
			$event = mysql_result($result,$i,"event_id");
			$title = mysql_result($result,$i,"title");
			$type = mysql_result($result,$i,"type");
			$dateStart = mysql_result($result,$i,"dateStart");
			$dateEnd = mysql_result($result,$i,"dateEnd");
			$date = date('m-d-Y',strtotime($dateStart));
			$timeStart = date('h:i A',strtotime($dateStart));
			$timeEnd = date('h:i A',strtotime($dateEnd));
			$room = mysql_result($result,$i,"room_number");
			$series = mysql_result($result,$i,"series");
			$status = mysql_result($result,$i,"status");
				echo "<tr>";
				echo "<td><a href='./showevent.php?e=".$event."&s=".$series."'>".$title."</a></td>";
				echo "<td>".$date."</td>";
				echo "<td>".$timeStart."</td>";
				echo "<td>".$timeEnd."</td>";
				echo "<td>".$room."</td>";
				echo "<td>".$type."</td>";
				echo "</tr>";
		}
		
		?>
		
		</table>
		<?php
		
		}
		include("footer.php");
?>