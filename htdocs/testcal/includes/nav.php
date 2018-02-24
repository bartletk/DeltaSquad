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

function group_nav($group_id) {
	global $table_prefix, $indent, $w, $supergroup;
	$q = "SELECT group_id, name from ".$table_prefix."groups where sub_of = ".$group_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		while ($row=mysql_fetch_row($query)) {
			$canview = false;
			if (!$supergroup) {
				$q = "SELECT * from ".$table_prefix."users_to_groups where group_id = ".$row[0]." and  user_id = ".$_SESSION["user_id"];
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
	$q = "SELECT category_id, name, color, background from ".$table_prefix."categories where sub_of = ".$category_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		while ($row=mysql_fetch_row($query)) {
			$canview = false;
			if (!$supercategory) {
				$q = "SELECT * from ".$table_prefix."users_to_categories where category_id = ".$row[0]." and user_id = ".$_SESSION["user_id"];
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


<form action="index.php" method="post" id="top_form" name="top_form" class="top_form">
<?php echo $lang["show_events_for"]; ?>:
<select name="w" id="w">
<?php 
$indent = "";
group_nav(0);
?>
</select>
<?php echo $lang["in_category"]; ?>:
<select name="c" id="c">
<?php
$indent = "";
category_nav(0);
?>
</select>
<?php echo $lang["for_date"]; ?>: <input type="text" name="godate" id="godate" value="<?php echo $m."/".$a."/".$y; ?>" size="10"> <a href="Javascript://" onclick="topcal.select(document.top_form.godate,this.name,'MM/dd/yyyy'); return false;" NAME="anchor_godate" ID="anchor_godate"><img src="images/calendar.png" border="0" /></a>
<input type="submit" value="<?php echo $lang["go"]; ?>">
</form>
<?php echo $lang["calendar_views"]; ?>:&nbsp;&nbsp;&nbsp;
<?php
$q = "SELECT module_id, link_name from ".$table_prefix."modules where active = 1 order by sequence";
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