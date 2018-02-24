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
$page_title = $lang["title_edit_users"];
$id = $_REQUEST["id"];
$edit=false;

function showUsers() {
	global $table_prefix, $common_get, $lang;
	echo "<h3>".$lang["select_user"]."</h3>\n";
	$q = "SELECT user_id, email, view, post, add_categories, add_groups, add_users from ".$table_prefix."users";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		if (mysql_num_rows($query) > 0) {
			echo "<table>\n<tr><th>".$lang["username_email"]."</th><th>".$lang["view?"]."</th><th>".$lang["post?"]."</th><th>".$lang["edit_categories?"]."</th><th>".$lang["edit_groups?"]."</th><th>".$lang["edit_users?"]."</th><th>".$lang["action"]."</th></tr>\n";
			while ($row=mysql_fetch_row($query)) {
				echo "<tr><td>".$row[1]."</td><td>";
				echo $row[2] == 1 ? $lang["yes"] : $lang["no"] ;
				echo "</td><td>";
				echo $row[3] == 1 ? $lang["yes"] : $lang["no"] ;
				echo "</td><td>";
				echo $row[4] == 1 ? $lang["yes"] : $lang["no"] ;
				echo "</td><td>";
				echo $row[5] == 1 ? $lang["yes"] : $lang["no"] ;
				echo "</td><td>";
				echo $row[6] == 1 ? $lang["yes"] : $lang["no"] ;
				echo "</td><td> [<a href=\"user_profile.php?id=".$row[0]."&".$common_get."\">".$lang["edit"]."</a>]";
				if ($row[0] != 1) echo "&nbsp;&nbsp;[<a href=\"".$PHP_SELF."?mode=delete_user&id=".$row[0]."&".$common_get."\">".$lang["delete"]."</a>]</td></tr>\n";
			}
		echo "</table>\n";
		}
	}
	echo "<p><a href=\"user_profile.php?id=add\">".$lang["add_user"]."</a></p>\n";
}





function deleteUser($id) {
	global $table_prefix, $lang;
	$q = "SELECT user_id, email from ".$table_prefix."users where user_id = ".$id." limit 1";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		$cat = mysql_fetch_array($query);
		
?>
<h3><?php echo $lang["delete"]; ?>: <?php echo $cat["email"]; ?>?</h3>
<p class="warning"><?php echo $lang["sure_delete_user"]; ?></p>
<form action="admin_actions.php" method="post">
<?php include "includes/hidden_fields.php"; ?>
<input type="hidden" name="id" id="id" value="<?php echo $cat["user_id"]; ?>">
<p><input type="submit" name="mode" value="<?php echo $lang["delete_user"]; ?>"></p>
<?php
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
		$edit = true;
	} else {
		echo "<p class=\"warning\">".$lang["not_authorzied_edit_users"]."</p>\n";
	} 
}
if ($edit) {

	switch ($_REQUEST["mode"]) {
	case "delete_user";
		deleteUser($id);
		break;
	
	default; 
		showUsers();
		break;
	}


}
include "includes/footer.php";
?>