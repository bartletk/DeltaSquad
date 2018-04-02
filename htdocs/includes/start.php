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

include "include/constants.php";
include $language;
$msg = $_REQUEST["msg"];
if (!$_SESSION['user_id']) $_SESSION['user_id'] = 1;

// establish database connection
$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
mysql_select_db(DB_NAME,$link);

//	this area to set superpermission levels
$query = mysql_query("SELECT view, post, add_categories, add_groups, add_users from users where user_id = ".$_SESSION["user_id"]." limit 1");
$row = mysql_fetch_row($query);
$supergroup = false;
$supercategory = false;

if ($row[4]) $supergroup = true;
if ($row[4]) $supercategory = true;
if ($row[4]) $superpost = true;
if ($row[4]) $superview = true;
if ($row[3]) $supergroup = true;
if ($row[3]) $superpost = true;
if ($row[3]) $superview = true;
if ($row[2]) $supercategory = true;
if ($row[2]) $superpost = true;
if ($row[2]) $superview = true;
if ($row[1]) $superpost = true;
if ($row[1]) $superview = true;
if ($row[0]) $superview = true;



if (!$calendar_title) $calendar_title = "SuperCali Event Calendar";

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
	
	$msg .= "<p class=\"warning\">"."No modules installed"."</p>\n";
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
$common_get = "o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w;
?>
