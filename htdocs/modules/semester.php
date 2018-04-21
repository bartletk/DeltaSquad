<?php
	
	function showGrid($date) {
		GLOBAL $session;
		$dateNew = substr_replace(substr_replace($date, "-", 6, 0), "-", 4, 0);
		$CWID = $session->getCWID();
		$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
		mysql_select_db(DB_NAME,$link);
		// If student
		if(!$session->isInstructor() & !$session->isAdmin()){
			if ($session->isStudent()){
				$q = sprintf("SELECT DISTINCT ".TBL_EVENTS.".* from ".TBL_EVENTS." join ".TBL_SCHED." on ".TBL_SCHED.".crn = ".TBL_EVENTS.".crn where (".TBL_SCHED.".cwid = $CWID OR series=9100) AND CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE) AND status='approved'");
				} else {
				header ("Location: class_select.php");
			}
			// if teacher
			} elseif (!$session->isAdmin() & $session->isInstructor()) {
			$sem = $_GET['sem'];
			if (isset($sem) && ($sem != 0) && ($sem != NULL)){
				$q = sprintf("select  ".TBL_EVENTS.".* from ".TBL_EVENTS." join ".TBL_CRN." ON ".TBL_EVENTS.".crn = ".TBL_CRN.".crn join ".TBL_COURSE." on ".TBL_COURSE.".course_number = ".TBL_CRN.".course_number where CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE) AND (semester = $sem OR semester=0 OR series=9100)");
				} else {
				$q = sprintf("select DISTINCT ".TBL_EVENTS.".* from ".TBL_EVENTS." join ".TBL_CRN." ON ".TBL_EVENTS.".crn = ".TBL_CRN.".crn join ".TBL_COURSE." on ".TBL_COURSE.".course_number = ".TBL_CRN.".course_number where (".TBL_CRN.".instructor = $CWID OR ".TBL_COURSE.".Lead_Instructor = $CWID OR series=9100) AND CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)");
			}
			//if admin
			} else {
			$sem = $_GET['sem'];
			if (isset($sem) && ($sem != 0) && ($sem != NULL)){
				// change to all of a semester's classes
				$q = sprintf("select  ".TBL_EVENTS.".* from ".TBL_EVENTS." join ".TBL_CRN." ON ".TBL_EVENTS.".crn = ".TBL_CRN.".crn join ".TBL_COURSE." on ".TBL_COURSE.".course_number = ".TBL_CRN.".course_number where CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE) AND (semester = $sem OR semester=0 OR series=9100)");	
				//$myfile = fopen("error.txt", "a") or die(print_r($q));
				} else {
				$q = sprintf("SELECT DISTINCT ".TBL_EVENTS.".* FROM ".TBL_EVENTS." WHERE CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)");
			}
		}
		$result = mysql_query($q, $link);
		if(!$result || (mysql_num_rows($result) < 1)){
			// NO EVENTS
			} else {
			// EVENTS
			while($row = mysql_fetch_assoc($result)) {
				if ($row['status'] != 'notapproved') {
					$style = "style='color: white; text-shadow: 1px 1px 2px black, 0 0 25px yellow, 0 0 5px orange'";
					$class = "class='approved'";
				} else if ($row['series']==9100) {
					$style = "style='color: white; text-shadow: 1px 1px 2px black, 0 0 25px gray, 0 0 5px black;'";
					$class = "class='rss'";
				} else {	
					$style = "";
					$class = "class='approved'";
				}
				echo "<div class=".$class.">";
				echo "<a href='/showevent.php?e=".$row['event_id']."&s=".$row['series']."' $style>".$row['title']."</a>";
				echo "<br> ".date('g:i a',strtotime($row['dateStart']))."-".date('g:i a',strtotime($row['dateEnd']))."<br> Room:";
				echo $row['room_number'];
				echo "</div>";
			}
		}
	}
	
	function showYear ($calyear) {
		$monthStart = 0;
		$monthEnd = 0;
		$q = sprintf("SELECT * FROM ".TBL_DEADLINES." WHERE type = 'semester'");
		$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
		$result = mysql_query($q, $link);
		if(!$result || (mysql_num_rows($result) < 1)){
			// NO SEMESTER SET
			echo "No semester set";
			} else {
			// EVENTS
			while($row = mysql_fetch_assoc($result)) {
				$monthStart  = strtotime(date('Y-m',strtotime(mysql_result($result,$i,"open"))));
				$monthEnd  = strtotime(date('Y-m',strtotime(mysql_result($result,$i,"close"))));
			}
			
			global $week_titles_ss, $o, $m, $a, $y, $w, $c, $next, $prev, $lm, $la, $le;
			echo '<table width="100%" class="grid""><tr>'; 
			echo '<th colspan="1" class="cal_top">Current Semester</th></tr>';
		$calmonth = 0;
		for($p=0;$p<12;$p++){
		echo '<tr>';
		for($q=0;$q<1;$q++){
		$calmonth++;
		echo '<td class="holder">';
		
		
		/* determine total number of days in a month */
		
		$calday = 0;
		$totaldays = 0;
		while ( checkdate( $calmonth, $totaldays + 1, $calyear ) )
		$totaldays++;
		$curMonth = strtotime(date($calyear.'-'.$calmonth));
		//$myfile = fopen("error.txt", "a") or die(print_r($curMonth." ".$monthEnd));
		if (($monthEnd >= $curMonth) && ($monthStart <= $curMonth)){
		/* build table */
		echo '<table width="100%" class="grid"><tr>'; 
		echo '<th colspan="7" class="cal_top_s"><a href="index.php?o=',$lm,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=1&y=',$calyear,'&sem=',$sem,'">',date('F', mktime(0,0,0,$calmonth,1,$calyear)),'</a></th></tr><tr>';
		for ( $x = 0; $x < 7; $x++ )
		echo '<th>', $week_titles_ss[ $x ], '</th>';
		
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
		echo '<td class="day"><div class="week"><a href="index.php?o=',$le,'&w=',$w,'&c=',$c,'&m=',$offmonth,'&a=',$offday,'&y=',$offyear,'&sem=',$sem,'">week</a></div></td>';
		} else {
		echo '<td class="day">&nbsp;</td>';
		}
		}
		/* start entering in the information */
		for ( $d = 1; $d <= $totaldays; $d++ )
		{
		if (($d == date(j)) && ($calmonth == date(m)) && ($calyear == date(Y))) {
		echo '<td class="day" id="today"><div class="day_of_month_s"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
		} else {
		echo '<TD class="day"><div class="day_of_month_s"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'&sem=',$sem,'">', $d, '</a></div>';
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
		if ( $day < $totaldays )
		echo '<tr>';
		}
		}
		
		/* fill in the remaining spaces for the end of the month, just to make it look
		pretty */
		if ( $offset > 0 )
		$offset = 7 - $offset;
		
		for ($t=0; $t < $offset; $t++) {
		echo "<td>&nbsp;</td>";
		}
		/* end the table */
		echo '</tr></table>';
		
		
		echo '</td>';
		}
		}
		}
		echo "</tr>";
		}			
		
		echo "</table>";
		
		}
		include "top_header.php";
		showYear($y);
		
		
		?>
				