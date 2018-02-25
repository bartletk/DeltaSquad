<?php
/*
Supercali Event Calendar

Copyright 2006 Dana C. Hutchins

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
$page = "index.php";

/* Grab Dates Function */
// Queries database and dumps events into an array.
// $start and $end are date ranges in 20060118 format
// $category_array is arrays of categories to be included


function grabDates($start,$end,$category_array) {
	$cats = implode(",",$category_array);
	global $supergroup;
	global $title, $niceday, $start_time, $end_time, $venue, $city, $state, $cat,$ed, $usr, $color, $background,$lang, $w, $ap, $status;
	/* get applicable events */
	$superedit = false;
	if (!$supergroup) {
		$q = "select moderate from users_to_groups where group_id = ".$w." and user_id = ".$_SESSION["user_id"];
		$query = mysql_query($q);
		if (mysql_num_rows($query) > 0) {
			$mod = mysql_result($query,0,0);
			if ($mod > 2) {
				$superedit = true;
			}
		}
	} else {
		$superedit = true;
	}
	if (($mod > 0) || ($superedit)) {
		$q = "select DATE_FORMAT(dates.date, '%Y%m%d'), DATE_FORMAT(dates.date, '%H%i'), events.event_id, events.title, DATE_FORMAT(dates.date, '%W, %M %e, %Y'), DATE_FORMAT(dates.date, '%l:%i %p'),  DATE_FORMAT(dates.end_date, '%l:%i %p'), links.company, links.city, links.state, events.category_id, events.user_id, dates.date, categories.color, categories.background, events.status_id from events, dates, links, categories, groups where dates.date >= '$start' and dates.date < '$end' and dates.event_id = events.event_id and events.venue_id = links.link_id and events.category_id in (".$cats.") and events.category_id = categories.category_id and events.group_id = groups.group_id and events.group_id = ".$w." order by dates.date";
		$query = mysql_query($q);
		//echo $q."<br>";
		while ($row = mysql_fetch_row($query)) {
			$edit = false;
			if ($row[11] == $_SESSION["user_id"]) {
				$edit = true;
			} elseif ($superedit) {
				$edit = true;
			}
			if ($edit==true) $ed[$row[2]]=true;
			if ($superedit==true) $ap[$row[2]]=true;
			$title[$row[2]]=strip_tags($row[3]);
			$niceday[$row[0]][$row[12]][$row[2]]=$row[4];
			if (($row[5] == "12:00 AM") && ($row[6] == "11:59 PM")) {
				$start_time[$row[0]][$row[12]][$row[2]]=$lang["all_day"];
			} elseif (($row[5] == "12:00 AM") && ($row[6] == "12:00 AM")) {
				$start_time[$row[0]][$row[12]][$row[2]]=$lang["tba"];
			} else {	
				$start_time[$row[0]][$row[12]][$row[2]]=$row[5];
				if ($row[6]) $end_time[$row[0]][$row[12]][$row[2]]=$row[6];
			}
			if ($row[7]) $venue[$row[2]]=$row[7];
			if ($row[7] && $row[8]) $city[$row[2]]=$row[8];
			if ($row[7] && $row[8] && $row[9]) $state[$row[2]]=$row[9];
			$cat[$row[2]]=$row[10];
			$usr[$row[2]]=$row[11];
			$color[$row[2]]=$row[13];
			$background[$row[2]]=$row[14];
			$status[$row[2]]=$row[15];
		}
	}
}

function grab($start,$end,$category) {
	global $include_child_categories, $include_parent_categories, $category_array,$supercategory,$supergroup,$category_permissions,$w;
	$canview = false;
	$groupview = false;
	if (!$supergroup) {
		$q = "SELECT * from users_to_groups where group_id = ".$w." and  user_id = ".$_SESSION["user_id"];
		$query = mysql_query($q);
		if (mysql_num_rows($query) > 0) $groupview = true;
	} else {
		$groupview = true;
	}
	if ($groupview) {
		if (!$supercategory) {
			//build permission array
			$q = "SELECT category_id from users_to_categories where user_id = ".$_SESSION["user_id"];
			//echo $q."<br>";
			$query = mysql_query($q);
			if (mysql_num_rows($query) > 0) {
				while ($row = mysql_fetch_row($query)) {
					$category_permissions[] = $row[0];
					
				}
			}
			if (in_array($category,$category_permissions)) $canview = true;
		} else {
			$canview = true;
		}
		if ($canview) {
			$category_array[] = $category;
			if ($include_child_categories) grab_child($start,$end,$category,true);
			if ($include_parent_categories) grab_parent($start,$end,$category,true);
			grabDates($start,$end,$category_array);
			
		}
	
	}
}

function grab_child($start,$end,$category,$starter=false) {
	global $category_array,$supercategory,$category_permissions;
	$canview = false;
	if (!$supercategory) {
		if ($category_permissions) {
			if (in_array($category,$category_permissions)) $canview = true;
		}
	} else {
		$canview = true;
	}
	if ($canview) {
		if (!$starter) $category_array[] = $category;
		$q = "select category_id from categories where sub_of = ".$category;
		//echo $q."<br>";
		$query = mysql_query($q);
		if (!$query) $msg = "Database Error : ".$q;
		else {
			while ($row = mysql_fetch_row($query)) {
				grab_child($start,$end,$row[0],false);
			}
		}
	}
}

function grab_parent($start,$end,$category,$starter=false) {
	global $category_array, $supercategory,$category_permissions;
	$canview = false;
	if (!$supercategory) {
		if ($category_permissions) {
			if (in_array($category,$category_permissions)) $canview = true;
		}
	} else {
		$canview = true;
	}
	if ($canview) {
		if (!$starter) $category_array[] = $category;
		
		$q = "select sub_of from categories where category_id = ".$category;
		//echo $q."<br>";
		$query = mysql_query($q);
		if (!$query) $msg = "Database Error : ".$q;
		else {
			while ($row = mysql_fetch_row($query)) {
				grab_parent($start,$end,$row[0],false);
			}
		}
	}
}



include "includes/start.php";
$canview = false;
//if no access, then kick them out!


if (($supergroup) && ($supercategory)) {
	$canview = true;
	
} else {
	
	if (!$supercategory) {
		$canview = false;
		$q = "select * from users_to_categories where category_id = ".$c." and user_id = ".$_SESSION["user_id"];
		//echo $q;
		$qu = mysql_query($q);
		if (mysql_num_rows($qu) > 0) {
			$canview = true;
			
		} else {
			$msg .= "<p>".$lang["no_permission_to_view_category"]."</p>";
			$canview = false;
			
		}
	}
	if ((!$supergroup) && $canview) {
		$q = "select * from users_to_groups where group_id = ".$w." and user_id = ".$_SESSION["user_id"];
		//echo $q;
		$qu = mysql_query($q);
		if (mysql_num_rows($qu) > 0) {
			$canview = true;
			
		} else {
			$msg .= "<p>".$lang["no_permission_to_view_group"]."</p>";
			$canview = false;
			
		}
	}
}
if (($canview == true)&& $script) {
	include "modules/".$script;
} else {
	include "header.php";
}



?>