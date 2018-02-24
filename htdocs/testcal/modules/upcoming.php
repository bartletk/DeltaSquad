<?php
/*
<?xml version="1.0" encoding="utf-8"?>
<module>
        <name>Upcoming View</name>
        <author>Dana C. Hutchins</author>
        <url>http://supercali.inforest.com/</url>
        <version>1.0.6</version>
        <link_name>Upcoming</link_name>
        <description>Shows Upcoming Events</description>
        <image></image>
		<install_script></install_script>     
</module>
Supercali Event Calendar

Copyright 2006-8 Dana C. Hutchins

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

For further information visit:
http://supercali.inforest.com/
*/
// module configuration variables
if (!$hideheader) $hideheader = false; // set to true if calling from another page
if (!$plusmonths) $plusmonths = 1;  // how far out in months to view, if > 1, then you probably want to set $plusdays=0 or vice-versa
if (!$plusdays) $plusdays = 0; // how far out in days to view

/* NOTE: To call this module within your code set $_REQUEST[o] to the ID of this module id shown in modules page of SuperCali.  For example:
$_REQUEST[o] = 5;
$hideheader = true;
chdir ("demo_calendar");
include "index.php";
chdir("../");
unset($_SESSION[o]);
*/


function showGrid($date) {
	global $title, $niceday, $start_time, $end_time, $venue, $city, $state, $cat, $color, $background, $ed, $usr, $o, $c, $m, $a, $y, $w, $lang, $ap, $status;
	if ($start_time[$date]) {
		ksort($start_time[$date]);
		
		while (list($t) = each($start_time[$date])) {
			$i = 0;
			while (list($id,$value) = each($start_time[$date][$t])) {
				if ($i == 0) {
					echo "<h3>".$niceday[$date][$t][$id]."</h3>\n";
					echo "<ul>\n";
				}
				echo "<li>";
				echo "<div class=\"item\"";
				if ($color[$id]) echo " style=\"color: ".$color[$id]."; background: ".$background[$id].";\"";
				echo ">";
				echo "<div class=\"time\">".$value;
				if ($end_time[$date][$t][$id]) echo " - ".$end_time[$date][$t][$id];
				echo "</div>\n";
				echo "<div class=\"title\"><a href=\"".$calendar_url."show_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\"";
				if ($color[$id]) echo " style=\"color: ".$color[$id]."; background: ".$background[$id].";\"";
				echo ">".$title[$id]."</a></div>\n";
				if ($venue[$id]) {
					echo "<div class=\"venue\">".$venue[$id]."</div>\n";
					if ($city[$id]) {
						echo "<div class=\"location\">".$city[$id];
						if ($state[$id]) echo ", ".$state[$id];
						echo "</div>\n";
					}
				}
				echo "</div>";
				if ($ed[$id]==true) {
					echo "<div class=\"edit\">";
					if (($ap[$id]==true) && (($status[$id] == 2) || ($status[$id] == 3))) echo "[<a href=\"".$calendar_url."admin_actions.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."&mode=".approve."\">".$lang["approve"]."</a>]&nbsp;&nbsp;";
					echo "[<a href=\"edit_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\">".$lang["edit"]."</a>]&nbsp;&nbsp;[<a href=\"".$calendar_url."delete_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\">".$lang["delete"]."</a>]</div>\n";
				}
				echo "</li>\n";
				$i++;
			}
			echo "</ul>\n";
		}
		
	}
}

function showUpcoming () {
	global $start_time;
	if ($start_time) {
		ksort($start_time);
		while (list($k) = each($start_time)) {
			showGrid($k);
		}
	}
}

echo "hide:".$hideheader;
if (!$plusmonths) $plusmonths = 1;
if (!$plusdays) $plusdays = 0;
if (!$hideheader) include "includes/header.php";

$listend = date("Y-m-d", mktime(0,0,0,$m+$plusmonths,$a+$plusdays,$y));
grab($y."-".$m."-".$a,$listend,$c);
showUpcoming($m,$y);

if (!$hideheader) include "includes/footer.php";
?>
