<?php



$javascript = '<script language="JavaScript">
	var cal = new CalendarPopup("testdiv1");
	cal.offsetX=-20;
	cal.offsetY=20;
	var testpopup5 = new PopupWindow("timelayer");
testpopup5.offsetX=-20;
testpopup5.offsetY=20;
testpopup5.autoHide();
var testpopup5input=null;
function test5popupactivate(obj,anchor) {
	testpopup5input=obj;
	testpopup5.showPopup(anchor);
	}
function testpopup5pick(val) {
	testpopup5input.value = val;
	testpopup5.hidePopup();
	}
function null_out(t,i) {
	if ((t.value == "all") || (t.value == "tba")){
		eval("t.form.start_time_" + i +".disabled=true");
		eval("t.form.end_time_" + i +".disabled=true");
		
		
		eval("t.form.start_time_" + i +".value=\'12:00 am\'");
		if (t.value == "all") {
			eval("t.form.end_time_" + i +".value=\'11:59 pm\'");
		} else {
			eval("t.form.end_time_" + i +".value=\'12:00 am\'");
		}
		eval("turn_off(\'anchor_time_start_" + i +"\')");
		eval("turn_off(\'anchor_time_end_" + i +"\')");
	} else {
		eval("t.form.start_time_" + i +".disabled=false");
		eval("t.form.end_time_" + i +".disabled=false");
		
		eval("turn_on(\'anchor_time_start_" + i +"\')");
		eval("turn_on(\'anchor_time_end_" + i +"\')");
	}
}
function turn_on(whichLayer) {
	if (document.getElementById) {
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		style2.visibility = "visible";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.visibility = "visible";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.visibility = "show";
	}
}
function turn_off(whichLayer) {
	if (document.getElementById) {
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		style2.visibility = "hidden";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.visibility = "hidden";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.visibility = "hide";
	}
}
</script>';
include "include/start.php";
$page_title = Add Event;



function category_tree($category_id) {
	global $table_prefix, $indent, $supercategory, $scategory;
	$q = "SELECT category_id, name from categories where sub_of = ".$category_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		
		while ($row=mysql_fetch_row($query)) {
			$mod = 0;
			
			if (!$supercategory) {
				$q = "select moderate from users_to_categories where category_id = ".$row[0]." and user_id = ".$_SESSION["user_id"];
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
	$q = "SELECT group_id, name from groups where sub_of = ".$group_id." order by name";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		
		while ($row=mysql_fetch_row($query)) {
			$mod = 0;
			
			if (!$supergroup) {
				$q = "select moderate from users_to_groups where group_id = ".$row[0]." and user_id = ".$_SESSION["user_id"];
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
	$q = "SELECT link_id, state, city, company from links where link_id > 1 order by state, city, company";
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


if (!$superview) {
	header("Location: ".$path."login.php?return_to=".$PHP_SELF);
} elseif (!$superpost) {
		include "header.php";
	echo "<p class=\"warning\">Not Authorized to Edit Events in this Category</p>\n";
} else {
	if ($_POST["mode"] == "Add Event") {
		if(get_magic_quotes_gpc()) {
            $title = mysql_real_escape_string(stripslashes($_POST["title"]));
			$link_id = mysql_real_escape_string(stripslashes($_POST["venue"]));
			$contact_id = mysql_real_escape_string(stripslashes($_POST["contact"]));
			$description = mysql_real_escape_string(stripslashes($_POST["description"]));
			$category_id = mysql_real_escape_string(stripslashes($_POST["category"]));
			$group_id = mysql_real_escape_string(stripslashes($_POST["group"]));
			$propose = mysql_real_escape_string(stripslashes($_POST["propose"]));
        } else {
          	$title = mysql_real_escape_string($_POST["title"]);
			$link_id = mysql_real_escape_string($_POST["venue"]);
			$contact_id = mysql_real_escape_string($_POST["contact"]);
			$description = mysql_real_escape_string($_POST["description"]);
			$category_id = mysql_real_escape_string($_POST["category"]);
			$group_id = mysql_real_escape_string($_POST["group"]);
			$propose = mysql_real_escape_string($_POST["propose"]);
        }
	
	
		
		if (!$supergroup) {
			if ($group_id) {
				$q = "select moderate from users_to_groups where group_id = ".$group_id." and user_id = ".$_SESSION["user_id"];
				//echo $q."<br>";
				$gmod = mysql_result(mysql_query($q),0,0);
				if ($gmod < 2) {
					header("Location: ".$path."add_event.php?msg="."Not Authorized to Add or Edit Events in Group"."&size=".$_REQUEST["size"]);
				} else {
					if ($gmod == 2) $propose = "propose";
				}
			} else {
				header("Location: ".$path."add_event.php?msg="."Not Authorized to Add or Edit Events in Group"."&size=".$_REQUEST["size"]);
			}
		}
		if (!$supercategory) {
			$q = "select moderate from users_to_categories where category_id = ".$category_id." and user_id = ".$_SESSION["user_id"];
			
			$mod = mysql_result(mysql_query($q),0,0);
			if ($mod < 2) {
				header("Location: ".$path."add_event.php?msg="."Not Authorized to Edit Events in this Category"."&size=".$_REQUEST["size"]);
			}
		}
		if ($propose == "propose") $status_id = 2;
		else $status_id = 1;
		$sauce = md5(time());
		$q = "INSERT into events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, quick_approve) values ('".$title."', ".$link_id.", ".$contact_id.", '".$description."', ".$category_id.", ".$_SESSION["user_id"].", ".$group_id.", ".$status_id.", '".$sauce."')";
		$query = mysql_query($q);
		$event_id = mysql_insert_id();
		if (!$query) $msg .= "Database Error : ".$q;
		else {
			$j = 0;
			while ($_POST["date_".$j]) {
				if (!$_POST["delete_".$j]) {
					if ($_POST["all_day_".$j] == "all") {
						$_POST["start_time_".$j] = "12:00 am";
						$_POST["end_time_".$j] = "11:59 pm";
					} else if ($_POST["all_day_".$j] == "tba") {
						$_POST["start_time_".$j] = "12:00 am";
						$_POST["end_time_".$j] = "12:00 am";
					}
					if (!preg_match ("/([0-9]{1,2})[\/-]+([0-9]{1,2})[\/-]+([0-9]{4})/i",$_POST["date_".$j],$date)) {
						$msg .= "Bad Date:".$_POST["date_".$j];
					} else {
						if (!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$_POST["start_time_".$j],$start_time)) {
							$msg .= "Bad Start Time:".$_POST["start_time_".$j];
						} else {
							if (($_POST["end_time_".$j])&&(!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$_POST["end_time_".$j],$end_time))) {
								$msg .= "Bad End Time:".$_POST["end_time_".$j];
							} else {
								if (strlen($date[1]) == 1) $date[1] = "0".$date[1];
								if (strlen($date[2]) == 1) $date[2] = "0".$date[2];
								if ((preg_match("/am/i",$start_time[3])) && ($start_time[1] == 12)) $start_time[1] = $start_time[1] - 12;
								if ((preg_match("/am/i",$end_time[3])) && ($end_time[1] == 12)) $end_time[1] = $end_time[1] - 12;
								if ((preg_match("/pm/i",$start_time[3])) && ($start_time[1] < 12)) $start_time[1] = $start_time[1] + 12;
								if ((preg_match("/pm/i",$end_time[3])) && ($end_time[1] < 12)) $end_time[1] = $end_time[1] + 12;							
								if (strlen($start_time[1]) == 1) $start_time[1] = "0".$start_time[1];
								if (strlen($end_time[1]) == 1) $end_time[1] = "0".$end_time[1];
								
								$start_date = $date[3]."-".$date[1]."-".$date[2]." ".$start_time[1].":".$start_time[2];
								if ($_POST["end_time_".$j]) {
									$end_date = $date[3]."-".$date[1]."-".$date[2]." ".$end_time[1].":".$end_time[2];
									if (($end_time[1].$end_time[2]) >= ($start_time[1].$start_time[2])) {
										$q = "INSERT into dates (event_id, date, end_date) values (".$event_id.", '".$start_date."', '".$end_date."')";
									} else {
										$q = "INSERT into dates (event_id, date) values (".$event_id.", '".$start_date."')";
									}
								} else {
									$q = "INSERT into dates (event_id, date) values (".$event_id.", '".$start_date."')";
								}
								$query = mysql_query($q);
								if (!$query) $msg .= "Database Error : ".$q;
							}
						}
					}
				}
				$j++;
			}
			if ($_POST["date_add"]) {
				if ($_POST["all_day_add"] == "all") {
					$_POST["start_time_add"] = "12:00 am";
					$_POST["end_time_add"] = "11:59 pm";
				} else if ($_POST["all_day_add"] == "tba") {
					$_POST["start_time_add"] = "12:00 am";
					$_POST["end_time_add"] = "12:00 am";
				}
				if (!preg_match ("/([0-9]{1,2})[\/-]+([0-9]{1,2})[\/-]+([0-9]{4})/i",$_POST["date_add"],$date)) {
					$msg .= "Bad Date:".$_POST["date_add"];
				} else {
					if (!preg_match ("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$_POST["start_time_add"],$start_time)) {
						$msg .= "Bad Start Time:".$_POST["start_time_add"];
					} else {
						if (($_POST["end_time_add"])&&(!preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$_POST["end_time_add"],$end_time))) {
							$msg .= "Bad End Time:".$_POST["end_time_add"];
						} else {
							if (strlen($date[1]) == 1) $date[1] = "0".$date[1];
							if (strlen($date[2]) == 1) $date[2] = "0".$date[2];
							if ((preg_match("/am/i",$start_time[3])) && ($start_time[1] == 12)) $start_time[1] = $start_time[1] - 12;
							if ((preg_match("/am/i",$end_time[3])) && ($end_time[1] == 12)) $end_time[1] = $end_time[1] - 12;
							if ((preg_match("/pm/i",$start_time[3])) && ($start_time[1] < 12)) $start_time[1] = $start_time[1] + 12;
							if ((preg_match("/pm/i",$end_time[3])) && ($end_time[1] < 12)) $end_time[1] = $end_time[1] + 12;
							if (strlen($start_time[1]) == 1) $start_time[1] = "0".$start_time[1];
							if (strlen($end_time[1]) == 1) $end_time[1] = "0".$end_time[1];
							
							$start_date = $date[3]."-".$date[1]."-".$date[2]." ".$start_time[1].":".$start_time[2];
							
							//loop through recurring events
							$juno = 0;
							$r = $_POST["recurring"];
							if (!$r) $r = 0;
							$int = $_POST["interval"];
							while ($juno <= $r) {
								$next = $juno * $int;
								if ($_POST["end_time_add"]) {
									$end_date = $date[3]."-".$date[1]."-".$date[2]." ".$end_time[1].":".$end_time[2];
									if (($end_time[1].$end_time[2]) >= ($start_time[1].$start_time[2])) {
											$q = "INSERT into dates (event_id, date, end_date) values (".$event_id.", DATE_ADD('".$start_date."', INTERVAL ".$next." DAY), DATE_ADD('".$end_date."', INTERVAL ".$next." DAY))";
										} else {
											$q = "INSERT into dates (event_id, date) values (".$event_id.", DATE_ADD('".$start_date."', INTERVAL ".$next." DAY))";
										}
								} else {
									$q = "INSERT into dates (event_id, date) values (".$event_id.", DATE_ADD('".$start_date."', INTERVAL ".$next." DAY))";
								}
								
								$query = mysql_query($q);
								if (!$query) $msg .= "Database Error : ".$q;
								$juno++;
							}
						}
					}
				}
			}
			if ($_POST["notify"]) {
				include "include/notify.php";
				notify_group($event_id);
			}
			$msg .= "Event Added";
			unset($_POST);
			$javascript .= "\n<script language=\"JavaScript\" type=\"text/javascript\">\n<!--\nopener.location.reload(true);\n// -->\n</script>\n";
		}
		
	} 
		include "header.php";

	$scategory = $_POST["category"] ? $_POST["category"] : $c;
	$sgroup = $_POST["group"] ? $_POST["group"] : $w;
	
?>

<form action="add_event.php" id="event" name="event" method="post">
<?php include "include/hidden_fields.php"; ?>
<input type="hidden" name="size" value="<?php echo $_REQUEST["size"]; ?>">
<table>
	<tr>
		<td><?php echo "Title";?>:</td>
		<td><input type="text" name="title" id="title" value="<?php echo $_POST["title"]; ?>" size="40"></td>
	</tr>
	<tr>
		<td><?php echo "Group";?>:</td>
		<td>
			<select name="group" id="group">
				<?php group_tree(0); ?>
			</select> <select name="propose" id="propose"><option value="post"<?php if ($_POST["propose"] == "post") echo " selected"; ?>><?php echo "Post";?></option><option value="propose"<?php if ($_POST["propose"] == "propose") echo " selected"; ?>><?php echo "Propose";?></option></select>
			<input type="checkbox" name="notify" value="1"<?php if ($_POST["notify"]) echo " checked"; ?>> <?php echo "Notify Subscribers";?>
		</td>
	</tr>
	<tr>
		<td><?php echo "Category";?>:</td>
		<td>
			<select name="category" id="category">
				<?php category_tree(0); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php echo "Venue/Location";?>:</td>
		<td>
			<select name="venue" id="venue" size="1">
				<option value="1"><?php echo "In Main Description";?></option>
				<?php select_place($_POST["venue"]); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php echo "Contact/Sponsor";?>:</td>
		<td>
			<select name="contact" id="contact" size="1">
				<option value="1"><?php echo "None";?></option>
				<?php select_place($_POST["contact"]); ?>
			</select>
		</td>
	</tr>
</table>
<?php echo "Dates";?>:
<table>
	<tr>
		<th><?php echo "Delete?";?></th>
		<th><?php echo "Date";?></th><th><?php echo $lang["all_day"];?> / <?php echo "TBA";?> / <?php echo "Enter Time";?></th><th><?php echo "Start Time";?></th>
		<th><?php echo "End Time";?></th>
		
	</tr>
<?php
$i = 0;
$j = 0;
while ($_REQUEST["date_".$j]) {
	if (!$_REQUEST["delete_".$j]) {
		$disabled = "";
		$hidden = "";
		$checked= "";
		$checked_tba= "";
		$checked_enter= " checked";
		if ($_REQUEST["all_day_".$j] == "all") {
			$_REQUEST["start_time_".$j] = "12:00 am";
			$_REQUEST["end_time_".$j] = "11:59 pm";
			$disabled = " disabled";
			$hidden = " style=\"visibility: hidden;\"";
			$checked= " checked";
			$checked_enter= "";
		} elseif (($_REQUEST["start_time_".$j] == "12:00 am") && ($_REQUEST["end_time_".$j] == "11:59 pm")) {
			$disabled = " disabled";
			$hidden = " style=\"visibility: hidden;\"";
			$checked= " checked";
			$checked_enter= "";
		} elseif ($_REQUEST["all_day_".$j] == "tba") {
			$_POST["start_time_".$j] = "12:00 am";
			$_POST["end_time_".$j] = "12:00 am";
			$disabled = " disabled";
			$hidden = " style=\"visibility: hidden;\"";
			$checked_tba = " checked";
			$checked_enter= "";
		} elseif (($_REQUEST["start_time_".$j] == "12:00 am") && ($_REQUEST["end_time_".$j] == "12:00 am")) {
			$disabled = " disabled";
			$hidden = " style=\"visibility: hidden;\"";
			$checked_tba= " checked";
			$checked_enter= "";
		}
?>
<tr>
		<td><input type="checkbox" name="delete_<?php echo $i; ?>" id="delete_<?php echo $i; ?>" value="yes" /></td>
		<td><input type="text" name="date_<?php echo $i; ?>" id="date_<?php echo $i; ?>" value="<?php echo $_REQUEST["date_".$j]; ?>"size="10"> <a href="#" onclick="cal.select(document.event.date_<?php echo $i; ?>,this.name,'MM/dd/yyyy'); return false;" NAME="anchor_date_<?php echo $i; ?>" ID="anchor_date_<?php echo $i; ?>"><img src="images/calendar.png" border="0" /></a></td>
		<td><input type="radio" onClick="null_out(this,<?php echo $i; ?>);" name="all_day_<?php echo $i; ?>" id="all_day_<?php echo $i; ?>" value="all"<?php echo $checked; ?>/> / <input type="radio" onClick="null_out(this,<?php echo $i; ?>);" name="all_day_<?php echo $i; ?>" id="all_day_<?php echo $i; ?>" value="tba"<?php echo $checked_tba; ?>/> / <input type="radio" onClick="null_out(this,<?php echo $i; ?>);" name="all_day_<?php echo $i; ?>" id="all_day_<?php echo $i; ?>" value=""<?php echo $checked_enter; ?>/></td>
		<td><input type="text" name="start_time_<?php echo $i; ?>" id="start_time_<?php echo $i; ?>" value="<?php echo $_REQUEST["start_time_".$j]; ?>" size="10"<?php echo $disabled; ?>/> <a href="#" onclick="test5popupactivate(document.event.start_time_<?php echo $i; ?>,this.name);return false;" NAME="anchor_time_start_<?php echo $i; ?>" ID="anchor_time_start_<?php echo $i; ?>"<?php echo $hidden; ?>><img src="images/time.png" border="0" /></a></td>

		<td><input type="text" name="end_time_<?php echo $i; ?>" id="end_time_<?php echo $i; ?>" value="<?php echo $_REQUEST["end_time_".$j]; ?>" size="10"<?php echo $disabled; ?>/> <a href="#" onclick="test5popupactivate(document.event.end_time_0,this.name);return false;" NAME="anchor_time_end_<?php echo $i; ?>" ID="anchor_time_end_<?php echo $i; ?>"<?php echo $hidden; ?>><img src="images/time.png" border="0" /></a></td>
		
	</tr>

<?php
		$i++;
	}
	$j++;
}
if ($_POST["date_add"]) {
	$disabled = "";
	$hidden = "";
	$checked= "";
	$checked_tba= "";
	$checked_enter= " checked";
	if ($_POST["all_day_add"]) {
		$_POST["start_time_add"] = "12:00 am";
		$_POST["end_time_add"] = "11:59 pm";
		$disabled = " disabled";
		$hidden = " style=\"visibility: hidden;\"";
		$checked= " checked";
		$checked_enter= "";
	} elseif (($_POST["start_time_add"] == "12:00 am") && ($_POST["end_time_add"] == "11:59 pm")) {
		$disabled = " disabled";
		$hidden = " style=\"visibility: hidden;\"";
		$checked= " checked";
		$checked_enter= "";
	} elseif ($_POST["all_day_".$j] == "tba") {
		$_POST["start_time_".$j] = "12:00 am";
		$_POST["end_time_".$j] = "12:00 am";
		$disabled = " disabled";
		$hidden = " style=\"visibility: hidden;\"";
		$checked_tba = " checked";
		$checked_enter= "";
	} elseif (($_POST["start_time_".$j] == "12:00 am") && ($_POST["end_time_".$j] == "12:00 am")) {
		$disabled = " disabled";
		$hidden = " style=\"visibility: hidden;\"";
		$checked_tba= " checked";
		$checked_enter= "";
	}
	//loop through recurring events
	preg_replace("/([0-9]{1,2})[\/-]+([0-9]{1,2})[\/-]+([0-9]{4})/i",$_POST["date_add"],$date);
	$juno = 0;
	$r = $_POST["recurring"];
	if (!$r) $r = 0;
	$int = $_POST["interval"];
	while ($juno <= $r) {
		$next = $juno * $int;
		$show_date = date( "m/d/Y", mktime( 0, 0, 0, $date[1], $date[2]+$next, $date[3] ) );
?>
<tr>
		<td><input type="checkbox" name="delete_<?php echo $i; ?>" id="delete_<?php echo $i; ?>" value="yes" /></td>
		<td><input type="text" name="date_<?php echo $i; ?>" id="date_<?php echo $i; ?>" value="<?php echo $show_date; ?>"size="10"> <a href="#" onclick="cal.select(document.event.date_<?php echo $i; ?>,this.name,'MM/dd/yyyy'); return false;" NAME="anchor_date_<?php echo $i; ?>" ID="anchor_date_<?php echo $i; ?>"><img src="images/calendar.png" border="0" /></a></td>
		<td><input type="radio" onClick="null_out(this,<?php echo $i; ?>);" name="all_day_<?php echo $i; ?>" id="all_day_<?php echo $i; ?>" value="all"<?php echo $checked; ?>/> / <input type="radio" onClick="null_out(this,<?php echo $i; ?>);" name="all_day_<?php echo $i; ?>" id="all_day_<?php echo $i; ?>" value="tba"<?php echo $checked_tba; ?>/> / <input type="radio" onClick="null_out(this,<?php echo $i; ?>);" name="all_day_<?php echo $i; ?>" id="all_day_<?php echo $i; ?>" value=""<?php echo $checked_enter; ?>/></td>
		<td><input type="text" name="start_time_<?php echo $i; ?>" id="start_time_<?php echo $i; ?>" value="<?php echo $_POST["start_time_add"]; ?>" size="10"<?php echo $disabled; ?>/> <a href="#" onclick="test5popupactivate(document.event.start_time_<?php echo $i; ?>,this.name);return false;" NAME="anchor_time_start_<?php echo $i; ?>" ID="anchor_time_start_<?php echo $i; ?>"<?php echo $hidden; ?>><img src="images/time.png" border="0" /></a></td>

		<td><input type="text" name="end_time_<?php echo $i; ?>" id="end_time_<?php echo $i; ?>" value="<?php echo $_POST["end_time_add"]; ?>" size="10"<?php echo $disabled; ?>/> <a href="#" onclick="test5popupactivate(document.event.end_time_0,this.name);return false;" NAME="anchor_time_end_<?php echo $i; ?>" ID="anchor_time_end_<?php echo $i; ?>"<?php echo $hidden; ?>><img src="images/time.png" border="0" /></a></td>
		
	</tr>
	
	
	
<?php
		$juno++;
		$i++;
	}

}
?>
	
	<tr>
		<td><?php echo "Add";?> =>></td>
		<td>
			<input type="text" name="date_add" id="date_add" value="<?php echo $_REQUEST["next_date"]; ?>" size="10"> <a href="#" onclick="cal.select(document.event.date_add,this.name,'MM/dd/yyyy'); return false;" NAME="anchor_date_add" ID="anchor_date_add"><img src="images/calendar.png" border="add" /></a></td>
		<td><input type="radio" onClick="null_out(this,'add');" name="all_day_add" id="all_day_add" value="all" /> / <input type="radio" onClick="null_out(this,'add');" name="all_day_add" id="all_day_add" value="tba" /> / <input type="radio" onClick="null_out(this,'add');" name="all_day_add" id="all_day_add" value="" checked /></td>
<td><input type="text" name="start_time_add" id="start_time_add" value="<?php echo $_REQUEST["next_start"]; ?>" size="10"> <a href="#" onclick="test5popupactivate(document.event.start_time_add,this.name);return false;" NAME="anchor_time_start_add" ID="anchor_time_start_add""><img src="images/time.png" border="add" /></a>
			
		</td>
		<td><input type="text" name="end_time_add" id="end_time_add" value="<?php echo $_REQUEST["next_end"]; ?>" size="10"> <a href="#" onclick="test5popupactivate(document.event.end_time_add,this.name);return false;" NAME="anchor_time_end_add" ID="anchor_time_end_add""><img src="images/time.png" border="add" /></a>
		</td>
		
	</tr>
	<tr>
		<td colspan="4"><?php echo "Recurring:";?> <input type="text" name="recurring" size="3"> X <select name="interval"><option value="1">1</option><option value="7">7</option></select> <?php echo "Days";?></td>
	</tr>
</table>
<p><input type="submit" name="mode" id="mode" value="<?php echo "Add/Edit Dates";?>"></p>
<p><?php echo "Description";?>:<br />
<?php if ($fck_editor_path) {
	include($fck_editor_path."fckeditor.php") ;
	$oFCKeditor = new FCKeditor('description') ;
	$oFCKeditor->BasePath	= $calendar_url.$fck_editor_path ;
	$oFCKeditor->Value		= $_POST["description"] ;
	$oFCKeditor->Height		= 400;
	$oFCKeditor->ToolbarSet	= $fck_editor_toolbar;
	$oFCKeditor->Create() ;
} else {
	echo "<textarea cols=\"60\" rows=\"10\" name=\"description\" id=\"description\">".$_POST["description"]."</textarea>\n";
	if ($ck_editor_path) echo "<script language=\"JavaScript\"> CKEDITOR.replace( 'description' );</script>\n";
}
?>
</p>
<p><input type="submit" name="mode" id="mode" value="<?php echo "Add Event";?>"></p>
</form>

<div id="timelayer" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;top:0px;z-index: 1;">
<form id="time" name="time">
	<select id="hour" size="18" onChange="testpopup5pick(this.options[this.selectedIndex].value);">
		<option value="12:00 am" class="midnight">Midnight</option>
		<option value="01:00 am" class="evening">01:00 am</option>
		<option value="02:00 am" class="evening">02:00 am</option>
		<option value="03:00 am" class="evening">03:00 am</option>
		<option value="04:00 am" class="evening">04:00 am</option>
		<option value="05:00 am" class="evening">05:00 am</option>
		<option value="06:00 am" class="morning">06:00 am</option>
		<option value="07:00 am" class="morning">07:00 am</option>
		<option value="07:30 am" class="morning">07:30 am</option>
		<option value="08:00 am" class="morning" selected>08:00 am</option>
		<option value="08:30 am" class="morning">08:30 am</option>
		<option value="09:00 am" class="morning">09:00 am</option>
		<option value="09:30 am" class="morning">09:30 am</option>
		<option value="10:00 am" class="morning">10:00 am</option>
		<option value="10:30 am" class="morning">10:30 am</option>
		<option value="11:00 am" class="morning">11:00 am</option>
		<option value="11:30 am" class="morning">11:30 am</option>
		<option value="12:00 pm" class="noon">Noon</option>
		<option value="12:30 pm" class="afternoon">12:30 pm</option>
		<option value="01:00 pm" class="afternoon">01:00 pm</option>
		<option value="01:30 pm" class="afternoon">01:30 pm</option>
		<option value="02:00 pm" class="afternoon">02:00 pm</option>
		<option value="02:30 pm" class="afternoon">02:30 pm</option>
		<option value="03:00 pm" class="afternoon">03:00 pm</option>
		<option value="03:30 pm" class="afternoon">03:30 pm</option>
		<option value="04:00 pm" class="afternoon">04:00 pm</option>
		<option value="04:30 pm" class="afternoon">04:30 pm</option>
		<option value="05:00 pm" class="afternoon">05:00 pm</option>
		<option value="05:30 pm" class="afternoon">05:30 pm</option>
		<option value="06:00 pm" class="evening">06:00 pm</option>
		<option value="06:30 pm" class="evening">06:30 pm</option>
		<option value="07:00 pm" class="evening">07:00 pm</option>
		<option value="07:30 pm" class="evening">07:30 pm</option>
		<option value="08:00 pm" class="evening">08:00 pm</option>
		<option value="08:30 pm" class="evening">08:30 pm</option>
		<option value="09:00 pm" class="evening">09:00 pm</option>
		<option value="09:30 pm" class="evening">09:30 pm</option>
		<option value="10:00 pm" class="evening">10:00 pm</option>
		<option value="10:30 pm" class="evening">10:30 pm</option>
		<option value="11:00 pm" class="evening">11:00 pm</option>
		<option value="11:30 pm" class="evening">11:30 pm</option>
	</select>	
</form>
</div>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<?php
}
include ("include/footer.php");
?>

