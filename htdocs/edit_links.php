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
$page_title = $lang["title_edit_links"];
$id = $_REQUEST["id"];
$edit=false;

function showLinks() {
	global $table_prefix, $common_get, $lang;
	echo "<h3>".$lang["select_links"]."</h3>\n";
	$q = "SELECT link_id, state, city, company from links where company != '' order by state, city, company";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		echo "<ul>\n";
		while ($row=mysql_fetch_row($query)) {
			echo "<li>";
			if ($row[1]) echo $row[1]." : ";
			if ($row[2]) echo $row[2]." : ";
			echo $row[3]."&nbsp;&nbsp;[<a href=\"".$PHP_SELF."?mode=edit_link&id=".$row[0]."&".$common_get."\">".$lang["edit"]."</a>]&nbsp;&nbsp;[<a href=\"".$PHP_SELF."?mode=delete_link&id=".$row[0]."&".$common_get."\">Delete</a>]</li>\n";
			
			
		}
		echo "</ul>\n";
	}
	echo "<p><a href=\"".$PHP_SELF."?mode=edit_link&id=add\">".$lang["add_new_link"]."</a></p>\n";
}


function deleteLink($id) {
	global $table_prefix, $lang;
	$q = "SELECT * from links where link_id = ".$id." limit 1";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		$cat = mysql_fetch_array($query);
		
?>
<h3><?php echo $lang["delete"]; ?>: <?php echo $cat["company"]; ?>?</h3>
<p class="warning"><?php echo $lang["sure_delete_link"]; ?></p>
<form action="admin_actions.php" method="post">
<?php include "includes/hidden_fields.php"; ?>
<input type="hidden" name="id" id="id" value="<?php echo $cat["link_id"]; ?>">

<p><input type="submit" name="mode" value="<?php echo $lang["delete_link"]; ?>"></p>
<?php
	}
}

function editLink($id) {
	global $table_prefix,$fck_editor_path,$fck_editor_toolbar, $lang, $ck_editor_path;
	if ($id != "add") {
		$q = "SELECT * from links where link_id = ".$id." limit 1";
		$query = mysql_query($q);
		if (!$query) echo "Database Error : ".$q;
		else $cat = mysql_fetch_array($query);
		echo "<h3>".$lang["edit"].": ".$cat["name"]."</h3>\n";
	} else {
		echo "<h3>".$lang["add_new_link"]."</h3>\n";
	}
?>
<form action="admin_actions.php" method="post">
<input type="hidden" name="id" id="id" value="<?php echo $cat["link_id"]; ?>">
<table>
	<tr><td><?php echo $lang["company"]; ?>:</td><td><input type="text" name="company" value="<?php echo $cat["company"]; ?>"></td></tr>
	<tr><td><?php echo $lang["address1"]; ?>:</td><td><input type="text" name="address1" value="<?php echo $cat["address1"]; ?>"></td></tr>
	<tr><td><?php echo $lang["address2"]; ?>:</td><td><input type="text" name="address2" value="<?php echo $cat["address2"]; ?>"></td></tr>
	<tr><td><?php echo $lang["city"]; ?>:</td><td><input type="text" name="city" value="<?php echo $cat["city"]; ?>"></td></tr>
	<tr><td><?php echo $lang["state"]; ?>:</td><td><input type="text" name="state" value="<?php echo $cat["state"]; ?>"></td></tr>
	<tr><td><?php echo $lang["zip"]; ?>:</td><td><input type="text" name="zip" value="<?php echo $cat["zip"]; ?>"></td></tr>
	<tr><td><?php echo $lang["phone"]; ?>:</td><td><input type="text" name="phone" value="<?php echo $cat["phone"]; ?>"></td></tr>
	<tr><td><?php echo $lang["fax"]; ?>:</td><td><input type="text" name="fax" value="<?php echo $cat["fax"]; ?>"></td></tr>
	<tr><td><?php echo $lang["contact_name"]; ?>:</td><td><input type="text" name="contact" value="<?php echo $cat["contact"]; ?>"></td></tr>
	<tr><td><?php echo $lang["email"]; ?>:</td><td><input type="text" name="email" value="<?php echo $cat["email"]; ?>"></td></tr>
	<tr><td><?php echo $lang["web_site"]; ?>:</td><td><input type="text" name="url" value="<?php echo $cat["url"]; ?>"></td></tr>
	<tr>
		<td colspan="2"><?php echo "Description"; ?>:<br />
		<?php if ($fck_editor_path) {
	include($fck_editor_path."fckeditor.php") ;
	$oFCKeditor = new FCKeditor('description') ;
	$oFCKeditor->BasePath	= $calendar_url.$fck_editor_path ;
	$oFCKeditor->ToolbarSet	= $fck_editor_toolbar;
	$oFCKeditor->Value		= $cat["description"] ;
	$oFCKeditor->Height		= 400;
	
	$oFCKeditor->Create() ;
} else {
	echo "<textarea cols=\"60\" rows=\"10\" name=\"description\" id=\"description\">".$cat["description"]."</textarea>\n";
	if ($ck_editor_path) echo "<script language=\"JavaScript\"> CKEDITOR.replace( 'description' );</script>\n";
}
?></td>
	</tr>

</table>
<p><input type="submit" name="mode" value="<?php echo $id == "add" ? $lang["add_link"] : $lang["edit_link"] ; ?>"></p>
</form>
<?php
}


if (!$_SESSION["user_id"]) {
	mysql_close($link);
	header("Location: ".$path."login.php?return_to=".$PHP_SELF);
} else {
		include "header.php";
	$query = mysql_query("SELECT add_categories from users where user_id = ".$_SESSION["user_id"]." limit 1");
	$row = mysql_fetch_row($query);
	if ($row[0] == 1) {
		$edit = true;
	} else {
		echo "<p class=\"warning\">".$lang["not_authorzied_edit_links"]."</p>\n";
	} 
	
}
if ($edit) {

	switch ($_REQUEST["mode"]) {
	case "edit_link";
		editLink($id);
		break;
	
	case "delete_link";
		deleteLink($id);
		break;
	
	default; 
		showLinks();
		break;
	}


}
include "includes/footer.php";
?>