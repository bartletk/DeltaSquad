<?php

include "include/start.php";
$page_title = "Event";
$id = mysql_real_escape_string($_REQUEST["id"]);

if ((!$id) or (!ctype_digit($id))) {
	echo "<p class=\"warning\">"."No Event Selected to Edit"."</p>\n";
} else {
	$q = "SELECT * from events where event_id =".$id;
	$query = mysql_query($q);
	if (mysql_num_rows($query) < 1) {
		echo "<p class=\"warning\">"."Event Not Found"."</p>\n";
	} else {
		$row = mysql_fetch_array($query);
		if (!$query) echo "<p class=\"warning\">Database Error : ".$q."</p>\n";
		
		$q = "SELECT DATE_FORMAT(date_start, '%W, %M %e, %Y'), DATE_FORMAT(date_start,' - %l:%i %p'),  DATE_FORMAT(date_end, ' - %l:%i %p') from events where event_id =".$id." order by date";
		$squery = mysql_query($q);
		if (!$squery) echo "<p class=\"warning\">Database Error : ".$q."</p>\n";
		else {
			while ($srow = mysql_fetch_row($squery)) {
				if (($srow[1] == " - 12:00 AM") && ($srow[2] == " - 11:59 PM")) $nicedate[] = $srow[0]." - ".$lang["all_day"];
				elseif (($srow[1] == " - 12:00 AM") && ($srow[2] == " - 12:00 AM")) $nicedate[] = $srow[0]." - "."TBA";
				elseif ($srow[2]) $nicedate[] = $srow[0].$srow[1].$srow[2];
				else $nicedate[] = $srow[0].$srow[1];
				
			}
		}
		$page_title = $row["title"];
		$category_id = $row["series"];
		$description = $row["notes"];
	}
}
if ($_REQUEST["size"] == "small") $javascript = "<base target=\"_blank\">\n";
	include "header.php";

if ($nicedate[1]) {
	echo "Dates".":<ul>\n";
	while (list($k,$v) = each($nicedate)) {
		echo "<strong><li>".$v."</li></strong>\n";
	}
	echo "</ul>\n";
} elseif ($nicedate[0]) {
	echo "Date".": <strong>".$nicedate[0]."</strong><br />";
}

echo "<p>".$description."</p>\n";

?>