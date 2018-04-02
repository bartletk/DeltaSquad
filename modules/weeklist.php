<?php


function showWeek() {
		if(!$session->isInstructor() & !$session->isAdmin()){
			if (isset($_GET['crn'])){
				$crns[] = explode(" ", trim($_GET['crn']));
				//print_r($crns);
				$dateNew = substr_replace(substr_replace($date, "-", 6, 0), "-", 4, 0);
				$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
				mysql_select_db(DB_NAME,$link);
				$q = sprintf("SELECT * FROM ".TBL_EVENTS." JOIN ".TBL_ROOMS." ON ".TBL_EVENTS.".room_number = ".TBL_ROOMS.".room_number WHERE (CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)) AND (crn IN (".implode(',', $crns[0])."))");
				$result = mysql_query($q, $link);
				if(!$result || (mysql_num_rows($result) < 1)){
					// NO EVENTS
					} else {
					// EVENTS
					while($row = mysql_fetch_assoc($result)) {
						echo $row['title'];
						echo "<br> ".substr($row[date_start], 10, -3)." -".substr($row[date_end], 10, -3)."<br> Room:";
						echo $row['room_number'];
						echo "<br><br>";
					}
				}
				} else {
				header ("Location: class_select.php");
			}
			} elseif (!$session->isAdmin() & $session->isInstructor()) {
			$dateNew = substr_replace(substr_replace($date, "-", 6, 0), "-", 4, 0);
			$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
			mysql_select_db(DB_NAME,$link);
			$q = sprintf("SELECT * FROM ".TBL_EVENTS." WHERE CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)");
			$result = mysql_query($q, $link);
			if(!$result || (mysql_num_rows($result) < 1)){
				// NO EVENTS
				} else {
				// EVENTS
				//$dbarray = mysql_fetch_array($result);
				while($row = mysql_fetch_assoc($result)) {
					echo $row['title'];
					echo "<br> ".substr($row[dateStart], 10, -3)." -".substr($row[dateEnd], 10, -3)."<br> Room:";
					echo $row['room_number'];
					echo "<br><br>";
				}
			}
			
			} else {
			$dateNew = substr_replace(substr_replace($date, "-", 6, 0), "-", 4, 0);
			$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
			mysql_select_db(DB_NAME,$link);
			$q = sprintf("SELECT * FROM ".TBL_EVENTS." WHERE CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)");
			$result = mysql_query($q, $link);
			if(!$result || (mysql_num_rows($result) < 1)){
				// NO EVENTS
				} else {
				// EVENTS
				//$dbarray = mysql_fetch_array($result);
				while($row = mysql_fetch_assoc($result)) {
					echo $row['title'];
					echo "<br> ".substr($row[dateStart], 10, -3)." -".substr($row[dateEnd], 10, -3)."<br> Room:";
					echo $row['room_number'];
					echo "<br><br>";
				}
			}
		}
}


$thisday = $y."-".$m."-".$a;
$nextseven =  $next["seven"]["y"]."-".$next["seven"]["m"]."-".$next["seven"]["a"];

grab($thisday,$nextseven,$c);
showWeek();
?>
