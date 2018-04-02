<?php
	include("top_header.php");
	$page = "mycourse.php";
	$CWID = $session->getCWID();
?>
<link rel="stylesheet" type="text/css" href="css/mycourse.css">
<div class="card wrap">
	<h5>My Courses</h5>
	<?php
		/*
			<a class="button yellow" href="#">Database</a>
			<a class="button red" href="#">Archi</a>
			<a class="button green" href="#">Algorithm</a>
			<a class="button grey" href="#">DataStructure</a>
		*/
		
		
		
		$q = "SELECT * FROM ".TBL_COURSE." JOIN ".TBL_CRN." ON course.course_number = section.course_number WHERE Lead_Instructor = $CWID OR Instructor = $CWID";
		$result = $database->query($q);
		$num_rows = mysql_numrows($result);
		for($i=0; $i<$num_rows; $i++){
			$crn  = mysql_result($result,$i,"crn");
			$num = mysql_result($result,$i,"course_number");
			$title = mysql_result($result,$i,"title");
			$button = "button grey";
			$q2 = "SELECT status FROM ".TBL_EVENTS." WHERE CRN = $crn";
			$result2 = $database->query($q2);
			$num_rows2 = mysql_numrows($result2);
			$button = "button grey";
			$status = 0;
			//$myfile = fopen("error.txt", "a") or die($q2);
			if ($num_rows2 > 0){
				$statusLevel = 0;
				
				for($j=0; $j<$num_rows2; $j++){
					$statusTest = mysql_result($result2,$j,"status");
					if ($statusTest == "approved"){
						$statusLevel = 1;
						} else if ($statusTest == "pending"){
						$statusLevel = 2;
						} else if ($statusTest == "resubmit"){ 
						$statusLevel = 3;
						} else if ($statusTest == "rejected"){
						$statusLevel = 4;
					}
					if ($statusLevel > $status){
						$status = $statusLevel;
					}
					
				}
				if ($status == 1){
					$button = "button green";
					} else if ($status == 2){
					$button = "button yellow"; 
					} else if ($status > 2) {
					$button = "button red";
				}
			}
			if ($button == "button grey"){
			echo "<a class=\"$button\" href=\"./addevent.php\">";
			} else {
			echo "<a class=\"$button\" href=\"./viewcourses.php?crn=$crn\">";
			}
			echo "NURS $num <br>";
			echo "$title <br>";
			echo "$crn <br>";			
			echo "</a>";
		}
		
	?>
	
</div>
<?php
	include("footer.php");
?>			