<?php
	
	function showGrid($date) {
		GLOBAL $session;
		if(!$session->isInstructor() & !$session->isAdmin()){
			if ($session->isStudent()){
				$dateNew = substr_replace(substr_replace($date, "-", 6, 0), "-", 4, 0);
				$CWID = $session->getCWID();
				$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
				mysql_select_db(DB_NAME,$link);
				$q = sprintf("SELECT * from ".TBL_EVENTS." join ".TBL_SCHED." on ".TBL_SCHED.".crn = ".TBL_EVENTS.".crn where ".TBL_SCHED.".cwid = $CWID AND CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE) AND status='approved'");
				//$myfile = fopen("error.txt", "a") or die(print_r($q));
				$result = mysql_query($q, $link);
				if(!$result || (mysql_num_rows($result) < 1)){
					// NO EVENTS
					} else {
					// EVENTS
					while($row = mysql_fetch_assoc($result)) {
						echo "<a href='/showevent.php?e=".$row['event_id']."&s=".$row['series']."'>".$row['title']."</a>";
						echo "<br> ".date('g:i a',strtotime($row[dateStart]))."-".date('g:i a',strtotime($row[dateEnd]))."<br> Room:";
						echo $row['room_number'];
						echo "<br><br>";
					}
				}
				} else {
				header ("Location: class_select.php");
			}
			} elseif (!$session->isAdmin() & $session->isInstructor()) {
			
			$dateNew = substr_replace(substr_replace($date, "-", 6, 0), "-", 4, 0);
			$CWID = $session->getCWID();
			$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
			mysql_select_db(DB_NAME,$link);
			if (isset($sem) && ($sem != 0) && ($sem != NULL)){

			$q = sprintf("select * from ".TBL_EVENTS." join ".TBL_CRN." ON ".TBL_EVENTS.".crn = ".TBL_CRN.".crn join ".TBL_COURSE." on ".TBL_COURSE.".course_number = ".TBL_CRN.".course_number where (".TBL_CRN.".instructor = $CWID OR ".TBL_COURSE.".Lead_Instructor = $CWID) AND CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)");
			
			
			//$myfile = fopen("error.txt", "a") or die(print_r($q));
				} else {

		$q = sprintf("select ".TBL_EVENTS.".* from ".TBL_EVENTS." join ".TBL_CRN." ON ".TBL_EVENTS.".crn = ".TBL_CRN.".crn join ".TBL_COURSE." on ".TBL_COURSE.".course_number = ".TBL_CRN.".course_number where (".TBL_CRN.".instructor = $CWID OR ".TBL_COURSE.".Lead_Instructor = $CWID) AND CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)");
		
				
				
				
				//$myfile = fopen("error.txt", "a") or die(print_r($q));
			}
			$result = mysql_query($q, $link);
			if(!$result || (mysql_num_rows($result) < 1)){
				// NO EVENTS
				} else {
				// EVENTS
				while($row = mysql_fetch_assoc($result)) {
				if ($row['status'] != 'approved') {
					$style = "style='color: white; text-shadow: 1px 1px 2px black, 0 0 25px yellow, 0 0 5px orange;'";
					} else {
					$style = "";
					}
					echo "<a href='/showevent.php?e=".$row['event_id']."&s=".$row['series']."' $style>".$row['title']."</a>";
					echo "<br> ".date('g:i a',strtotime($row[dateStart]))."-".date('g:i a',strtotime($row[dateEnd]))."<br> Room:";
					echo $row['room_number'];
					echo "<br><br>";
				}
			}
			
			} else {
			$dateNew = substr_replace(substr_replace($date, "-", 6, 0), "-", 4, 0);
			$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
			mysql_select_db(DB_NAME,$link);
			if (isset($sem) && ($sem != 0) && ($sem != NULL)){
				// change to all of a semester's classes
				$q = sprintf("select * from ".TBL_EVENTS.", ".TBL_CRN.", ".TBL_COURSE." where (".TBL_CRN.".instructor = $CWID and ".TBL_CRN.".crn=".TBL_EVENTS.".crn) OR (".TBL_COURSE.".Lead_Instructor = $CWID and ".TBL_CRN.".course_number = ".TBL_COURSE.".course_number and ".TBL_CRN.".crn = ".TBL_EVENTS.".crn) AND CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)");
				
				} else {
				$q = sprintf("SELECT * FROM ".TBL_EVENTS." WHERE CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)");
			}
			$result = mysql_query($q, $link);
			if(!$result || (mysql_num_rows($result) < 1)){
				// NO EVENTS
				} else {
				// EVENTS
				while($row = mysql_fetch_assoc($result)) {
				if ($row['status'] != 'approved') {
					$style = "style='color: white; text-shadow: 1px 1px 2px black, 0 0 25px yellow, 0 0 5px orange;'";
					} else {
					$style = "";
					}
					echo "<a href='/showevent.php?e=".$row['event_id']."&s=".$row['series']."' $style>".$row['title']."</a>";
					echo "<br> ".date('g:i a',strtotime($row['dateStart']))."-".date('g:i a',strtotime($row['dateEnd']))."<br> Room:";
					echo $row['room_number'];
					echo "<br><br>";
				}
			}
		}
	}
	
	
	
	function showMonth ($calmonth,$calyear) {
		global $week_titles, $o, $m, $a, $y, $w, $c, $next, $prev,$ly, $lm, $le, $la, $sem;
		/* determine total number of days in a month */
		
		$calday = 0;
		$totaldays = 0;
		while ( checkdate( $calmonth, $totaldays + 1, $calyear ) )
		$totaldays++;
		
		/* build table */
		echo '<table width="100%" class="grid""><tr>'; 
		echo '<th colspan="7" class="cal_top"><a href="index.php?o=',$o,'&w=',$w,'&c=',$c,'&m=',$prev["month"]["m"],'&a=1&y=',$prev["month"]["y"],'&sem=',$sem,'">&lt;</a> ',date('F', mktime(0,0,0,$calmonth,1,$calyear)),'&nbsp;',date('Y', mktime(0,0,0,$calmonth,1,$calyear)),' <a href="index.php?o=',$o,'&w=',$w,'&c=',$c,'&m=',$next["month"]["m"],'&a=1&y=',$next["month"]["y"],'&sem=',$sem,'">&gt;</a></th></tr><tr>';
		for ( $x = 0; $x < 7; $x++ ){
			if ($week_titles[$x] == "Sunday" || $week_titles[$x] == "Saturday"){
				echo '<th class="noshow">', $week_titles[ $x ], '</th>';
				} else {
				echo '<th>', $week_titles[ $x ], '</th>';
			}
		}
		
		/* ensure that a number of blanks are put in so that the first day of the month
		lines up with the proper day of the week */
		$off = date( "w", mktime( 0, 0, 0, $calmonth, $calday, $calyear ) );
		$offset = $off + 1;
		echo '</tr><tr>';
		if ($offset > 6) $offset = 0;
		for ($t=0; $t < $offset; $t++) {
			if ($t == 0) {
				$offyear = date( "Y", mktime( 0, 0, 0, $calmonth, $calday-$off, $calyear ) );
				$offmonth = date( "m", mktime( 0, 0, 0, $calmonth, $calday-$off, $calyear ) );
				$offday = date( "d", mktime( 0, 0, 0, $calmonth, $calday-$off, $calyear ) );
				echo '<td class="day noshow"><div class="week"><a href="index.php?o=',$le,'&w=',$w,'&c=',$c,'&m=',$offmonth,'&a=',$offday,'&y=',$offyear,'&sem=',$sem,'">week</a></div></td>';
				} else {
				if ($offset == 6 || $offset == 0){
					echo '<td class="day noshow">&nbsp;</td>';
					} else {
					echo '<td class="day">&nbsp;</td>';
				}
			}
		}
		/* start entering in the information */
		for ( $d = 1; $d <= $totaldays; $d++ )
		{
			if (($d == date('j')) && ($calmonth == date('m')) && ($calyear == date('Y'))) {
				
				if ($offset == 0 || $offset == 6){
					echo '<td class="day noshow" id="today"><div class="day_of_month"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'&sem=',$sem,'">', $d, '</a></div>';
					
					} else {
					echo '<td class="day" id="today"><div class="day_of_month"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'&sem=',$sem,'">', $d, '</a></div>';
				}
				} else {
				if ($offset == 0 || $offset == 6){
					echo '<td class="day noshow"><div class="day_of_month"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'&sem=',$sem,'">', $d, '</a></div>';
					} else {
					echo '<td class="day"><div class="day_of_month"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'&sem=',$sem,'">', $d, '</a></div>';
				}
				if ($offset == 0) echo '<div class="week"><a href="index.php?o=',$le,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'&sem=',$sem,'">week</a></div>';
				
				
			}
			/* correct date format */
			$coredate = date( "Ymd", mktime( 0, 0, 0, $calmonth, $d, $calyear ) );
			showGrid($coredate);
			echo "</td>";
			$offset++;
			
			/* if we're on the last day of the week, wrap to the other side */
			if ( $offset > 6 )
			{
				$offset = 0;
				echo '</tr>';
				//if ( $day < $totaldays )
				//echo '<tr>';
			}
		}
		
		/* fill in the remaining spaces for the end of the month, just to make it look
		pretty */
		if ( $offset > 0 )
		$offset = 7 - $offset;
		
		for ($t=0; $t < $offset; $t++) {
			if ($t == $offset-1){
				echo '<td class="day noshow">&nbsp;</td>';
				} else {
				echo '<td class="day">&nbsp;</td>';
			}
		}
		/* end the table */
		echo '</tr></table>';
	}
	
	include "top_header.php";
	
	$thismonth = $y."-".$m;
	$nextmonth =  $next["month"]["y"]."-".$next["month"]["m"];
	showMonth($m,$y);
	
	
?>
