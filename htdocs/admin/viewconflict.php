<link rel="stylesheet" type="text/css" href="admin.css">
<?php
	include("../top_header.php");
	
	/**
		* User not an administrator, redirect to main page
		* automatically.
	*/
	if(!$session->isAdmin()){
		header("Location: ../index.php");
	}
	else{
		/**
			* Administrator is viewing page, so display all
			* forms.
		*/
		$event = $_GET['e'];
		if (isset($event) && $event != NULL && $event != 0){
			global $database;
			$q = "SELECT e.title AS etit, e.attendees, e.type, e.room_number, e.notes, e.dateStart, e.dateEnd, e.status, c.course_number, c.title AS ctit, u.name from ".TBL_EVENTS." e JOIN ".TBL_CRN." s on e.crn = s.crn JOIN ".TBL_COURSE." c on s.course_number = c.course_number JOIN ".TBL_USERS." u on e.cwid = u.cwid where e.event_id = $event";
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
				} else {
				header("Location: /admin/conflicts.php");
			}
		?>
		
	
	
	<?php
	}
	include "../footer.php";
?>