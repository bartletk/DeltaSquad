<?php
function notify_group($event_id) {
	global $lang, $calendar_title, $calendar_url;
$q = "SELECT *, DATE_FORMAT(stamp, '%M %e, %Y - %l:%i %p') as stamp_date from events where event_id =".$event_id;
//echo $q;
$query = mysql_query($q);
if (mysql_num_rows($query) < 1) {
	echo "<p class=\"warning\">"."Event Not Found"."</p>\n";
} else {
	$row = mysql_fetch_array($query);
	if (!$query) echo "<p class=\"warning\">Database Error : ".$q."</p>\n";
	$page_title = strip_tags($row["title"]);
	$category_id = $row["category_id"];
	$venue_id = $row["venue_id"];
	$contact_id = $row["contact_id"];
	$description = $row["description"];
	$quick_approve = $row["quick_approve"];
	$group_id = $row["group_id"];
	$q = "SELECT DATE_FORMAT(date, '%W, %M %e, %Y'), DATE_FORMAT(date,' - %l:%i %p'),  DATE_FORMAT(end_date, ' - %l:%i %p'), DATE_FORMAT(date,'%Y'), DATE_FORMAT(date,'%c'), DATE_FORMAT(date,'%e') from dates where event_id =".$event_id." order by date";
	$squery = mysql_query($q);
	if (!$squery) echo "<p class=\"warning\">Database Error : ".$q."</p>\n";
	else {
		while ($srow = mysql_fetch_row($squery)) {
			$ur = $calendar_url."index.php?c=".$category_id."&w=".$group_id."&y=".$srow[3]."&m=".$srow[4]."&a=".$srow[5];
		
			if (($srow[1] == " - 12:00 AM") && ($srow[2] == " - 11:59 PM")) $nicedate[] = $srow[0]." - ".$lang["all_day"]."\n".$ur;
			elseif (($srow[1] == " - 12:00 AM") && ($srow[2] == " - 12:00 AM")) $nicedate[] = $srow[0]." - "."TBA"."\n".$ur;
			elseif ($srow[2]) $nicedate[] = $srow[0].$srow[1].$srow[2]."\n".$ur;
			else $nicedate[] = $srow[0].$srow[1]."\n".$ur;
			
		}
	}
	
}

$grou = mysql_result(mysql_query("select name from groups where group_id = ".$row["group_id"]),0,0);
$cate = mysql_result(mysql_query("select name from categories where category_id = ".$row["category_id"]),0,0);
$use = mysql_result(mysql_query("select email from users where user_id = ".$row["user_id"]),0,0);
$status = $lang["status"][$row["status_id"]];
$message = "This is an automated e-mail notifying you of changes to the calendar\n\n";
$message .= $use." has ".$status.": ".$page_title." on ".$row["stamp_date"]."\n\n";
$message .= "Title".": ".$page_title."\n";
$message .= "Group".": ".$grou."\n";
$message .= "Category".": ".$cate."\n";
if ($venue_id != 1) {
	$q = "select url, company, description, address1, address2, city, state, zip, phone, fax  FROM links where link_id = ".$row["venue_id"];
	$lq = mysql_query($q);
	
	$message .= "Venue/Location".": ";
	$li = mysql_fetch_row($lq);
	$message.= $li[1];
	if ($li[3]) $message.= ", ".$li[3];
	if ($li[4]) $message.= ", ".$li[4];
	if ($li[5]) $message.= ", ".$li[5].", ".$li[6]."  ".$li[7];
	if ($li[8]) $message.= ", "."Phone".": ".$li[8];
	if ($li[9]) $message.= ", "."Fax".": ".$li[9];
	$message.= "\n";
} 
if ($contact_id != 1) {
	$q = "select url, company, description, address1, address2, city, state, zip, phone, fax  FROM links where link_id = ".$row["contact_id"];
	$lq = mysql_query($q);
	
	$message .= "Contact/Sponsor".": ";
	$li = mysql_fetch_row($lq);
	$message.= $li[1];
	if ($li[3]) $message.= ", ".$li[3];
	if ($li[4]) $message.= ", ".$li[4];
	if ($li[5]) $message.= ", ".$li[5].", ".$li[6]."  ".$li[7];
	if ($li[8]) $message.= ", "."Phone".": ".$li[8];
	if ($li[9]) $message.= ", "."Fax".": ".$li[9];
	$message.= "\n";
}
if ($nicedate[1]) {
	$message.= "Dates".":\n";
	while (list($k,$v) = each($nicedate)) {
		$message.= $v."\n\n";
	}
	$message.= "\n\n";
} elseif ($nicedate[0]) {
	$message.= "Date".": ".$nicedate[0]."\n\n";
}

$message.= "Description:\n".$description."\n\n";
if ($row["status_id"] == 2) $nextmessage .= "\n"."To approve this event without changes, visit: "."\n".$calendar_url."actions.php?mode=q&qa=".$quick_approve."\n\n";
$message .= $calendar_title." (".$calendar_url.")\n";
$q = "select users.email, users_to_groups.moderate, users.add_groups from users, users_to_groups where users_to_groups.group_id = ".$row["group_id"]." and users_to_groups.user_id = users.user_id and users_to_groups.subscribe = 1";
$gq = mysql_query($q);
while ($grow = mysql_fetch_row($gq)) {
	if (($grow[1] == 3)|| ($grow[2] == 1)) $sendmessage = $message.$nextmessage;
	else $sendmessage = $message;
	mail($grow[0],"Event"." ".$status.": ".$page_title, $sendmessage, "From: \"SuperCali\" <".$use.">");
	
}
}
?>