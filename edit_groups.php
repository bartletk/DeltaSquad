<?php

include "include/start.php";
$page_title = "Edit Groups";
$id = $_REQUEST["id"];
$edit=false;

function showGroups() {
	global $lang;
	echo "<h3>"."Select Group"."</h3>\n";
	group_tree_edit(0);
	echo "<p><a href=\"".$PHP_SELF."?mode=edit_group&id=add\">"."Add New Group"."</a></p>\n";
}



function group_tree_edit($group_id) {
	global $table_prefix, $common_get,$lang;
	$q = "SELECT group_id, name from groups where sub_of = ".$group_id." order by sequence";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		if (mysql_num_rows($query) > 0) {
			echo "<ul>\n";
			while ($row=mysql_fetch_row($query)) {
				echo "<li>".$row[1]." [<a href=\"".$PHP_SELF."?mode=edit_group&id=".$row[0]."&".$common_get."\">"."Edit"."</a>]";
				if ($row[0] != 1) echo "&nbsp;&nbsp;[<a href=\"".$PHP_SELF."?mode=delete_group&id=".$row[0]."&".$common_get."\">Delete</a>]\n";
				group_tree_edit($row[0]);
				echo "</li>\n";
			}
			echo "</ul>\n";
		}
	}
}
function group_tree_find($group_id,$find) {
	global $table_prefix, $indent, $id;
	$q = "SELECT group_id, name from groups where sub_of = ".$group_id." order by name";
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
	$q = "SELECT * from groups where group_id = ".$id." limit 1";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		$cat = mysql_fetch_array($query);
		
?>
<h3>Delete: <?php echo $cat["name"]; ?>?</h3>
<p class="warning"><?php echo "Are you sure you want to delete this group?"; ?></p>
<form action="admin_actions.php" method="post">
<?php include "include/hidden_fields.php"; ?>
<input type="hidden" name="size" value="<?php echo $_REQUEST["size"]; ?>">
<input type="hidden" name="id" id="id" value="<?php echo $cat["group_id"]; ?>">
<p><?php echo "Move any existing events, subgroups in this group to"; ?>:
<select name="sub_of" id="sub_of">
<?php group_tree_find(0,$cat["sub_of"]); ?>
</select>
<p><input type="submit" name="mode" value="<?php echo "Delete Group"; ?>"></p>
<?php
	}
}

function editGroup($id) {
	global $table_prefix, $fck_editor_path, $fck_editor_toolbar, $lang;
	if ($id != "add") {
		$q = "SELECT * from groups where group_id = ".$id." limit 1";
		$query = mysql_query($q);
		if (!$query) echo "Database Error : ".$q;
		else $cat = mysql_fetch_array($query);
		echo "<h3>"."Edit".": ".$cat["name"]."</h3>\n";
	} else {
		echo "<h3>"."Add New Group"."</h3>\n";
	}
?>
<form action="admin_actions.php" method="post" name="cate" id="cate">
<input type="hidden" name="id" id="id" value="<?php echo $cat["group_id"]; ?>">
<table>
	<tr>
		<td colspan="2"><?php echo "Group Name"; ?>: <input type="text" name="name" value="<?php echo $cat["name"]; ?>" size="20" maxlength="40"></td>
	</tr>
	<tr><td><?php echo "Parent Group"; ?>: 
		<select name="sub_of" id="sub_of">
		<?php group_tree_find(0,$cat["sub_of"]); ?>
		</select>
		</td>
		<td><?php echo "Sequence"; ?>: <input type="text" name="sequence" value="<?php echo $cat["sequence"]; ?>" size="2" maxlength="2"></td>
	</tr>
	
	

</table>
<p><input type="submit" name="mode" value="<?php echo $id == "add" ? "Add Group" : "Edit Group" ; ?>"></p>
</form>
<?php
}


if (!$_SESSION["user_id"]) {
	mysql_close($link);
	header("Location: ".$path."login.php?return_to=".$PHP_SELF);
} else {
		include "header.php";
	$query = mysql_query("SELECT add_groups from users where user_id = ".$_SESSION["user_id"]." limit 1");
	$row = mysql_fetch_row($query);
	if ($row[0] == 1) {
		$edit = true;
	} else {
		echo "<p class=\"warning\">"."You are not authorized to edit groups."."</p>\n";
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

?>