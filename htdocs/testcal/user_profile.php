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
$page_title = $lang["title_user_profile"];
$id = "";
$edit=false;

function group_tree_select($group_id,$group_access,$group_subscribe) {
	global $table_prefix, $edit;
	$q = "SELECT group_id, name from ".$table_prefix."groups where sub_of = ".$group_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		if (mysql_num_rows($query) > 0) {
			echo "<ul>\n";
			while ($row=mysql_fetch_row($query)) {
				echo "<li><input type=\"checkbox\" name=\"group[".$row[0]."]\" value=\"1\"";
				if ($group_access[$row[0]] >= "1") echo " checked";
				if (!$edit) echo " disabled";
				echo "> <input type=\"checkbox\" name=\"gpost[".$row[0]."]\" value=\"2\"";
				if ($group_access[$row[0]] > "1") echo " checked";
				if (!$edit) echo " disabled";
				echo "> <input type=\"checkbox\" name=\"gmoderate[".$row[0]."]\" value=\"3\"";
				if ($group_access[$row[0]] == "3") echo " checked";
				if (!$edit) echo " disabled";
				echo "> <input type=\"checkbox\" name=\"gsubscribe[".$row[0]."]\" value=\"1\"";
				if ($group_subscribe[$row[0]]) echo " checked";
				echo "> ".$indent.$row[1];
				group_tree_select($row[0],$group_access,$group_subscribe);
				echo "</li>\n";
			}
			echo "</ul>\n";
		}
	}
}

function category_tree_select($category_id,$category_access) {
	global $table_prefix, $edit;
	$q = "SELECT category_id, name from ".$table_prefix."categories where sub_of = ".$category_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		if (mysql_num_rows($query) > 0) {
			echo "<ul>\n";
			while ($row=mysql_fetch_row($query)) {
				echo "<li><input type=\"checkbox\" name=\"category[".$row[0]."]\" value=\"1\"";
				if ($category_access[$row[0]] >= "1") echo " checked";
				if (!$edit) echo " disabled";
				echo "> <input type=\"checkbox\" name=\"cpost[".$row[0]."]\" value=\"2\"";
				if ($category_access[$row[0]] > "1") echo " checked";
				if (!$edit) echo " disabled";
				echo "> <input type=\"checkbox\" name=\"cmoderate[".$row[0]."]\" value=\"3\"";
				if ($category_access[$row[0]] == "3") echo " checked";
				if (!$edit) echo " disabled";
				echo "> ".$indent.$row[1];
				category_tree_select($row[0],$category_access);
				echo "</li>\n";
			}
			echo "</ul>\n";
		}
	}
}



if (!$_SESSION["user_id"]) {
	mysql_close($link);
	header("Location: ".$path."login.php?return_to=".$PHP_SELF);
} else {
	include "includes/header.php";
	$query = mysql_query("SELECT add_users from ".$table_prefix."users where user_id = ".$_SESSION["user_id"]." limit 1");
	$row = mysql_fetch_row($query);
	if ($row[0] == 1) {
		$id = $_REQUEST["id"];
		$edit = true;
	} else {
		$id = $_SESSION["user_id"];
	} 
	
	
}
if ($id) {
	if ($id != "add") {
		$query = mysql_query("SELECT * from ".$table_prefix."users where user_id = ".$id." limit 1");
		if (mysql_num_rows($query) < 1) {
			echo "<p class=\"warning\">".$lang["user_not_found"]."</p>\n";
		} else {
			$row = mysql_fetch_array($query);
		}
		echo "<h2>".$lang["edit_user"].": ".$row["email"]."</h2>\n";
	} else {
		echo "<h2>".$lang["add_user"]."</h2>\n";
	}
?>

<form action="admin_actions.php" method="post">
<?php include "includes/hidden_fields.php"; ?>
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<div class="element">
<h4><?php echo $lang["username_password"]; ?></h4>
<table>
<tr>
<td><?php echo $lang["email"]; ?>:</td><td><input type="text" name="email" size="30" value="<?php echo $row["email"]; ?>"></td>
</tr>
<tr>
<td><?php echo $lang["password"]; ?><sup>*</sup>:<div style="font-size: .8em"><?php echo $lang["only_if_changing_password"]; ?></div></td><td><input type="password" name="new_password" size="30"></td>
</tr>
</table>
</div>

<div class="element">
<h4><?php echo $lang["privileges"]; ?></h4>

<?php
		echo "<p><input type=\"checkbox\" name=\"view\" value=\"yes\"";
		if ($row["view"]) echo " checked";
		if (!$edit) echo " disabled";
		echo "> ".$lang["view_calendar"]."<br />\n";
		echo "<input type=\"checkbox\" name=\"post\" value=\"yes\"";
		if ($row["post"]) echo " checked";
		if (!$edit) echo " disabled";
		echo "> ".$lang["post_events"]."<br />\n";
		echo "<input type=\"checkbox\" name=\"add_categories\" value=\"yes\"";
		if ($row["add_categories"]) echo " checked";
		if (!$edit) echo " disabled";
		echo "> ".$lang["edit_categories"]."<br />\n";
		echo "<input type=\"checkbox\" name=\"add_groups\" value=\"yes\"";
		if ($row["add_groups"]) echo " checked";
		if (!$edit) echo " disabled";
		echo "> ".$lang["edit_groups"]."<br />\n";
		echo "<input type=\"checkbox\" name=\"add_users\" value=\"yes\"";
		if ($row["add_users"]) echo " checked";
		if (!$edit) echo " disabled";
		echo "> ".$lang["edit_users"]."</p>\n";
		
?>
</div>
<div class="element">
<h4><?php echo $lang["category_access"]; ?></h4>
<?php
		if ($id != "add") {
			$query = mysql_query("SELECT category_id, moderate from ".$table_prefix."users_to_categories where user_id = ".$id);
			while($row = mysql_fetch_row($query)) {
				$access[$row[0]] = $row[1];
			}
		}
		category_tree_select(0,$access);
		
?>
</div>
<div class="element">
<h4><?php echo $lang["group_access"]; ?></h4>
<?php
		if ($id != "add") {
			$query = mysql_query("SELECT group_id, moderate, subscribe from ".$table_prefix."users_to_groups where user_id = ".$id);
			while($row = mysql_fetch_row($query)) {
				$gaccess[$row[0]] = $row[1];
				$subscribe[$row[0]] = $row[2];
				
			}
			
		}
		group_tree_select(0,$gaccess,$subscribe);
		
?>
</div>
<input type="hidden" name="return_to" value="<?php echo $_REQUEST["return_to"] ? $_REQUEST["return_to"]:$_SERVER['HTTP_REFERER']; ?>">
<p style="clear: left">
<p><input type="submit" name="mode" value="<?php echo $id == "add" ? "Add Profile" : "Update Profile" ; ?>"></p>
</form>
<?php	
	
} else {
	echo "<p class=\"warning\">".$lang["no_user_selected"]."</p>\n";
}
include "includes/footer.php";
?>