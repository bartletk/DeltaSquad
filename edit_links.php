<?php

include "include/start.php";
$page_title = "Edit Links";
$id = $_REQUEST["id"];
$edit=false;

function showLinks() {
	global $table_prefix, $common_get, $lang;
	echo "<h3>"."Select Links"."</h3>\n";
	$q = "SELECT link_id, state, city, company from links where company != '' order by state, city, company";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		echo "<ul>\n";
		while ($row=mysql_fetch_row($query)) {
			echo "<li>";
			if ($row[1]) echo $row[1]." : ";
			if ($row[2]) echo $row[2]." : ";
			echo $row[3]."&nbsp;&nbsp;[<a href=\"".$PHP_SELF."?mode=edit_link&id=".$row[0]."&".$common_get."\">"."Edit"."</a>]&nbsp;&nbsp;[<a href=\"".$PHP_SELF."?mode=delete_link&id=".$row[0]."&".$common_get."\">Delete</a>]</li>\n";
			
			
		}
		echo "</ul>\n";
	}
	echo "<p><a href=\"".$PHP_SELF."?mode=edit_link&id=add\">"."Add New Link"."</a></p>\n";
}


function deleteLink($id) {
	global $table_prefix, $lang;
	$q = "SELECT * from links where link_id = ".$id." limit 1";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		$cat = mysql_fetch_array($query);
		
?>
<h3><?php echo "Delete"; ?>: <?php echo $cat["company"]; ?>?</h3>
<p class="warning"><?php echo "Are you sure you want to delete this link?"; ?></p>
<form action="admin_actions.php" method="post">
<?php include "include/hidden_fields.php"; ?>
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
		echo "<h3>"."Edit".": ".$cat["name"]."</h3>\n";
	} else {
		echo "<h3>"."Add New Link"."</h3>\n";
	}
?>
<form action="admin_actions.php" method="post">
<input type="hidden" name="id" id="id" value="<?php echo $cat["link_id"]; ?>">
<table>
	<tr><td><?php echo "Company"; ?>:</td><td><input type="text" name="company" value="<?php echo $cat["company"]; ?>"></td></tr>
	<tr><td><?php echo "Address1"; ?>:</td><td><input type="text" name="address1" value="<?php echo $cat["address1"]; ?>"></td></tr>
	<tr><td><?php echo "Address"; ?>:</td><td><input type="text" name="address2" value="<?php echo $cat["address2"]; ?>"></td></tr>
	<tr><td><?php echo "City"; ?>:</td><td><input type="text" name="city" value="<?php echo $cat["city"]; ?>"></td></tr>
	<tr><td><?php echo "State"; ?>:</td><td><input type="text" name="state" value="<?php echo $cat["state"]; ?>"></td></tr>
	<tr><td><?php echo "Zip"; ?>:</td><td><input type="text" name="zip" value="<?php echo $cat["zip"]; ?>"></td></tr>
	<tr><td><?php echo "Phone"; ?>:</td><td><input type="text" name="phone" value="<?php echo $cat["phone"]; ?>"></td></tr>
	<tr><td><?php echo "Fax"; ?>:</td><td><input type="text" name="fax" value="<?php echo $cat["fax"]; ?>"></td></tr>
	<tr><td><?php echo "Contact Name"; ?>:</td><td><input type="text" name="contact" value="<?php echo $cat["contact"]; ?>"></td></tr>
	<tr><td><?php echo "E-mail"; ?>:</td><td><input type="text" name="email" value="<?php echo $cat["email"]; ?>"></td></tr>
	<tr><td><?php echo "Web Site"; ?>:</td><td><input type="text" name="url" value="<?php echo $cat["url"]; ?>"></td></tr>
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
<p><input type="submit" name="mode" value="<?php echo $id == "add" ? "Add Link" : $lang["edit_link"] ; ?>"></p>
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

?>