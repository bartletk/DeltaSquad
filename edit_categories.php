<?php

include "include/start.php";
$page_title = "Edit Categories";
$id = $_REQUEST["id"];
$edit=false;

function showCategories() {
	global $lang;
	echo "<h3>"."Select Category"."</h3>\n";
	category_tree_edit(0);
	echo "<p><a href=\"".$PHP_SELF."?mode=edit_category&id=add\">"."Add New Category"."</a></p>\n";
}



function category_tree_edit($category_id) {
	global $table_prefix, $common_get,$lang;
	$q = "SELECT category_id, name from categories where sub_of = ".$category_id." order by sequence";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		if (mysql_num_rows($query) > 0) {
			echo "<ul>\n";
			while ($row=mysql_fetch_row($query)) {
				echo "<li>".$row[1]." [<a href=\"".$PHP_SELF."?mode=edit_category&id=".$row[0]."&".$common_get."\">"."Edit"."</a>]";
				if ($row[0] != 1) echo "&nbsp;&nbsp;[<a href=\"".$PHP_SELF."?mode=delete_category&id=".$row[0]."&".$common_get."\">Delete</a>]\n";
				category_tree_edit($row[0]);
				echo "</li>\n";
			}
			echo "</ul>\n";
		}
	}
}
function category_tree_find($category_id,$find) {
	global $table_prefix, $indent, $id;
	$q = "SELECT category_id, name from categories where sub_of = ".$category_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		
		while ($row=mysql_fetch_row($query)) {
			if ($row[0] != $id) {
				echo "<option value=\"".$row[0]."\"";
				if ($find == $row[0]) echo " selected";
				echo ">".$indent.$row[1]."</option>\n";
			
				$indent .= "__";
				category_tree_find($row[0],$find);
				$indent = substr($indent, 2);
			}
		}
		
	}
}

function deleteCategory($id) {
	global $table_prefix, $lang;
	$q = "SELECT * from categories where category_id = ".$id." limit 1";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		$cat = mysql_fetch_array($query);
		
?>
<h3>Delete: <?php echo $cat["name"]; ?>?</h3>
<p class="warning"><?php echo "Are you sure you want to delete this category?"; ?></p>
<form action="admin_actions.php" method="post">
<?php include "include/hidden_fields.php"; ?>
<input type="hidden" name="size" value="<?php echo $_REQUEST["size"]; ?>">
<input type="hidden" name="id" id="id" value="<?php echo $cat["category_id"]; ?>">
<p><?php echo "Move any existing events, subcategories in this category to"; ?>:
<select name="sub_of" id="sub_of">
<?php category_tree_find(0,$cat["sub_of"]); ?>
</select>
<p><input type="submit" name="mode" value="<?php echo "Delete Category"; ?>"></p>
<?php
	}
}

function editCategory($id) {
	global $table_prefix, $fck_editor_path, $fck_editor_toolbar, $lang, $ck_editor_path;
	if ($id != "add") {
		$q = "SELECT * from categories where category_id = ".$id." limit 1";
		$query = mysql_query($q);
		if (!$query) echo "Database Error : ".$q;
		else $cat = mysql_fetch_array($query);
		echo "<h3>"."Edit".": ".$cat["name"]."</h3>\n";
	} else {
		echo "<h3>"."Add New Category"."</h3>\n";
	}
?>
<form action="admin_actions.php" method="post" name="cate" id="cate">
<input type="hidden" name="id" id="id" value="<?php echo $cat["category_id"]; ?>">
<table>
	<tr>
		<td colspan="2"><?php echo "Cateogory Name"; ?>: <input type="text" name="name" value="<?php echo $cat["name"]; ?>" size="20" maxlength="40" style="color: <?php echo $cat["color"]; ?>; background-color: <?php echo $cat["background"]; ?>;".></td>
	</tr>
	<tr><td><?php echo "Parent Category"; ?>: 
		<select name="sub_of" id="sub_of">
		<?php category_tree_find(0,$cat["sub_of"]); ?>
		</select>
		</td>
		<td><?php echo "Sequence"; ?>: <input type="text" name="sequence" value="<?php echo $cat["sequence"]; ?>" size="2" maxlength="2"></td>
	</tr>
	<tr><td><?php echo "Text Color"; ?>:  <input type="text" name="color" value="<?php echo $cat["color"]; ?>" size="10" maxlength="30" onChange="this.form.name.style.color=this.value;"> <A HREF="#" onClick="cp.select(cate.color,'pick');return false;" NAME="pick" ID="pick">Pick</A>
		</td>
		<td><?php echo "Background"; ?>: <input type="text" name="background" value="<?php echo $cat["background"]; ?>" size="10" maxlength="255" onChange="this.form.name.style.background=this.value;">  <A HREF="#" onClick="cp.select(cate.background,'pick2');return false;" NAME="pick2" ID="pick2">Pick</A></td>
	</tr>
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
?>
		</td>
	</tr>

</table>
<p><input type="submit" name="mode" value="<?php echo $id == "add" ? Add Category : "Edit Category" ; ?>"></p>
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
		echo "<p class=\"warning\">"."You are not authorized to edit categories."."</p>\n";
	} 
}
if ($edit) {

	switch ($_REQUEST["mode"]) {
	case "edit_category";
		editCategory($id);
		break;
	
	case "delete_category";
		deleteCategory($id);
		break;
	
	default; 
		showCategories();
		break;
	}


}

?>