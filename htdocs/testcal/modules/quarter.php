<?php
/*
<?xml version="1.0" encoding="utf-8"?>
<module>
        <name>Quarter View</name>
        <author>Chris McDonald</author>
        <url>http://supercali.inforest.com/</url>
        <version>1.0.0</version>
        <link_name>Quarter</link_name>
        <description>Shows a quarter year on one screen.  Each event is shown as a 10x10 pixel box.  Mouse over to see title.</description>
        <image></image>
		<install_script></install_script>     
</module>
Quarter View - Created by Chris McDonald
*/

function showGrid($date) {
	global $title, $niceday, $start_time, $end_time, $venue, $city, $state, $cat, $color, $background, $ed, $usr, $o, $c, $m, $a, $y, $w, $lang;
	if ($start_time[$date]) {
		ksort($start_time[$date]);
		while (list($t) = each($start_time[$date])) {
			while (list($id,$value) = each($start_time[$date][$t])) {
				echo "<div class=\"button\" style=\"";
				if ($color[$id]) echo " color: ".$color[$id]."; background: ".$background[$id].";";
				echo "\">";
				echo "<a href=\"show_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('show_event.php?id=".$id."&size=small','pop','600','400'); window.newWindow.focus(); return false\">";
				echo "<div style=\"background: ".$color[$id].";\" title=\"";
				echo $value;
				if ($end_time[$date][$t][$id]) echo " - ".$end_time[$date][$t][$id];
				echo " - ";
				echo $title[$id];
				if ($venue[$id]) {
					echo " - ".$venue[$id];
					if ($city[$id]) {
						echo " - ".$city[$id];
						if ($state[$id]) echo ", ".$state[$id];
						
					}
				}
				echo "\">&nbsp;</div></a></div>";
			}
		}
	}
}

function showQuarter ($quarter,$calyear) {
	global $week_titles_s, $o, $m, $a, $y, $w, $c, $next, $prev,$lm, $le, $la;
	global $prevmonth, $prevyear, $nextmonth, $nextyear;
	echo '<table width="100%" class="grid""><tr>'; 
	echo '<th colspan="3" class="cal_top"><a href="',$PHP_SELF,'?o=',$o,'&c=',$c,'&m=',$prevmonth,'&a=1&y=',$prevyear,'">&lt;</a>&nbsp;',$calyear,' - Quarter ',$quarter,'&nbsp;<a href="',$PHP_SELF,'?o=',$o,'&c=',$c,'&m=',$nextmonth,'&a=1&y=',$nextyear,'">&gt;</a></th></tr>';
	echo '<tr>';
	$calmonth = $quarter * 3 - 3;
	for($q=0;$q<3;$q++){
		$calmonth++;
		echo '<td width="33%" class="holder">';
	

	/* determine total number of days in a month */
	
	$calday = 0;
	$totaldays = 0;
	while ( checkdate( $calmonth, $totaldays + 1, $calyear ) )
	        $totaldays++;
	
	/* build table */
	echo '<table width="100%" class="grid""><tr>'; 
	echo '<th colspan="7" class="cal_top_s"><a href="index.php?o=',$lm,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=1&y=',$calyear,'">',date('F', mktime(0,0,0,$calmonth,1,$calyear)),'</a></th></tr><tr>';
	for ( $x = 0; $x < 7; $x++ )
	        echo '<th>', $week_titles_s[ $x ], '</th>';
	
	/* ensure that a number of blanks are put in so that the first day of the month
	   lines up with the proper day of the week */
	$offset = date( "w", mktime( 0, 0, 0, $calmonth, $calday, $calyear ) ) + 1;
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
			if (($d == date(j)) && ($calmonth == date(m)) && ($calyear == date(Y))) {
				echo '<td class="day" id="today"><div class="day_of_month_s"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
			} else {
				echo '<TD class="day"><div class="day_of_month_s"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
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
	
	
		echo '</td>';
	}
	echo '</tr></table>';
	
}
// get current quarter
$thisquarter = intval(((intval($m)-1) / 3) + 1);
$thisyear = $y;
//create date range
$startmonth = ($thisquarter - 1) * 3 + 1;
$endmonth = $thisquarter * 3 + 1;
$startmonth = "$y-$startmonth";
$endmonth = "$y-$endmonth";

if($thisquarter == 4){
	$nextquarter = 1;
	$nextmonth = 1;
	$nextyear = $next["year"]["y"];
}else{
	$nextquarter = $thisquarter + 1;
	$nextyear = $y;
	$nextmonth = $thisquarter * 3 + 1;
}
if($thisquarter == 1){
	$prevquarter = 4;
	$prevyear = $prev["year"]["y"];
	$prevmonth = 10;
}else{
	$prevquarter = $thisquarter - 1;
	$prevyear = $y;
	$prevmonth = $prevquarter * 3 - 2;
}
include "includes/header.php";
grab($startmonth."-01",$endmonth."-01",$c);
showQuarter($thisquarter,$thisyear);

include "includes/footer.php";
?>
