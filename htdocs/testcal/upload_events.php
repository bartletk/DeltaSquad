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
$page_title = $lang["title_upload_events"];
$id = $_REQUEST["id"];
$edit=false;

function category_table($category_id) {
	global $table_prefix, $indent, $edit;
	$q = "SELECT category_id, name from ".$table_prefix."categories where sub_of = ".$category_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		
		while ($row=mysql_fetch_row($query)) {
			echo "<tr><td style=\"text-align:center\">".$row[0]."</td>";
			if (!$edit) {
				$q = "select * from ".$table_prefix."users_to_categories where category_id = ".$row[0]." and user_id = ".$_SESSION["user_id"];
				$qu = mysql_query($q);
				
			}
			
			echo "<td>".$indent.$row[1]."</td></tr>\n";
			
			$indent .= "__";
			category_table($row[0]);
			$indent = substr($indent, 2);
		}
		
	}
}

function group_table($group_id) {
	global $table_prefix, $indent, $edit;
	$q = "SELECT group_id, name from ".$table_prefix."groups where sub_of = ".$group_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		
		while ($row=mysql_fetch_row($query)) {
			echo "<tr><td style=\"text-align:center\">".$row[0]."</td>";
			if (!$edit) {
				$q = "select * from ".$table_prefix."users_to_groups where group_id = ".$row[0]." and user_id = ".$_SESSION["user_id"];
				$qu = mysql_query($q);
				
			}
			
			echo "<td>".$indent.$row[1]."</td></tr>\n";
			
			$indent .= "__";
			group_table($row[0]);
			$indent = substr($indent, 2);
		}
		
	}
}

function category_tree($category_id) {
	global $table_prefix, $indent, $supercategory, $scategory;
	$q = "SELECT category_id, name from ".$table_prefix."categories where sub_of = ".$category_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		
		while ($row=mysql_fetch_row($query)) {
			$mod = 0;
			
			if (!$supercategory) {
				$q = "select moderate from ".$table_prefix."users_to_categories where category_id = ".$row[0]." and user_id = ".$_SESSION["user_id"];
				$qu = mysql_query($q);
				if (mysql_num_rows($qu) > 0) $mod = mysql_result($qu,0,0);
			} else {
				$mod = 3;
			}
			if ($mod > 0) {
				echo "<option value=\"".$row[0]."\"";
				if($mod < 2) echo " disabled";
				if ($scategory == $row[0]) echo " SELECTED";
				echo ">".$indent.$row[1]."</option>\n";
			}
			
			
			$indent .= "__";
			category_tree($row[0]);
			$indent = substr($indent, 2);
		}
		
	}
}

function group_tree($group_id) {
	global $table_prefix, $indent, $supergroup, $sgroup;
	$q = "SELECT group_id, name from ".$table_prefix."groups where sub_of = ".$group_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		
		while ($row=mysql_fetch_row($query)) {
			$mod = 0;
			
			if (!$supergroup) {
				$q = "select moderate from ".$table_prefix."users_to_groups where group_id = ".$row[0]." and user_id = ".$_SESSION["user_id"];
				$qu = mysql_query($q);
				if (mysql_num_rows($qu) > 0) $mod = mysql_result($qu,0,0);
			} else {
				$mod = 3;
			}
			if ($mod > 0) {
				echo "<option value=\"".$row[0]."\"";
				if($mod < 2) echo " disabled";
				if ($sgroup == $row[0]) echo " SELECTED";
				echo ">".$indent.$row[1]."</option>\n";
			}
			
			
			$indent .= "__";
			group_tree($row[0]);
			$indent = substr($indent, 2);
		}
		
	}
}

function select_place($field) {
	global $table_prefix, $indent;
	$q = "SELECT link_id, state, city, company from ".$table_prefix."links where link_id > 1 order by state, city, company";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		while ($row=mysql_fetch_row($query)) {
			echo "<option value=\"".$row[0]."\"";
			if ($field) {
				if ($field == $row[0]) echo " SELECTED";
			}
			echo ">";
			if ($row[1]) echo $row[1]." : ";
			if ($row[2]) echo $row[2]." : ";
			echo $row[3]."</option>\n";
			
			
		}
	}
}


function uploadForm() {
global $table_prefix, $lang;
?>
<div class="sidebar">
<h4><?php echo $lang["csv_upload_instructions_title"]; ?></h4>
<?php echo $lang["csv_upload_instructions"]; ?>
<table class="grid" style="margin: 10px 0px;">
<tr><th style="width:auto;"><?php echo $lang["id"]; ?></th><th style="width:auto;"><?php echo $lang["city"]; ?></th><th style="width:auto;"><?php echo $lang["state"]; ?></th><th style="width:auto;"><?php echo $lang["company"]; ?></th></tr>
<tr><td style="text-align:center">1</td><td colspan="3"><?php echo $lang["default_information_shown_event_description"]; ?></td></tr>
<?php
	$q = "SELECT link_id, state, city, company from ".$table_prefix."links where state !='' and city != '' order by state, city, company";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		while ($row=mysql_fetch_row($query)) {
			echo "<tr><td style=\"text-align:center;\">".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>\n";
		}
	}
?>
</table>
<h4><?php echo $lang["table_categories_title"]; ?></h4>
<?php echo $lang["table_categories"]; ?>
<table class="grid" style="margin: 10px 0px;">
<tr><th style="width:auto;">ID</th><th style="width:auto;">Category</th></tr>
<?php
category_table(0);
?>
</table>
<h4><?php echo $lang["table_groups_title"]; ?></h4>
<?php echo $lang["table_groups"]; ?>
<table class="grid" style="margin: 10px 0px;">
<tr><th style="width:auto;">ID</th><th style="width:auto;">Group</th></tr>
<?php
group_table(0);
?>
</table>
</div>
<form action="<?php echo $PHP_SELF; ?>" method="post" enctype="multipart/form-data">
<h3><?php echo $lang["upload_csv_file"]; ?></h3>

<?php include "includes/hidden_fields.php"; ?>
<input type="hidden" name="MAX_FILE_SIZE" value="5120000">
<table>
<tr><td><?php echo $lang["file"]; ?>:</td><td><input name="data" type="file" size="30"></td></tr>
<tr><td><?php echo $lang["fields_separated_by"]; ?>:</td><td><input type="text" name="separator" id="separator" value="," size="2"></td></tr>
<tr><td><?php echo $lang["default_group"]; ?>:</td><td><select name="group" id="group" size="1">
<?php group_tree(0,1); ?></select></td></tr>
<tr><td><?php echo $lang["default_category"]; ?>:</td><td><select name="category" id="category" size="1">
<?php category_tree(0,1); ?></select></td></tr>
<tr><td><?php echo $lang["default_venue"]; ?>:</td><td><select name="venue" id="venue" size="1"><option value="1"><?php echo $lang["in_main_description"]; ?></option>
<?php select_place(1); ?></select></td></tr>
<tr><td><?php echo $lang["default_contact"]; ?>:</td><td><select name="contact" id="contact" size="1"><option value="1"><?php echo $lang["none"]; ?></option>
<?php select_place(1); ?></select></td></tr>
</table>
<input type="submit" NAME="mode" VALUE="<?php echo $lang["upload_csv_file"]; ?>">
</form>
<div style="clear:right"></div>
<?php
}

function processUpload() {
global $lang, $scategory, $sgroup;
$datafile = $_FILES['data']['tmp_name'];
$fp = fopen($datafile, "r");
fgetcsv($fp, 10000, $_POST["separator"])  //dump first line headers

?>
<form action="<?php echo $PHP_SELF; ?>" method="post">
<?php include "includes/hidden_fields.php"; ?>
<h3><?php echo $lang["verify_event_listings_title"]; ?></h3>
<?php echo $lang["verify_event_listings"]; ?>
<table>
<tr><th><?php echo $lang["title"]; ?></th><th><?php echo $lang["venue"]; ?></th><th><?php echo $lang["contact_sponsor"]; ?></th><th><?php echo $lang["category"]; ?></th><th><?php echo $lang["group"]; ?></th><th><?php echo $lang["date"]; ?></th><th><?php echo $lang["start_time"]; ?></th><th><?php echo $lang["end_time"]; ?></th><th><?php echo $lang["description"]; ?></th></tr>
<?php


while (($data = fgetcsv($fp, 10000, $_POST["separator"])) !== FALSE) {
	$start = preg_replace("/([0-9]{1,2}:[0-9]{2}):[0-9]{2}([ ]?[a|p]m)/i","\\1\\2",$data[6]);
	$end = preg_replace("/([0-9]{1,2}:[0-9]{2}):[0-9]{2}([ ]?[a|p]m)/i","\\1\\2",$data[7]);
	if (!preg_match("/([0-9]{1,2})[\/-]+([0-9]{1,2})[\/-]+([0-9]{4})/i",$data[5])) $dc = " class=\"error\"";
	if (!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$start)) $sc = " class=\"error\"";
	if (($end)&&(!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$end))) $ec = " class=\"error\"";
	$venue = $data[1] ? $data[1] : $_REQUEST["venue"];
	$contact = $data[2] ? $data[2] : $_REQUEST["contact"];
	$scategory = $data[3] ? $data[3] : $_REQUEST["category"];
	$sgroup = $data[3] ? $data[4] : $_REQUEST["group"];
	echo "<tr>\n";
	echo "<td><input type=\"text\" size=\"20\" id=\"title[]\" name=\"title[]\" value=\"".$data[0]."\"></td>\n";
	echo "<td><select name=\"venue[]\" id=\"venue[]\" size=\"1\"><option value=\"1\">".$lang["in_main_description"]."</option>\n";
	select_place($venue);
	echo "</select></td>\n";
	echo "<td><select name=\"contact[]\" id=\"contact[]\" size=\"1\"><option value=\"1\">".$lang["none"]."</option>\n";
	select_place($contact);
	echo "</select></td>\n";
	echo "<td><select name=\"category[]\" id=\"category[]\" size=\"1\">\n";
	category_tree(0);
	echo "</select></td>\n";
	echo "<td><select name=\"group[]\" id=\"group[]\" size=\"1\">\n";
	group_tree(0);
	echo "</select></td>\n";
	echo "<td><input type=\"text\"".$dc." id=\"date[]\" size=\"10\" name=\"date[]\" value=\"".$data[5]."\"></td>\n";
	echo "<td><input type=\"text\"".$sc." id=\"start[]\" size=\"8\" name=\"start[]\" value=\"".$start."\"></td>\n";
	echo "<td><input type=\"text\"".$ec." id=\"end[]\" size=\"8\" name=\"end[]\" value=\"".$end."\"></td>\n";
	echo "<td><textarea cols=\"30\" rows=\"1\" name=\"description[]\">".$data[8]."</textarea></td>\n";
	echo "</tr>\n";
}
fclose($fp);
unlink($datafile);
echo "</table>\n";
echo "<INPUT TYPE=\"submit\" NAME=\"mode\" VALUE=\"".$lang["add_events"]."\"></form>";
}

function addEvents() {
global $o,$c,$m,$a,$y,$w,$id,$table_prefix,$page_title,$calendar_title,$supergroup, $supercategory, $lang;
$title = $_REQUEST["title"];
$venue = $_REQUEST["venue"];
$contact = $_REQUEST["contact"];
$category = $_REQUEST["category"];
$group = $_REQUEST["group"];
$date = $_REQUEST["date"];
$start = $_REQUEST["start"];
$end = $_REQUEST["end"];
$description = $_REQUEST["description"];


while (list($k,$v) = each($start)) {
	$dstart = preg_replace("/([0-9]{1,2}:[0-9]{2}):[0-9]{2}([ ]?[a|p]m)/i","\\1\\2",$v);
	$dend = preg_replace("/([0-9]{1,2}:[0-9]{2}):[0-9]{2}([ ]?[a|p]m)/i","\\1\\2",$end[$k]);
	$dc="";
	$sc="";
	$ec="";
	if (!preg_match("/([0-9]{1,2})[\/-]+([0-9]{1,2})[\/-]+([0-9]{4})/i",$date[$k])) $dc = " class=\"error\"";
	if (!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$dstart)) $sc = " class=\"error\"";
	if (($dend)&&(!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$dend))) $ec = " class=\"error\"";
	if ($dc|$sc|$ec) $verify .= "There are date/time errors in your listings. ";
	if (!$supercategory) {
		$q = "select * from ".$table_prefix."users_to_categories where category_id = ".$category[$k]." and user_id = ".$_SESSION["user_id"];
		$qu = mysql_query($q);
		if (mysql_num_rows($qu) < 2) $verify = $lang["no_permision_post_events"];
	}
	if (!$supergroup) {
		$q = "select * from ".$table_prefix."users_to_groups where group_id = ".$group[$k]." and user_id = ".$_SESSION["user_id"];
		$qu = mysql_query($q);
		if (mysql_num_rows($qu) < 2) $verify = $lang["no_permision_post_events"];
	}
	// verify categories
}
reset($start);
if ($verify) {
	include "includes/header.php";
?>
<form action="<?php echo $PHP_SELF; ?>" method="post">
<?php include "includes/hidden_fields.php"; ?>
<h3><?php echo $lang["verify_event_listings_title"]; ?></h3>
<p><?php echo $lang["errors_in_listings"]; ?> <?php echo $verify; ?></p>
<table>
<tr><th><?php echo $lang["title"]; ?></th><th><?php echo $lang["venue"]; ?></th><th><?php echo $lang["contact_sponsor"]; ?></th><th><?php echo $lang["category"]; ?></th><th><?php echo $lang["group"]; ?></th><th><?php echo $lang["date"]; ?></th><th><?php echo $lang["start_time"]; ?></th><th<?php echo $lang["end_time"]; ?></th><th><?php echo $lang["description"]; ?></th></tr>
<?php
	while (list($k,$v) = each($start)) {
		$dstart = preg_replace("/([0-9]{1,2}:[0-9]{2}):[0-9]{2}([ ]?[a|p]m)/i","\\1\\2",$v);
		$dend = preg_replace("(/[0-9]{1,2}:[0-9]{2}):[0-9]{2}([ ]?[a|p]m)/i","\\1\\2",$end[$k]);
		$dc="";
		$sc="";
		$ec="";
		if (!preg_match("/([0-9]{1,2})[\/-]+([0-9]{1,2})[\/-]+([0-9]{4})/",$date[$k])) $dc = " class=\"error\"";
		if (!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$dstart)) $sc = " class=\"error\"";
		if (($dend)&&(!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$dend))) $ec = " class=\"error\"";
		echo "<tr>\n";
		echo "<td><input type=\"text\" size=\"20\" id=\"title[]\" name=\"title[]\" value=\"".$title[$k]."\"></td>\n";
		echo "<td><select name=\"venue[]\" id=\"venue[]\" size=\"1\"><option value=\"1\">".$lang["in_main_description"]."</option>\n";
		select_place($venue[$k]);
		echo "</select></td>\n";
		echo "<td><select name=\"contact[]\" id=\"contact[]\" size=\"1\"><option value=\"1\">".$lang["none"]."</option>\n";
		select_place($contact[$k]);
		echo "</select></td>\n";
		echo "<td><select name=\"category[]\" id=\"category[]\" size=\"1\">\n";
		$scategory = $category[$k];
		category_tree(0);
		echo "</select></td>\n";
		echo "<td><select name=\"category[]\" id=\"category[]\" size=\"1\">\n";
		$sgroup = $group[$k];
		group_tree(0);
		echo "</select></td>\n";
		echo "<td><input type=\"text\"".$dc." id=\"date[]\" size=\"10\" name=\"date[]\" value=\"".$date[$k]."\"></td>\n";
		echo "<td><input type=\"text\"".$sc." id=\"start[]\" size=\"8\" name=\"start[]\" value=\"".$dstart."\"></td>\n";
		echo "<td><input type=\"text\"".$ec." id=\"end[]\" size=\"8\" name=\"end[]\" value=\"".$dend."\"></td>\n";
		echo "<td><textarea cols=\"30\" rows=\"1\" name=\"description[]\">".$description[$k]."</textarea></td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "<INPUT TYPE=\"submit\" NAME=\"mode\" VALUE=\"".$lang["add_events"]."\"></form>";
} else  {
	while (list($k,$v) = each($start)) {
		$dstart = preg_replace("/([0-9]{1,2}:[0-9]{2}):[0-9]{2}([ ]?[a|p]m)/i","\\1\\2",$v);
		$dend = preg_replace("/([0-9]{1,2}:[0-9]{2}):[0-9]{2}([ ]?[a|p]m)/i","\\1\\2",$end[$k]);
		$title[$k] = addslashes(strip_tags($title[$k]));
		$descriptioni[$k] = addslashes($description[$k]);
		$q = "INSERT into ".$table_prefix."events (title, venue_id, contact_id, description, category_id, user_id, group_id) values ('".$title[$k]."', ".$venue[$k].", ".$contact[$k].", '".$description[$k]."', ".$category[$k].", ".$_SESSION["user_id"].", ".$group[$k].")";
		$query = mysql_query($q);
		$event_id = mysql_insert_id();
		if (!$query) $msg .= "Database Error : ".$q;
		else {
			if (!preg_match("/([0-9]{1,2})[\/-]+([0-9]{1,2})[\/-]+([0-9]{4})/i",$date[$k],$d_date)) {
				$msg .= "Bad Date:".$date[$k];
			} else {
				if (!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$dstart,$start_time)) {
					$msg .= "Bad Start Time:".$dstart;
				} else {
					if (($dend)&&(!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$dend,$end_time))) {
						$msg .= "Bad End Time:".$dend;
					} else {
						if (strlen($d_date[1]) == 1) $d_date[1] = "0".$d_date[1];
						if (strlen($d_date[2]) == 1) $d_date[2] = "0".$d_date[2];
						if ((preg_match("/pm/i",$start_time[3])) && ($start_time[1] < 12)) $start_time[1] = $start_time[1] + 12;
						if ((preg_match("/pm/i",$end_time[3])) && ($end_time[1] < 12)) $end_time[1] = $end_time[1] + 12;							
						if (strlen($start_time[1]) == 1) $start_time[1] = "0".$start_time[1];
						if (strlen($end_time[1]) == 1) $end_time[1] = "0".$end_time[1];
						$start_date = $d_date[3]."-".$d_date[1]."-".$d_date[2]." ".$start_time[1].":".$start_time[2];
						if ($dend) {
							$end_date = $d_date[3]."-".$d_date[1]."-".$d_date[2]." ".$end_time[1].":".$end_time[2];
							if (($end_time[1].$end_time[2]) > ($start_time[1].$start_time[2])) {
								$q = "INSERT into ".$table_prefix."dates (event_id, date, end_date) values (".$event_id.", '".$start_date."', '".$end_date."')";
							} else {
								$q = "INSERT into ".$table_prefix."dates (event_id, date) values (".$event_id.", '".$start_date."')";
							}
						} else {
							$q = "INSERT into ".$table_prefix."dates (event_id, date) values (".$event_id.", '".$start_date."')";
						}
						$query = mysql_query($q);
						if (!$query) $msg .= "Database Error : ".$q;
					}
				}
			}
		}
	}
	if (!$msg) $msg =  $lang["events_added"];
	header("Location: upload_events.php?msg=".$msg."&".$common_get);
}

}



if (!$superpost) {
	mysql_close($link);
	$msg =  $lang["no_permision_post_events"];
	header("Location: upload_events.php?msg=".$msg."&".$common_get);
} else {
	
	
	switch ($_REQUEST["mode"]) {
	case $lang["upload_csv_file"];
		include "includes/header.php";
		processUpload();
		break;
	case $lang["add_events"];
		addEvents();
		break;
	default; 
		include "includes/header.php";
		uploadForm();
		break;
	}


}
include "includes/footer.php";
?>