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
$page_title = $lang["title_edit_groups"];
$id = $_REQUEST["id"];
$edit=false;

function showGroups() {
	global $lang;
	echo "<h3>".$lang["select_group"]."</h3>\n";
	group_tree_edit(0);
	echo "<p><a href=\"".$PHP_SELF."?mode=edit_group&id=add\">".$lang["add_new_group"]."</a></p>\n";
}



function group_tree_edit($group_id) {
	global $table_prefix, $common_get,$lang;
	$q = "SELECT group_id, name from ".$table_prefix."groups where sub_of = ".$group_id." order by sequence";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		if (mysql_num_rows($query) > 0) {
			echo "<ul>\n";
			while ($row=mysql_fetch_row($query)) {
				echo "<li>".$row[1]." [<a href=\"".$PHP_SELF."?mode=edit_group&id=".$row[0]."&".$common_get."\">".$lang["edit"]."</a>]";
				if ($row[0] != 1) echo "&nbsp;&nbsp;[<a href=\"".$PHP_SELF."?mode=delete_group&id=".$row[0]."&".$common_get."\">".$lang["delete"]."</a>]\n";
				group_tree_edit($row[0]);
				echo "</li>\n";
			}
			echo "</ul>\n";
		}
	}
}
function group_tree_find($group_id,$find) {
	global $table_prefix, $indent, $id;
	$q = "SELECT group_id, name from ".$table_prefix."groups where sub_of = ".$group_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		
		while ($row=mysql_fetch_row($query)) {
			if ($row[0] != $id) {
				echo "<option value=\"".$row[0]."\"";
				if ($find == $row[0]) echo " selected";
				echo ">".$indent.$row[1]."</option>\n";
			
				$indent .= "__";
				group_tree_find($row[0],$find);
				$indent = substr($indent, 2);
			}
		}
		
	}
}

function deleteGroup($id) {
	global $table_prefix, $lang;
	$q = "SELECT * from ".$table_prefix."groups where group_id = ".$id." limit 1";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		$cat = mysql_fetch_array($query);
		
?>
<h3>Delete: <?php echo $cat["name"]; ?>?</h3>
<p class="warning"><?php echo $lang["sure_delete_group"]; ?></p>
<form action="admin_actions.php" method="post">
<?php include "includes/hidden_fields.php"; ?>
<input type="hidden" name="size" value="<?php echo $_REQUEST["size"]; ?>">
<input type="hidden" name="id" id="id" value="<?php echo $cat["group_id"]; ?>">
<p><?php echo $lang["move_existing_events_group"]; ?>:
<select name="sub_of" id="sub_of">
<?php group_tree_find(0,$cat["sub_of"]); ?>
</select>
<p><input type="submit" name="mode" value="<?php echo $lang["delete_group"]; ?>"></p>
<?php
	}
}

function editGroup($id) {
	global $table_prefix, $fck_editor_path, $fck_editor_toolbar, $lang;
	if ($id != "add") {
		$q = "SELECT * from ".$table_prefix."groups where group_id = ".$id." limit 1";
		$query = mysql_query($q);
		if (!$query) echo "Database Error : ".$q;
		else $cat = mysql_fetch_array($query);
		echo "<h3>".$lang["edit"].": ".$cat["name"]."</h3>\n";
	} else {
		echo "<h3>".$lang["add_new_group"]."</h3>\n";
	}
?>
<form action="admin_actions.php" method="post" name="cate" id="cate">
<input type="hidden" name="id" id="id" value="<?php echo $cat["group_id"]; ?>">
<table>
	<tr>
		<td colspan="2"><?php echo $lang["group_name"]; ?>: <input type="text" name="name" value="<?php echo $cat["name"]; ?>" size="20" maxlength="40"></td>
	</tr>
	<tr><td><?php echo $lang["parent_group"]; ?>: 
		<select name="sub_of" id="sub_of">
		<?php group_tree_find(0,$cat["sub_of"]); ?>
		</select>
		</td>
		<td><?php echo $lang["sequence"]; ?>: <input type="text" name="sequence" value="<?php echo $cat["sequence"]; ?>" size="2" maxlength="2"></td>
	</tr>
	
	

</table>
<p><input type="submit" name="mode" value="<?php echo $id == "add" ? $lang["add_group"] : $lang["edit_group"] ; ?>"></p>
</form>
<?php
}


if (!$_SESSION["user_id"]) {
	mysql_close($link);
	header("Location: ".$path."login.php?return_to=".$PHP_SELF);
} else {
	include "includes/header.php";
	$query = mysql_query("SELECT add_groups from ".$table_prefix."users where user_id = ".$_SESSION["user_id"]." limit 1");
	$row = mysql_fetch_row($query);
	if ($row[0] == 1) {
		$edit = true;
	} else {
		echo "<p class=\"warning\">".$lang["not_authorized_edit_groups"]."</p>\n";
	} 
}
if ($edit) {

	switch ($_REQUEST["mode"]) {
	case "edit_group";
		editGroup($id);
		break;
	
	case "delete_group";
		deleteGroup($id);
		break;
	
	default; 
		showGroups();
		break;
	}


}
include "includes/footer.php";
?>