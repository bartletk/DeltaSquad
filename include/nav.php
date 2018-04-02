<?php


function group_nav($group_id) {
	global $table_prefix, $indent, $w, $supergroup;
	$q = "SELECT group_id, name from groups where sub_of = ".$group_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		while ($row=mysql_fetch_row($query)) {
			$canview = false;
			if (!$supergroup) {
				$q = "SELECT * from users_to_groups where group_id = ".$row[0]." and  user_id = ".$_SESSION["user_id"];
				$squery = mysql_query($q);
				if (mysql_num_rows($squery) > 0) $canview = true;
			} else {
				$canview = true;
			}
			if ($canview) {
				echo "<option value=\"".$row[0]."\"";
				if ($w == $row[0]) echo " selected";
				echo ">".$indent.$row[1]."</option>\n";
				
				$indent .= "__";
				group_nav($row[0]);
				$indent = substr($indent, 2);
			}
		}
		
	}
}

function category_nav($category_id) {
	global $table_prefix, $indent, $c, $supercategory;
	$q = "SELECT category_id, name, color, background from categories where sub_of = ".$category_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		while ($row=mysql_fetch_row($query)) {
			$canview = false;
			if (!$supercategory) {
				$q = "SELECT * from users_to_categories where category_id = ".$row[0]." and user_id = ".$_SESSION["user_id"];
				$squery = mysql_query($q);
				if (mysql_num_rows($squery) > 0) $canview = true;
			} else {
				$canview = true;
			}
			if ($canview) {
			
				echo "<option value=\"".$row[0]."\"";
				if ($c == $row[0]) echo " selected";
				echo " style=\"color: ".$row[2]."; background-color: ".$row[3].";\">".$indent.$row[1]."</option>\n";
			}
			$indent .= "__";
			category_nav($row[0]);
			$indent = substr($indent, 2);
		}
		
	}
}
?>



<?php echo "Calendar Views"; ?>:&nbsp;&nbsp;&nbsp;
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
?>