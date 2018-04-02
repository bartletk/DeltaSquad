<!DOCTYPE html>

<?php
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
	$superedit = true;
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
		$q = "select DATE_FORMAT(date_start, '%Y%m%d'),DATE_FORMAT(date_start, '%H%i'), event_id, title, DATE_FORMAT(date_start, '%W, %M %e, %Y'), DATE_FORMAT(date_start, '%l:%i %p'), DATE_FORMAT(date_end, '%l:%i %p'), series, user_id, date_start, status from events order by date_start";
			$query = mysql_query($q);
		while ($row = mysql_fetch_row($query)) {
			$edit = true;
			if ($row[8] == $_SESSION["user_id"]) {
				$edit = true;
			} elseif ($superedit) {
				$edit = true;
			}
			if ($edit==true) $ed[$row[2]]=true;
			if ($superedit==true) $ap[$row[2]]=true;
			$title[$row[2]]=strip_tags($row[3]);
			$niceday[$row[0]][$row[9]][$row[2]]=$row[4];
			if (($row[5] == "12:00 AM") && ($row[6] == "11:59 PM")) {
				$start_time[$row[0]][$row[9]][$row[2]]=$lang["all_day"];
			} elseif (($row[5] == "12:00 AM") && ($row[6] == "12:00 AM")) {
				$start_time[$row[0]][$row[9]][$row[2]]="TBA";
			} else {	
				$start_time[$row[0]][$row[9]][$row[2]]=$row[5];
				if ($row[6]) $end_time[$row[0]][$row[9]][$row[2]]=$row[6];
			}
			$cat[$row[2]]=$row[7];
			$usr[$row[2]]=$row[8];
			$status[$row[2]]=$row[10];
		}
	}

}

function grab($start,$end,$category) {
	global $include_child_categories, $include_parent_categories, $category_array,$supercategory,$supergroup,$category_permissions,$w;
	$canview = true;
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



include "include/start.php";
$canview = true;
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
			$msg .= "<p>"."Sorry, you do not have permission to view events in this category"."</p>";
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
			$msg .= "<p>"."Sorry, you do not have permission to view events in this group calendar"."</p>";
			$canview = false;
			
		}
	}
}
if ($script) {
	include "modules/".$script;
} else {
			include ('top_header.php');	


	
}

echo "Calendar Views"; ?>:&nbsp;&nbsp;&nbsp;
<?php

$q = "SELECT module_id, link_name from modules where active = 1 order by sequence";
$query = mysql_query($q);
if (!$query) $msg .= "Database Error : ".$q;
else {
	$i = false;
	while($row = mysql_fetch_row($query)) {
		if ($i == true) echo " | ";
		echo "<a href=\"index.php?o=".$row[0]."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\"";
			if ($o == $row[0]) echo " class=\"selected\"";
		echo ">".$row[1]."</a>";
		$i = true;
	}
}
include('footer.php');
?>

</body>
</html>
