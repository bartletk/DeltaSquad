<?php
/*
<?xml version="1.0" encoding="utf-8"?>
<module>
        <name>Year View</name>
        <author>Chris McDonald</author>
        <url>http://supercali.inforest.com/</url>
        <version>1.0.0</version>
        <link_name>Year</link_name>
        <description>Shows an entire year on one screen.  Each event is shown as a 10x10 pixel box.  Mouse over to see title.</description>
        <image></image>
		<install_script></install_script>     
</module>

Year View - Created by Chris McDonald
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

function showYear ($calyear) {
	global $week_titles_ss, $o, $m, $a, $y, $w, $c, $next, $prev, $lm, $la, $le;
	echo '<table width="100%" class="grid""><tr>'; 
	echo '<th colspan="4" class="cal_top"><a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$m,'&a=1&y=',$prev['year']['y'],'">&lt;</a>&nbsp;',$calyear,'&nbsp;<a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$m,'&a=1&y=',$next['year']['y'],'">&gt;</a></th></tr>';
	$calmonth = 0;
		for($p=0;$p<3;$p++){
			echo '<tr>';
			for($q=0;$q<4;$q++){
				$calmonth++;
				echo '<td width="25%" class="holder">';
			
		
			/* determine total number of days in a month */
			
			$calday = 0;
			$totaldays = 0;
			while ( checkdate( $calmonth, $totaldays + 1, $calyear ) )
					$totaldays++;
			
			/* build table */
			echo '<table width="100%" class="grid"><tr>'; 
			echo '<th colspan="7" class="cal_top_s"><a href="index.php?o=',$lm,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=1&y=',$calyear,'">',date('F', mktime(0,0,0,$calmonth,1,$calyear)),'</a></th></tr><tr>';
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
			echo "</tr>";
		}			
			
		echo "</table>";
	
}
include "includes/header.php";
grab($y."-01-01",$y."-12-31",$c);
showYear($y);

include "includes/footer.php";
?>
