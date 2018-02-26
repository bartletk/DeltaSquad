<?php
//include("../include/database.php")
/*
function showGrid($date) {
	global $title, $niceday, $start_time, $end_time, $cat, $ed, $usr, $o, $c, $m, $a, $y, $w, $lang, $ap, $status;
	if ($start_time[$date]) {
		ksort($start_time[$date]);
		echo "<ul>\n";
		while (list($t) = each($start_time[$date])) {
			while (list($id,$value) = each($start_time[$date][$t])) {
				echo "<li>";
				echo "<div class=\"item\"";
				echo ">";
				echo "<div class=\"time\">".$value;
				if ($end_time[$date][$t][$id]) echo " - ".$end_time[$date][$t][$id];
				echo "</div>\n";
				echo "<div class=\"title\"><a href=\"show_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('show_event.php?id=".$id."&size=small','pop','600','400'); window.newWindow.focus(); return false\"";
				echo ">".$title[$id]."</a></div>\n";

				echo "</div>";
				if ($ed[$id]==true) {
					echo "<div class=\"edit\">";
					if (($ap[$id]==true) && (($status[$id] == 2) || ($status[$id] == 3))) echo "[<a href=\"admin_actions.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."&mode=".approve."\">Approve</a>]&nbsp;&nbsp;";
					echo "[<a href=\"edit_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('edit_event.php?id=".$id."&size=small','pop','650','600'); window.newWindow.focus(); return false\">Edit</a>]&nbsp;&nbsp;[<a href=\"delete_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\">Delete</a>]</div>\n";
				}
				echo "</li>\n";
			}
		}
		echo "</ul>\n";
	}
	
}
*/

function showGrid($date) {
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
	echo $row[room];
	echo "<br><br>";
}
		}
   		
		
}



function showMonth ($calmonth,$calyear) {
	global $week_titles, $o, $m, $a, $y, $w, $c, $next, $prev,$ly, $lm, $le, $la;
	/* determine total number of days in a month */
	
	$calday = 0;
	$totaldays = 0;
	while ( checkdate( $calmonth, $totaldays + 1, $calyear ) )
	        $totaldays++;
	
	/* build table */
	echo '<table width="100%" class="grid""><tr>'; 
	echo '<th colspan="7" class="cal_top"><a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$prev["month"]["m"],'&a=1&y=',$prev["month"]["y"],'">&lt;</a> ',date('F', mktime(0,0,0,$calmonth,1,$calyear)),'&nbsp;',date('Y', mktime(0,0,0,$calmonth,1,$calyear)),' <a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$next["month"]["m"],'&a=1&y=',$next["month"]["y"],'">&gt;</a></th></tr><tr>';
	for ( $x = 0; $x < 7; $x++ )
	        echo '<th>', $week_titles[ $x ], '</th>';
	
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
			echo '<td class="day"><div class="week"><a href="index.php?o=',$le,'&w=',$w,'&c=',$c,'&m=',$offmonth,'&a=',$offday,'&y=',$offyear,'">week</a></div></td>';
		} else {
			echo '<td class="day">&nbsp;</td>';
		}
	}
	/* start entering in the information */
	for ( $d = 1; $d <= $totaldays; $d++ )
	{
			if (($d == date('j')) && ($calmonth == date('m')) && ($calyear == date('Y'))) {
				echo '<td class="day" id="today"><div class="day_of_month"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
			} else {
				echo '<td class="day"><div class="day_of_month"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
				if ($offset == 0) echo '<div class="week"><a href="index.php?o=',$le,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">week</a></div>';
				
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
}

	include "header.php";

$thismonth = $y."-".$m;
$nextmonth =  $next["month"]["y"]."-".$next["month"]["m"];
grab($thismonth."-01",$nextmonth."-01",$c);
showMonth($m,$y);


?>
