<?php
	include("top_header.php");
	
	/**
		* User not an administrator, redirect to main page
		* automatically.
	*/
	if(!$session->isAdmin()){
		header("Location: index.php");
	}
	else{
		/**
			* Administrator is viewing page, so display all
			* forms.
		*/
		$event = $_GET['e'];
		if (isset($event) && $event != NULL && $event != 0){
			global $database;
			$q = "SELECT e.title AS etit, e.crn, e.attendees, e.type, e.room_number, e.notes, e.dateStart, e.dateEnd, e.status, c.course_number, c.title AS ctit, u.name from ".TBL_EVENTS." e JOIN ".TBL_CRN." s on e.crn = s.crn JOIN ".TBL_COURSE." c on s.course_number = c.course_number JOIN ".TBL_USERS." u on e.cwid = u.cwid where e.event_id = $event";
			$result = $database->query($q);
			//$myfile = fopen("error.txt", "a") or die(print_r($q));
			$num_rows = mysql_numrows($result);
			for($i=0; $i<$num_rows; $i++){
				$title = mysql_result($result,$i,"etit");
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
				$type = mysql_result($result,$i,"type");
				$coursenum = mysql_result($result,$i,"course_number");
				$coursetit = mysql_result($result,$i,"ctit");
				$crn = mysql_result($result,$i,"crn");
			}
		?>
		<div class="card card-2">
			<h1 style="text-align:center">Selected Event:</h1>
			<table class="highlight" style="width:100%">
				<thead>
				<tr>
					<th>Title</th>
					<th>Date</th> 
					<th>Start time</th>
					<th>End time</th>
					<th>Course - CRN</th>
					<th>Room</th>
					<th>Status</th>
					<th>Attendees</th>
					<th>Creator</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $title; ?></td>
					<td><?php echo $date; ?></td>
					<td><?php echo $timeStart; ?></td>
					<td><?php echo $timeEnd; ?></td>
					<td><?php echo $coursenum." - ".$crn; ?></td>
					<td><?php echo $room; ?></td>
					<td><?php echo $status; ?></td>
					<td><?php echo $seats; ?></td>
					<td><?php echo $creator; ?></td>
				</tr>
				</tbody>
			</table>
			<br>
			<div id="out">
				<div class="in">
			<form action="process.php" method="POST" id="approve" class="col s12">
				<input type="hidden" name="approve" value="1">
				<input type="hidden" name="eventid" value="<?php echo $event; ?>">
				<button class="btn waves-effect waves-light" type="submit" name="action">Approve
					<i class="material-icons right">send</i>
				</button>

			</form>
		</div>
		<div class="in">
			<form action="process.php" method="POST" id="reject" class="col s12">
				<input type="hidden" name="reject" value="1">
				<input type="hidden" name="eventid" value="<?php echo $event; ?>">
				<button class="btn waves-effect waves-light" type="submit" name="action">Reject
					<i class="material-icons right">send</i>
				</button>
			</form>
		</div>
		</div>
		</div>
		
		<?php
			// if no conflicting events, say none found.
			$q2 ="SELECT e.event_id, e.title AS etit, e.crn, e.attendees, e.type, e.room_number, e.notes, e.dateStart, e.dateEnd, e.status, c.course_number, c.title AS ctit, u.name from ".TBL_EVENTS." e JOIN ".TBL_CRN." s on e.crn = s.crn JOIN ".TBL_COURSE." c on s.course_number = c.course_number JOIN ".TBL_USERS." u on e.cwid = u.cwid where 
			('$dateStart' > e.dateStart AND '$dateStart' < e.dateEnd)
			OR
			('$dateEnd' > e.dateStart AND '$dateEnd' < e.dateEnd)
			OR
			('$dateStart' < e.dateStart AND '$dateEnd' > e.dateEnd)
			OR
			('$dateStart' > e.dateStart AND '$dateEnd' < e.dateEnd)
			AND series != 9100 AND room_number != 'Offsite' ";
			$result2 = $database->query($q2);
			//$myfile = fopen("error.txt", "a") or die(print_r($q));
			$num_rows2 = mysql_numrows($result2);
			for($i2=0; $i2<$num_rows2; $i2++){
				$title2 = mysql_result($result2,$i2,"etit");
				$dateStart2 = mysql_result($result2,$i2,"dateStart");
				$dateEnd2 = mysql_result($result2,$i2,"dateEnd");
				$date2 = date('m-d-Y',strtotime($dateStart2));
				$timeStart2 = date('h:i A',strtotime($dateStart2));
				$timeEnd2 = date('h:i A',strtotime($dateEnd2));
				$room2 = mysql_result($result2,$i2,"room_number");
				$seats2 = mysql_result($result2,$i2,"attendees");
				$creator2 = mysql_result($result2,$i2,"name");
				$notes2 = mysql_result($result2,$i2,"notes");
				$status2 = mysql_result($result2,$i2,"status");
				$type2 = mysql_result($result2,$i2,"type");
				$coursenum2 = mysql_result($result2,$i2,"course_number");
				$coursetit2 = mysql_result($result2,$i2,"ctit");
				$crn2 = mysql_result($result2,$i2,"crn");
				$event_id = mysql_result($result2,$i2,"event_id");
			}
			
		?>
		
		<div class="card card-2">
			<h1 style="text-align:center">Conflicting Events:</h1>
			<?php if ($num_rows2 == 0){
					echo "No events are causing a conflict. Perhaps the event was created outside of the scheduling deadline, the room didn't have enough capacity, or a previous conflicting event has been changed.";
				} else {
				?>
			<table class="highlight" style="width:100%">
				<thead>
				<tr>
					<th>Title</th>
					<th>Date</th> 
					<th>Start time</th>
					<th>End time</th>
					<th>Course - CRN</th>
					<th>Room</th>
					<th>Status</th>
					<th>Attendees</th>
					<th>Creator</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><a href="/viewconflict.php?e=<?php echo $event_id;?>"><?php echo $title2; ?></a></td>
					<td><?php echo $date2; ?></td>
					<td><?php echo $timeStart2; ?></td>
					<td><?php echo $timeEnd2; ?></td>
					<td><?php echo $coursenum2." - ".$crn2; ?></td>
					<td><?php echo $room2; ?></td>
					<td><?php echo $status2; ?></td>
					<td><?php echo $seats2; ?></td>
					<td><?php echo $creator2; ?></td>
				</tr>
				</tbody>
			</table>
		</div>
		<?php
				}
			} else {
			header("Location: index.php");
		}
	?>
	
	

<?php
}
include "footer.php";
?>