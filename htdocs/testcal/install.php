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

if (file_exists("config.php")) exit("Configuration file already exists");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title>SuperCali Event Calendar - Installation</title>
<link rel="stylesheet" type="text/css" href="css/supercali.css">
</head>

<body>
<div class="top">

<h4>SuperCali Event Calendar</h4>
<h1>Calendar Configuration and Installation<h1>
</div>
<div class="content">
<?php
if ($_POST["mode"] == "Install") {
	// check for missing fields
	if (!$_POST["server"]) $error_msg[] = "server name";
	if (!$_POST["database"]) $error_msg[] = "database name";
	if (!$_POST["username"]) $error_msg[] = "database username";
	if (!$_POST["password"]) $error_msg[] = "database password";
	if (!$_POST["title"]) $error_msg[] = "calendar title";
	if (!$_POST["admin_username"]) $error_msg[] = "administrator username";
	if (!$_POST["admin_password"]) $error_msg[] = "administrator password";
	if ($error_msg) {
		echo "<h3 class=\"warning\">Error: Missing Fields</h3>\n";
		echo "<p>The";
		for(  $i  =  0;  $error_msg[$i];  $i++  )  {
			if ($error_msg[$i+1]) {
				if ($error_msg[$i+2]) {
					echo "<span class=\"warning\">".$error_msg[$i]."</span>, ";
				} else {
					echo "<span class=\"warning\">".$error_msg[$i]."</span> and ";
				}
			} else {
			echo "<span class=\"warning\">".$error_msg[$i]."</span>";
			}
		}
		echo "fields are missing. Please go <a href=\"javascript:history.back()\">back</a> and fill in this information.</p>\n";
	} else {
		// Connect to Database
		$link = mysql_connect ($_POST["server"], $_POST["username"], $_POST["password"]);
		if (!$link) {
			echo "<h3 class=\"warning\">Error: Unable to Connect</h3>\n";
			echo "<p>Unable to connect the the database server, please go <a href=\"javascript:history.back()\">back</a> and check this information</p>.\n";
		} else {
			$connect = mysql_select_db($_POST["database"],$link);
			if (!$connect) {
			echo "<h3 class=\"warning\">Error: Unable to Connect</h3>\n";
			echo "<p>Unable to connect the the database, ".$_POST["database"].", please go <a href=\"javascript:history.back()\">back</a> and check this information</p>.\n";
			} else {
				// check for existing installation
				$query = mysql_query("SELECT count(*) FROM ".$_POST["table_prefix"]."users");
				if ($query) {
					echo "<h3 class=\"warning\">Error: Existing Calendar</h3>\n";
					echo "<p>There is already a calendar using the prefix \"".$_POST["table_prefix"]."\", please go <a href=\"javascript:history.back()\">back</a> and try another prefix.</p>\n";
				} else {
					// Create the tables
					
					$users = mysql_query("CREATE TABLE ".$_POST["table_prefix"]."users (
  						user_id int(6) unsigned NOT NULL auto_increment,
						`password` varchar(32) NOT NULL default '',
						temp_password varchar(32) default NULL,
						email varchar(80) NOT NULL default '',
						view int(1) unsigned NOT NULL default '0',
						post int(1) unsigned NOT NULL default '0',
						add_users int(1) unsigned NOT NULL default '0',
						add_categories int(1) unsigned NOT NULL default '0',
						add_groups int(1) unsigned NOT NULL default '0',
						PRIMARY KEY  (user_id)
						)");
					$categories = mysql_query("CREATE TABLE ".$_POST["table_prefix"]."categories (
						  category_id int(6) unsigned NOT NULL auto_increment,
						  name varchar(40) NOT NULL default '',
						  sub_of int(6) unsigned NOT NULL default '1',
						  sequence int(2) unsigned NOT NULL default '1',
						  restricted int(1) unsigned NOT NULL default '0',
						  description text,
						  color varchar(30) default NULL,
  						  background varchar(255) NULL default '',
						  
						  PRIMARY KEY  (category_id)
						)");
					$users_to_categories = mysql_query("CREATE TABLE ".$_POST["table_prefix"]."users_to_categories (
						  user_id int(6) unsigned NOT NULL default '0',
						  category_id int(6) unsigned NOT NULL default '0',
						  moderate int(1) NOT NULL default '0'
						)");
					$groups = mysql_query("CREATE TABLE ".$_POST["table_prefix"]."groups (
						  group_id int(6) unsigned NOT NULL auto_increment,
						  name varchar(40) NOT NULL default '',
						  sub_of int(6) unsigned NOT NULL default '1',
						  sequence int(2) unsigned NOT NULL default '1',
						  
						  PRIMARY KEY  (group_id)
						)");
					$users_to_groups = mysql_query("CREATE TABLE ".$_POST["table_prefix"]."users_to_groups (
						  user_id int(6) unsigned NOT NULL default '0',
						  group_id int(6) unsigned NOT NULL default '0',
						  moderate int(1) NOT NULL default '0',
						  subscribe int(1) NOT NULL default '0'
						)");
					$events = mysql_query("CREATE TABLE ".$_POST["table_prefix"]."events (
						  event_id int(8) unsigned NOT NULL auto_increment,
						  title varchar(255) default NULL,
						  venue_id int(6) unsigned NOT NULL default '1',
						  contact_id int(6) unsigned NOT NULL default '1',
						  description text,
						  category_id int(6) unsigned NOT NULL default '1',
						  user_id int(6) unsigned default NULL,
						  group_id int(6) unsigned NOT NULL default '1',
						  status_id int(1) unsigned NOT NULL default '1',
						  stamp timestamp NULL default CURRENT_TIMESTAMP(),
						  quick_approve varchar(32),
						  PRIMARY KEY  (event_id)
						)");
					$venues = mysql_query("CREATE TABLE ".$_POST["table_prefix"]."links (
						  link_id int(6) unsigned NOT NULL auto_increment,
						  company varchar(50) default NULL,
						  address1 varchar(40) default NULL,
						  address2 varchar(40) default NULL,
						  city varchar(30) default NULL,
						  state char(2) default NULL,
						  zip varchar(10) default NULL,
						  phone varchar(15) default NULL,
						  fax varchar(15) default NULL,
						  email varchar(120) default NULL,
						  url varchar(120) default NULL,
						  contact varchar(50) default NULL,
						  description text,
						  PRIMARY KEY  (link_id)
						)");
					$no_venue = mysql_query("INSERT INTO ".$_POST["table_prefix"]."links VALUES (1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)");
					$dates = mysql_query("CREATE TABLE ".$_POST["table_prefix"]."dates (
						  event_id int(8) unsigned default '0',
						  `date` datetime default NULL,
						  end_date datetime default NULL						  
						)");
					$modules = mysql_query("CREATE TABLE ".$_POST["table_prefix"]."modules (
						  module_id int(6) unsigned NOT NULL auto_increment,
						  link_name varchar(20) NOT NULL default '',
						  name varchar(60) NOT NULL default '',
						  active int(1) unsigned NOT NULL default '0',
						  sequence int(2) unsigned NOT NULL default '1',
						  script varchar(60) default NULL,
						  year int(2) unsigned NULL,
						  month int(2) unsigned NULL,
						  week int(2) unsigned NULL,
						  day int(2) unsigned NULL,
						  PRIMARY KEY  (module_id)
						)");
					
					if (!$users || !$categories || !$users_to_categories || !$groups || !$users_to_groups || !$events || !$venues || !$dates || !$modules) {
						echo "<h3 class=\"warning\">Error: Problem Writing Tables</h3>\n";
						echo "<p>Can not create tables, please check your database permissions and go <a href=\"javascript:history.back()\">back</a> to try again.</p>\n";
						
					} else {
						// insert beginning data
						$top_category = mysql_query("INSERT into ".$_POST["table_prefix"]."categories (category_id, name, sub_of, sequence, restricted, description) values (1,'".$_POST["title"]."',0,1,0,'Top Level Category')");
						$top_group = mysql_query("INSERT into ".$_POST["table_prefix"]."groups (group_id, name, sub_of, sequence) values (1,'".$_POST["title"]."',0,1)");
						$crypt_pass=md5($_POST["admin_password"]);
						$public_user = mysql_query("INSERT into ".$_POST["table_prefix"]."users (user_id, email, view) values (1,'Public Access',1)");
						$public_category = mysql_query("INSERT into ".$_POST["table_prefix"]."users_to_categories (user_id, category_id, moderate) values (1,1,1)");
						$public_group = mysql_query("INSERT into ".$_POST["table_prefix"]."users_to_groups (user_id, group_id, moderate, subscribe) values (1,1,1,0)");
						$admin_user = mysql_query("INSERT into ".$_POST["table_prefix"]."users (user_id, password, email, add_users, add_categories, view, post, add_groups) values (NULL,'".$crypt_pass."','".$_POST["admin_username"]."',1,1,1,1,1)");
						$year_module = mysql_query("INSERT INTO ".$_POST["table_prefix"]."modules(module_id, link_name, name, active, sequence, script, year, month, week, day) VALUES (1, 'Year', 'Year', 1, 1, 'year.php',0,2,3,4)");
						$month_module = mysql_query("INSERT INTO ".$_POST["table_prefix"]."modules(module_id, link_name, name, active, sequence, script, year, month, week, day) VALUES (2, 'Month', 'Month', 1, 2, 'grid.php',0,2,3,4)");
						$week_module = mysql_query("INSERT INTO ".$_POST["table_prefix"]."modules(module_id, link_name, name, active, sequence, script, year, month, week, day) VALUES (3, 'Week', 'Week', 1, 3, 'week.php',0,2,3,4)");
						$day_module = mysql_query("INSERT INTO ".$_POST["table_prefix"]."modules(module_id, link_name, name, active, sequence, script, year, month, week, day) VALUES (4, 'Day', 'Day', 1, 4, 'day.php',0,2,3,4)");
						if (!$top_category || !$admin_user || !$top_group) {
							echo "<h3 class=\"warning\">Error: Problem Writing to Tables</h3>\n";
							echo "<p>Created tables, but can not write to tables.  Please check your database permissions.  You will need to clean out the tables in the database and try again.</p>\n";				
						} else {
							// Success!
							echo "<h3>Installation Successful</h3>\n";
							echo "<p>The initial database creation has been successful.</p>\n<p>To finalize the installation, please <strong>copy and paste the text in the text box below into a file called config.php and then upload this file to the root directory of your SuperCali installation</strong>.</p>\n<p>You can later edit config.php if you reconfigure your setup (e.g. change the database password, etc.).</p>\n";
							echo "<p><strong>It is a good idea to delete install.php!</strong></p>\n";
							echo "<p>SuperCali currently utilizes Matt Kruse's Javascript Toolbox for pop-up color, calendar and time selectors.  Unfortunately, I did not carefully read the fine print governing distribution of this otherwise free to use library, so you will have to download these files directly from his site.</p>  
<p>Right click the following links and select &quot;Save Target As&quot; or &quot;Save Link As&quot; to save the files and then upload them to the /js directory of your SuperCali installation.</p>";
							echo "<ul><li><p><a href=\"http://www.mattkruse.com/javascript/calendarpopup/combined-compact/CalendarPopup.js\">CalendarPopup.js</a> <sup style=\"font-size: 8pt;\">(right click, &quot;Save Target As&quot; or &quot;Save Link As&quot;)</sup></p></li><li><p><a href=\"http://www.mattkruse.com/javascript/colorpicker/compact/ColorPicker2.js\">ColorPicker2.js</a> <sup style=\"font-size: 8pt;\">(right click, &quot;Save Target As&quot; or &quot;Save Link As&quot;)</sup></p></li></ul>\n";
							//Generate config.php info
							$conf = "<?php\n\n";
							$conf .= '$h = "'.$_POST["server"].'";'."\n";
							$conf .= '$d = "'.$_POST["database"].'";'."\n";
							$conf .= '$u = "'.$_POST["username"].'";'."\n";
							$conf .= '$p = "'.$_POST["password"].'";'."\n\n";
							$conf .= '$calendar_title = "'.$_POST["title"].'";'."\n\n";
							$conf .= '$calendar_email = "'.$_POST["admin_username"].'";'."\n\n";
							$conf .= '$calendar_url = "'.$_POST["url"].'";'."\n\n";
							$conf .= '$table_prefix = "'.$_POST["table_prefix"].'";'."\n\n";
							$conf .= '$default_module = 2;'."\n\n";
							$conf .= '// display sub-category events along with events in selected category'."\n";
							$conf .= '$include_child_categories = true;'."\n\n";
							$conf .= '// display events in parent category along with events in selected category'."\n";
							$conf .= '$include_parent_categories = true;'."\n\n";
							$conf .= '// How to display the titles on the header of the calendar'."\n";
							$conf .= '$week_titles[] = "Sunday";'."\n";
							$conf .= '$week_titles[] = "Monday";'."\n";
							$conf .= '$week_titles[] = "Tuesday";'."\n";
							$conf .= '$week_titles[] = "Wednesday";'."\n";
							$conf .= '$week_titles[] = "Thursday";'."\n";
							$conf .= '$week_titles[] = "Friday";'."\n";
							$conf .= '$week_titles[] = "Saturday";'."\n\n";
							$conf .= '//used with the quarter view'."\n";
							$conf .= '$week_titles_s[] = "Sun";'."\n";
							$conf .= '$week_titles_s[] = "Mon";'."\n";
							$conf .= '$week_titles_s[] = "Tue";'."\n";
							$conf .= '$week_titles_s[] = "Wed";'."\n";
							$conf .= '$week_titles_s[] = "Thu";'."\n";
							$conf .= '$week_titles_s[] = "Fri";'."\n";
							$conf .= '$week_titles_s[] = "Sat";'."\n\n";
							$conf .= '//used with the year view'."\n";
							$conf .= '$week_titles_ss[] = "S";'."\n";
							$conf .= '$week_titles_ss[] = "M";'."\n";
							$conf .= '$week_titles_ss[] = "T";'."\n";
							$conf .= '$week_titles_ss[] = "W";'."\n";
							$conf .= '$week_titles_ss[] = "T";'."\n";
							$conf .= '$week_titles_ss[] = "F";'."\n";
							$conf .= '$week_titles_ss[] = "S";'."\n\n";
							$conf .= '// FCK Editor can be used for HTML of event text (Depreciated)'."\n";
							$conf .= '$fck_editor_path = "";'."\n\n";
							$conf .= '$fck_editor_toolbar = "Basic"; // Basic or Default'."\n\n";
							$conf .= '// CK Editor now can be used for HTML'."\n\n";
							$conf .= '$ck_editor_path = "";'."\n\n";
							$conf .= '// The default start category for the event calendar'."\n";
							$conf .= '$start_category_id = 1;'."\n\n";
							$conf .= '// Language File'."\n";
							$conf .= '$language = "lang/en_us.php";'."\n\n";
							$conf .= '// Day/week view start hour'."\n";
							$conf .= '$day_week_start_hour = 8;'."\n\n";
							$conf .= "?>";
							
							// show config.php
							echo "<form>\n<textarea cols=\"60\" rows=\"10\">".htmlspecialchars($conf)."</textarea>\n</form>\n";
							echo "<p><a href=\"".$_POST["url"]."\">Click here after config.php is installed</a></p>\n";
						}
					}
				}
			}
			mysql_close($link);
		}	
	}
} else {
	$path = preg_replace("/install.php/","",$_SERVER['PHP_SELF']);
?>

<p>Please enter the following information to install SuperCali Event Calendar:</p>
<form action= "<?php echo $PHP_SELF; ?>" method="POST">
<h3>MySQL Database Information</h3>
<p>Database Server: <input type="text" name="server"></p>
<p>Database Name: <input type="text" name="database"></p>
<p>Database Username: <input type="text" name="username"></p>
<p>Database Password: <input type="text" name="password"></p>
<p>Table Prefix<sup>*</sup>: <input type="text" name="table_prefix"><br />
<span style="font-size: .8em;">* Optional.  This text prepends the table names, used for multiple installations</p>
<h3>Calendar Information</h3>
<p>Calendar Title: <input type="text" name="title"></p>

<p>Calendar URL Path(make sure you have a trailing forward slash): <input type="text" name="url" size="30" value = "http://<?php echo $_SERVER['SERVER_NAME'].$path; ?>"></p>
<h3>Administrator Information</h3>
<p>Administrator E-mail/Username: <input type="text" name="admin_username"></p>
<p>Administrator Password: <input type="text" name="admin_password"></p>

<input type="submit" name="mode" value="Install">
</form>
<?php
}
?>
</div>
<div class="bottom">
<p><a href="http://supercali.inforest.com/" target="_blank">SuperCali Event Calendar</a></p>
</div>
</body>
</html>





