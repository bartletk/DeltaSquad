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
if ($_SESSION["user_id"] == 1) echo "<a href=\"login.php\">".$lang["manage_calendar"]."</a>\n";

$query = mysql_query("SELECT add_users, add_categories, view, post, add_groups from ".$table_prefix."users where user_id = ".$_SESSION["user_id"]." limit 1");
$row = mysql_fetch_row($query);
if (($_SESSION["user_id"] == 1) && ($row[3] == 1)) echo " | ";
if ($row[3] == 1) {
	echo "<a href=\"add_event.php?".$common_get."\" onClick=\"x=openPic('add_event.php?size=small','pop','650','600'); x.focus();return false\">".$lang["top_nav_add_event"]."</a> | ";
	
	echo "<a href=\"upload_events.php?".$common_get."\">".$lang["top_nav_csv_upload"]."</a> | ";
}
if ($row[4] == 1) echo "<a href=\"edit_groups.php?".$common_get."\">".$lang["top_nav_groups"]."</a> | ";
if ($row[0] == 1) {
	echo "<a href=\"edit_users.php?".$common_get."\">".$lang["top_nav_users"]."</a> | <a href=\"modules.php?".$common_get."\">".$lang["title_modules"]."</a> | ";
} elseif ($_SESSION["user_id"] != 1) {
	echo "<a href=\"user_profile.php?".$common_get."\">".$lang["top_nav_profile"]."</a> | ";
}
if ($row[1] == 1) echo "<a href=\"edit_categories.php?&".$common_get."\">".$lang["top_nav_categories"]."</a> | <a href=\"edit_links.php?".$common_get."\">".$lang["top_nav_links"]."</a> | ";
if ($row[3] == 1)echo "<a href=\"documentation.php?".$common_get."\" onClick=\"openPic('documentation.php?size=small','pop','600','400'); return false\">".$lang["top_nav_documentation"]."</a>";
if (($_SESSION["user_id"] != 1) && ($row[3] == 1)) echo " | ";
if ($_SESSION["user_id"] != 1) echo "<a href=\"actions.php?mode=logout&".$common_get."\">".$lang["top_nav_log_out"]."</a>\n";

?>
