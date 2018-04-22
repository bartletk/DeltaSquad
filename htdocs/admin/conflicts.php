<?php
	
	include "../top_header.php";
	$msg = $_REQUEST["msg"];
	if (!$_SESSION['user_id']) $_SESSION['user_id'] = 1;
	
	// establish database connection
	$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
	mysql_select_db(DB_NAME,$link);
	
	$supergroup = true;
	$supercategory = true;
	
	
	
	
	if (!$calendar_title) $calendar_title = "Nursing Calendar";
	
	if ((is_numeric($_REQUEST["m"]))&& ($_REQUEST["m"]!= 0)) {
		$_SESSION["m"] = $_REQUEST["m"];
		$m = $_REQUEST["m"];
		} elseif ($_SESSION["m"]) {
		$m = $_SESSION["m"];
		} else {
		$m = date(m);
	}
	if (strlen($m) == 1) $m = "0".$m;
	
	
	if ((is_numeric($_REQUEST["a"]))&& ($_REQUEST["a"]!= 0)) {
		$_SESSION["a"] = $_REQUEST["a"];
		$a = $_REQUEST["a"];
		} elseif ($_SESSION["a"]) {
		$a = $_SESSION["a"];
		} else {
		$a = date(d);
	}
	if (strlen($a) == 1) $a = "0".$a;
	
	if ((is_numeric($_REQUEST["y"]))&& ($_REQUEST["y"]!= 0)) {
		$_SESSION["y"] = $_REQUEST["y"];
		$y = $_REQUEST["y"];
		} elseif ($_SESSION["y"]) {
		$y = $_SESSION["y"];
		} else {
		$y = date(Y);
	}
	
	
	if ($_POST["godate"]) {
		if (preg_match("/([0-9]{1,2})[\/-]+([0-9]{1,2})[\/-]+([0-9]{4})/i",$_POST["godate"],$dater)) {
			$_SESSION["m"] = $dater[1];
			$_SESSION["a"] = $dater[2];
			$_SESSION["y"] = $dater[3];
			$m = $dater[1];
			$a = $dater[2];
			$y = $dater[3];
		}
		
	}
	
	$day_of_week = date("w", mktime( 0, 0, 0, $m, $a, $y ) );
	$wa = $a-$day_of_week;
	for($wacount=0;$wacount < 7;$wacount++) {
		
		$now["week"]["a"][$wacount] = date( "d", mktime( 0, 0, 0, $m, $wa+$wacount, $y ) );
		$now["week"]["m"][$wacount] = date( "m", mktime( 0, 0, 0, $m, $wa+$wacount, $y ) );
		$now["week"]["y"][$wacount] = date( "Y", mktime( 0, 0, 0, $m, $wa+$wacount, $y ) );
		
	}
	
	$next["week"]["a"] = date( "d", mktime( 0, 0, 0, $m, $wa+7, $y ) );
	$next["week"]["m"] = date( "m", mktime( 0, 0, 0, $m, $wa+7, $y ) );
	$next["week"]["y"] = date( "Y", mktime( 0, 0, 0, $m, $wa+7, $y ) );
	$prev["week"]["a"] = date( "d", mktime( 0, 0, 0, $m, $wa-7, $y ) );
	$prev["week"]["m"] = date( "m", mktime( 0, 0, 0, $m, $wa-7, $y ) );
	$prev["week"]["y"] = date( "Y", mktime( 0, 0, 0, $m, $wa-7, $y ) );
	
	$next["day"]["a"] = date( "d", mktime( 0, 0, 0, $m, $a+1, $y ) );
	$next["day"]["m"] = date( "m", mktime( 0, 0, 0, $m, $a+1, $y ) );
	$next["day"]["y"] = date( "Y", mktime( 0, 0, 0, $m, $a+1, $y ) );
	$next["month"]["m"] = date( "m", mktime( 0, 0, 0, $m+1, 1, $y ) );
	$next["month"]["y"] = date( "Y", mktime( 0, 0, 0, $m+1, 1, $y ) );
	$next["year"]["y"] = date( "Y", mktime( 0, 0, 0, 1, 1, $y+1 ) );
	
	$prev["day"]["a"] = date( "d", mktime( 0, 0, 0, $m, $a-1, $y ) );
	$prev["day"]["m"] = date( "m", mktime( 0, 0, 0, $m, $a-1, $y ) );
	$prev["day"]["y"] = date( "Y", mktime( 0, 0, 0, $m, $a-1, $y ) );
	$prev["month"]["m"] = date( "m", mktime( 0, 0, 0, $m-1, 1, $y ) );
	$prev["month"]["y"] = date( "Y", mktime( 0, 0, 0, $m-1, 1, $y ) );
	$prev["year"]["y"] = date( "Y", mktime( 0, 0, 0, 1, 1, $y-1 ) );
	
	if ((is_numeric($_REQUEST["c"]))&& ($_REQUEST["c"]!= 0)) {
		$_SESSION["c"] = $_REQUEST["c"];
		$c = $_REQUEST["c"];
		} elseif ($_SESSION["c"]) {
		$c = $_SESSION["c"];
		} else {
		$c = 1;
	}
	
	if ((is_numeric($_REQUEST["w"]))&& ($_REQUEST["w"]!= 0)) {
		$_SESSION["w"] = $_REQUEST["w"];
		$w = $_REQUEST["w"];
		} elseif ($_SESSION["w"]) {
		$w = $_SESSION["w"];
		} else {
		$w = 1;
	}
	
	
	if ((is_numeric($_REQUEST["o"]))&& ($_REQUEST["o"]!= 0)) {
		$_SESSION["o"] = $_REQUEST["o"];
		$o = $_REQUEST["o"];
		} elseif ($_SESSION["o"]) {
		$o = $_SESSION["o"];
		} elseif ($default_module) {
		$o = $default_module;
		} else {
		$o = mysql_result(mysql_query("SELECT module_id from modules where active = 1 order by sequence limit 1"),0,0);
	}
	if (!$o) {
		
		$msg .= "<p class=\"warning\">".$lang["no_modules_installed"]."</p>\n";
		} else {
		$q = "SELECT * from modules where module_id = ".$o;
		$query = mysql_query($q);
		if (!$query) $msg = "Database Error : ".$q;
		else {
			$row = mysql_fetch_array($query);
			if (!$page_title) $page_title = $row["name"];
			$script = $row["script"];
			$ly = $row["year"];
			$lm = $row["month"];
			$la = $row["day"];
			$le = $row["week"];
		}
		
	}
	$common_get = "o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."&sem=".$sem."&cwid=".$studentCWID."&rm=".$rm;
	if(!$session->isAdmin()){
		header("Location: ../index.php");
	}
	else{
		$thismonth = $y."-".$m;
		$nextmonth =  $next["month"]["y"]."-".$next["month"]["m"];
		showMonth($m,$y);

	}
	include "../footer.php";
	
	function showGrid($date) {
		GLOBAL $session;
		$dateNew = substr_replace(substr_replace($date, "-", 6, 0), "-", 4, 0);
		$CWID = $session->getCWID();
		$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
		mysql_select_db(DB_NAME,$link);
		
		$q = sprintf("SELECT DISTINCT ".TBL_EVENTS.".* FROM ".TBL_EVENTS." WHERE CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE) AND status != 'approved'");
		
		
		$result = mysql_query($q, $link);
		if(!$result || (mysql_num_rows($result) < 1)){
			// NO EVENTS
			} else {
			// EVENTS
			while($row = mysql_fetch_assoc($result)) {
					$style = "style='color: white; text-shadow: 1px 1px 2px black, 0 0 25px orange; 0 0 5px black;'";
					$class = "";
				$title = $row['title'];
				if (strlen($title) > 28) {
					$stringCut = substr($row['title'], 0, 28);
					$title = substr($stringCut, 0, strrpos($stringCut, ' '))."...";
				}
				echo "<div ".$class.">";
				echo "<a href='./viewconflict.php?e=".$row['event_id']."' $style>".$title."</a>";
				if ($row['series'] != 9100){
					echo "<br> ".date('g:i a',strtotime($row['dateStart']))."-".date('g:i a',strtotime($row['dateEnd']))."<br> Room:";
					echo $row['room_number'];
				}
				echo "</div>";
			}
		}
	}
	
	
	
	function showMonth ($calmonth,$calyear) {
		global $week_titles, $o, $m, $a, $y, $w, $c, $next, $prev,$ly, $lm, $le, $la, $sem, $cwid, $studentCWID, $rm;
		/* determine total number of days in a month */
		
		$calday = 0;
		$totaldays = 0;
		while ( checkdate( $calmonth, $totaldays + 1, $calyear ) )
		$totaldays++;
		
		/* build table */
		echo '<table width="100%" class="grid""><tr>'; 
		echo '<th colspan="7" class="cal_top"><a href="/admin/conflicts.php?o=',$o,'&w=',$w,'&c=',$c,'&m=',$prev["month"]["m"],'&a=1&y=',$prev["month"]["y"],'">&lt;</a> ',date('F', mktime(0,0,0,$calmonth,1,$calyear)),'&nbsp;',date('Y', mktime(0,0,0,$calmonth,1,$calyear)),' <a href="/admin/conflicts.php?o=',$o,'&w=',$w,'&c=',$c,'&m=',$next["month"]["m"],'&a=1&y=',$next["month"]["y"],'">&gt;</a></th></tr><tr>';
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
				echo '<td class="day noshow"><div class="week"><a href="/admin/conflicts.php?o=',$le,'&w=',$w,'&c=',$c,'&m=',$offmonth,'&a=',$offday,'&y=',$offyear,'">week</a></div></td>';
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
					echo '<td class="day noshow" id="today"><div class="day_of_month"><a href="/admin/conflicts.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
					
					} else {
					echo '<td class="day" id="today"><div class="day_of_month"><a href="/admin/conflicts.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
				}
				} else {
				if ($offset == 0 || $offset == 6){
					echo '<td class="day noshow"><div class="day_of_month"><a href="/admin/conflicts.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
					} else {
					echo '<td class="day"><div class="day_of_month"><a href="/admin/conflicts.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
				}
				if ($offset == 0) echo '<div class="week"><a href="/admin/conflicts.php?o=',$le,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">week</a></div>';
				
				
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
?>