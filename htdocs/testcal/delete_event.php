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
include "includes/start.php";
$page_title = $lang["title_delete_event"];
$id = $_REQUEST["id"];
$edit=false;


if (!$_SESSION["user_id"]) {
	mysql_close($link);
	header("Location: ".$path."login.php?return_to=".$PHP_SELF);
} elseif (!$_REQUEST["id"]) {
	mysql_close($link);
	header("Location: ".$path."index.php?msg=".$lang["no_event_selected"]);
} else {
	$q = "SELECT * from ".$table_prefix."events where event_id =".$_REQUEST["id"];
	$query = mysql_query($q);
	$row = mysql_fetch_array($query);
		
	if (!$query) $msg .= "Database Error : ".$q;

	$squery = mysql_query("SELECT add_categories from ".$table_prefix."users where user_id = ".$_SESSION["user_id"]." limit 1");
	$srow = mysql_fetch_row($squery);
	if ($srow[0] == 1) {
		$edit = true;
	} else {
		if ($row["user_id"] != $_SESSION["user_id"]) {
			$q = "select moderate from ".$table_prefix."users_to_categories where category_id = ".$row[0]." and user_id = ".$_SESSION["user_id"];
			$mod = mysql_result(mysql_query($q),0,0);
			if ($mod < 2) {
				mysql_close($link);
				header("Location: ".$path."index.php?msg=".$lang["not_authorized_events_category"]);
			}
		}
	}
	include "includes/header.php";
	$q = "SELECT * from ".$table_prefix."events where event_id = ".$id." limit 1";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		$cat = mysql_fetch_array($query);
		
?>
<h3><?php echo $lang["delete"]; ?>: <?php echo strip_tags($cat["title"]); ?>?</h3>
<p class="warning"><?php echo $lang["sure_delete_event"]; ?></p>
<form action="admin_actions.php" method="post">
<?php include "includes/hidden_fields.php"; ?>
<input type="hidden" name="id" id="id" value="<?php echo $cat["event_id"]; ?>">
<p><input type="submit" name="mode" value="<?php echo $lang["delete_event"]; ?>"></p>
<?php
	}
}
include "includes/footer.php";
?>