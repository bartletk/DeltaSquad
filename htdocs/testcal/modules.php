<?php
/*
Supercali Event Calendar

Copyright 2007 Dana C. Hutchins

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
$page_title = $lang["title_modules"];
$id = $_REQUEST["id"];
$dir = "modules"; 

$edit=false;

function showModules() {
	global $dir, $files, $lang, $table_prefix;
	if (is_dir($dir)) {
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {				
				if ($file != '.' && $file != '..' && $file != 'index.html') $files[] = $file;
				
			}
		}
	}

	$q = "select * from ".$table_prefix."modules order by sequence";
	$query = mysql_query($q);
	if (mysql_num_rows($query) > 0) {
		echo "<h3>".$lang["module_installed_modules"]."</h3><form action=\"admin_actions.php\" method=\"post\"><table>\n";
		echo "<tr><th>".$lang["delete?"]."</th><th>".$lang["module_id"]."</th><th>".$lang["module_name"]."</th><th>".$lang["module_link_name"]."</th><th>".$lang["module_script"]."</th><th>".$lang["module_active"]."</th><th>".$lang["sequence"]."</th></th><th>".$lang["link_year"]."</th></th><th>".$lang["link_month"]."</th></th><th>".$lang["link_week"]."</th></th><th>".$lang["link_day"]."</th></tr>\n";
		while ($row = mysql_fetch_row($query)) {
			echo "<tr><td><input type=\"checkbox\" name=\"delete[".$row[0]."]\" value=\"1\"></td><td>".$row[0]."<td><input name=\"name[".$row[0]."]\" type=\"text\" size=\"30\" value=\"".$row[2]."\"></td><td><input name=\"link_name[".$row[0]."]\" type=\"text\" size=\"20\" value=\"".$row[1]."\"></td><td>".$row[5]."</td><td><input type=\"checkbox\" name=\"active[".$row[0]."]\" value=\"1\"";
			if ($row[3] == 1) echo " checked";
			echo "></td><td><input name=\"sequence[".$row[0]."]\" type=\"text\" size=\"2\" value=\"".$row[4]."\"></td><td><input name=\"year[".$row[0]."]\" type=\"text\" size=\"2\" value=\"".$row[6]."\"></td><td><input name=\"month[".$row[0]."]\" type=\"text\" size=\"2\" value=\"".$row[7]."\"></td><td><input name=\"week[".$row[0]."]\" type=\"text\" size=\"2\" value=\"".$row[8]."\"></td><td><input name=\"day[".$row[0]."]\" type=\"text\" size=\"2\" value=\"".$row[9]."\"></td></tr>\n";
			$installed_files[] = $row[5];
		}
		echo "</table>\n";
		echo "<p><input type=\"submit\" name=\"mode\" value=\"".$lang["update modules"]."\"></p></form>\n";
	
	} else {
		echo $lang["no_modules_installed"];
	}
	
	$uninstalled = array_diff($files, $installed_files);
	if ($uninstalled) {
		echo "<h3>".$lang["module_uninstalled_modules"]."</h3>";
		while (list($key, $val) = each($uninstalled)) {
			unset($mod);
			$script = file_get_contents($dir."/".$val);
			$pa = xml_parser_create();
			xml_parse_into_struct($pa, $script, $vals, $index);
			xml_parser_free($pa);
			while (list($k, $v) = each($index)) {
				$mod[$k] = $vals[$index[$k][0]][value];
			}
			if ($mod["NAME"]) {
				echo "<p style=\"clear:left;\">\n";
				if ($mod["IMAGE"]) echo "<img src=\"".$mod["IMAGE"]." style=\"float: left; margin: 10px;\">\n";
				echo "<strong>".$mod["NAME"]."</strong> by ".$mod["AUTHOR"]."<br />Version: ".$mod["VERSION"]." Homepage: <a href=\"".$mod["URL"]."\" target=\"_blank\">".$mod["URL"]."</a></p>\n";
				echo "<p>".$mod["DESCRIPTION"]."</p>\n<p><a href=\"";
				if ($mod["INSTALL_SCRIPT"]) {
					echo $mod["INSTALL_SCRIPT"];
				} else {
					echo "admin_actions.php";
				}
				echo "?mode=install_module&dir=".$dir."&file=".$val."\">".$lang["add"]." ".$mod["NAME"]."</a></p>\n";
			}
		}
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
	case "edit_category";
		editCategory($id);
		break;
	
	case "delete_category";
		deleteCategory($id);
		break;
	
	default; 
		showModules();
		break;
	}


}
include "includes/footer.php";
?>