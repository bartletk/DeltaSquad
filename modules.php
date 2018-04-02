<?php
include "include/start.php";
$page_title = "Modules";
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

	$q = "select * from modules order by sequence";
	$query = mysql_query($q);
	if (mysql_num_rows($query) > 0) {
		echo "<h3>"."Installed Modules"."</h3><form action=\"admin_actions.php\" method=\"post\"><table>\n";
		echo "<tr><th>"."Delete?"."</th><th>"."ID"."</th><th>"."Module Heading"."</th><th>"."Link Name"."</th><th>"."File Name"."</th><th>"."Active"."</th><th>"."Sequence"."</th></th><th>"."Year Link"."</th></th><th>"."Month Link"."</th></th><th>"."Week Link"."</th></th><th>"."Day Link"."</th></tr>\n";
		while ($row = mysql_fetch_row($query)) {
			echo "<tr><td><input type=\"checkbox\" name=\"delete[".$row[0]."]\" value=\"1\"></td><td>".$row[0]."<td><input name=\"name[".$row[0]."]\" type=\"text\" size=\"30\" value=\"".$row[2]."\"></td><td><input name=\"link_name[".$row[0]."]\" type=\"text\" size=\"20\" value=\"".$row[1]."\"></td><td>".$row[5]."</td><td><input type=\"checkbox\" name=\"active[".$row[0]."]\" value=\"1\"";
			if ($row[3] == 1) echo " checked";
			echo "></td><td><input name=\"sequence[".$row[0]."]\" type=\"text\" size=\"2\" value=\"".$row[4]."\"></td><td><input name=\"year[".$row[0]."]\" type=\"text\" size=\"2\" value=\"".$row[6]."\"></td><td><input name=\"month[".$row[0]."]\" type=\"text\" size=\"2\" value=\"".$row[7]."\"></td><td><input name=\"week[".$row[0]."]\" type=\"text\" size=\"2\" value=\"".$row[8]."\"></td><td><input name=\"day[".$row[0]."]\" type=\"text\" size=\"2\" value=\"".$row[9]."\"></td></tr>\n";
			$installed_files[] = $row[5];
		}
		echo "</table>\n";
		echo "<p><input type=\"submit\" name=\"mode\" value=\""."Update Modules"."\"></p></form>\n";
	
	} else {
		echo "No modules installed";
	}
	
	$uninstalled = array_diff($files, $installed_files);
	if ($uninstalled) {
		echo "<h3>"."Uninstalled Modules"."</h3>";
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
				echo "?mode=install_module&dir=".$dir."&file=".$val."\">"."Add"." ".$mod["NAME"]."</a></p>\n";
			}
		}
	}
}





if (!$_SESSION["user_id"]) {
	mysql_close($link);
	header("Location: ".$path."login.php?return_to=".$PHP_SELF);
} else {
		include "top_header.php";
	$query = mysql_query("SELECT add_users from users where user_id = ".$_SESSION["user_id"]." limit 1");
	$row = mysql_fetch_row($query);
	if ($row[0] == 1) {
		$edit = true;
	} else {
		echo "<p class=\"warning\">"."You are not authorized to edit users or modules."."</p>\n";
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

?>