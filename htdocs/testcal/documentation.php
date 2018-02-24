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

include "includes/start.php";
$page_title = "Documentation";
if (!$_SESSION["user_id"]) {
	mysql_close($link);
	header("Location: ".$path."login.php?return_to=".$PHP_SELF);
} else {
	include "includes/header.php";
?>


<h3>Installation</h3>
<ul>
<li><p>Create the MySQL database to store the SuperCali data.  Contact your hosting provider if they do not provide a tool for you to do this directly.</p></li>
<li>Place the compressed download file in the Web accessible directory you wish to install SuperCali and uncompress it.  If installing on a Linux or Unix server, the command is:<div class="code">unzip supercali-1.0.8.zip</div></p>
</li>
<li><p>Point your Web browser to the directory of the SuperCali installation, it should redirect you to &quot;install.php,&quot; the Installation page.</p></li>
<li><p>Enter your database information, e-mail address and password into the Installation page and click &quot;Install.&quot;  The script will generate the necessary tables in the database and display the code for your configuration file.  Cut and paste this code into a new file and save it as &quot;config.php.&quot;  Upload &quot;config.php&quot; to the main directory of your SuperCali installation.</p></li>
<li><p>Delete install.php</p></li>
<li><p>Place the Javascript files you downloaded separately; CalendarPopup.js and ColorPicker2.js in the /js directory.</p></li>
</ul>


<h3>Configuration</h3>

<p>The following are explanations to the variables found in the configuration file, &quot;config.php&quot;</p> 
<ul>
<li><p><strong>$h</strong>,<strong>$d</strong>,<strong>$u</strong> and <strong>$p</strong> correspond to the host name, database name, user name and password of the database.</p></li>
<li><p><strong>$calendar_title</strong> is the title of calendar that is displayed.</p></li>
<li><p><strong>$calendar_email</strong> is the e-mail of the calendar administrator.</p></li>
<li><p><strong>$calendar_url</strong> is the url address of the calendar, used for notification functions.</p></li>
<li><p><strong>$table_prefix</strong> is the text amended to the front of the default table names to differentiate installations of SuperCali on the same database.</p></li>
<li><p><strong>$default_module</strong> is the id of the default calendar view to be displayed.</p></li>
<li><p><strong>$include_child_categories</strong> indicates whether events listed under sub-categories of the current category should be displayed.  Set to either &quot;true&quot; or &quot;false&quot;.</p></li>
<li><p><strong>$include_parent_categories</strong> indicates whether events listed in categories in which  the current category is nested should be displayed.  Set to either &quot;true&quot; or &quot;false&quot;.</p></li>
<li><p><strong>$week_titles</strong>, <strong>$week_titles_s</strong>, <strong>$week_titles_ss</strong> are the arrays containing the names of each day of the week to be displayed, starting with Sunday.</p></li>
<li><p><strong>$fck_editor_path</strong> specifies the url path to your installation of the HTML editor, &quot;FCKeditor.&quot;  For information on integrating FCKeditor, see below.</p></li>
<li><p><strong>$fck_editor_toolbar</strong> indicates which FCKeditor toolbar configuration to use.</p></li>
<li><p><strong>$start_category_id</strong> specifies the default category shown in SuperCali.</p></li>
<li><p><strong>$language</strong> specifies the path to the language file.</p></li>
<li><p><strong>$day_week_start_hour</strong> specifies the first full time block on the day and week views.</p></li>
</ul>
<h3>Basic CKEditor Integration</h3>
<ul>
<li><p>Place the compressed CKEditor download file in your main SuperCali directory and uncompress it.</p></li>
<li><p>Edit the SuperCali configuration file, &quot;config.php,&quot; and change the value of $ck_editor_path to the url path of newly created CKeditor directory.  For example, if your calendar is found at: &quot;http://www.example.com/calendar/&quot; you will set <span class="code">$ck_editor_path = &quot;ckeditor/&quot;;</span>.</p></li>
<li>For more HTML formating tools, $fck_editor_path can be set to &quot;Default&quot;  Please see the CKEditor documentation for more information on using CKeditor.</li>

</ul>

<h3>Managing the Calendar</h3>

<p>In order to set up categories for the calendar and add events, you must click the &quot;Log In&quot; link at the top right corner of the screen.  This will bring you to the log in screen.  Enter the e-mail address and password you used during Installation.  If you forget your password, click on the link to have a new password generated and e-mailed to you.</p>

<h4>Categories</h4>

<p>A good place to start in managing your calendar is to create various categories for your events.  While not required, adding categories might be desirable if you have a whole lot of events or want users to be able the filter the calendar to only show certain types of events.  Be sure to take a look at the configuration variables; <span class="code">$include_child_categories</span> and <span class="code">$include_parent_categories</span> to determine how nested categories are to be displayed.</p>

<p>The initial installation has only one category which is named after the title of the calendar.  All new categories must be subcategories of this default, parent category.  To create a new category, click on the &quot;add new category&quot; link.  This brings us a short form for you to enter the details of your new category.</p>

<p>In addition to entering a category name, you can select which category your new category is going to be a subcategory.  Again, all additional categories need to be a subcategory of the default, parent category, but there are no limits to how many levels of subcategories can be created.  Its also possible to rearrange the nesting of your categories in the future without loosing any event data.  The order in which categories of the same generation are displayed is determined by the Sequence field.</p>

<p>Optional fields are the Text Color and Background fields.  You can pick the colors that will determine the foreground and background of those events listed in the category.  Currently, the Description field is not used but is likely to be utilized in future display modules.</p>

<h4>Groups</h4>

<p>Groups are separate calendars.  They are nested similarly to categories.  However, unlike categores, there is no way to show events of different groups within the same page.  This feature was put in place so that users could have both private and publicly accessible calendars within the same installation, or provide separate calendars for different departments or individuals within the same organization.</p>


<h4>Links</h4>

<p>Event calendars typically include venue and contact information for the events they feature, and these are often repeated from event to event.  Thus, SuperCali stores repeated location and contact information in the database, referenced under the tab, &quot;Links.&quot;  Here you can add commonly used venue and contact information to be showed with your events, which can be included in an event using drop down, select menus featured in the Add Event and Edit Event screens.</p>

<p>Currently, the link e-mail address is not displayed in the event description.  Also, the Web site address must be the full url address, such as &quot;http://www.example.com/&quot;, not just &quot;www.example.com&quot;.</p>

<h4>Add Event / Edit Event</h4>

<p>Clicking the Add Event or Edit Event link will bring up a pop-up window where you can enter event information, such as the Title, Group and Category.  Venue and Sponsor fields are drop-down, select fields where you can add previously entered contact information under the Links tab, or alternately leave these blank and enter the information in your main description.</p>
<p>When posting to a Group, the user can either propose or post events to a calendar, and indicate whether to notify other subscribed users of the addition or change.  This will then send an e-mail to those users informing them of the event.  Other users, depending on their permission, can subsequently approve or change the event.</p>
<p>Below this general event information is a four column table for scheduling dates for the event.  The first column will include a checkbox for any existing events which, when checked, will delete the date when the &quot;Add/Edit Dates&quot; button is clicked.  The next column indicates the dates of the event and can be added or edited directly or by clicking the calendar icon to bring up a pop-up calendar.  The next column indicates whether it is an all day event, To Be Announced (TBA) or has time entered to the right.  The final two columns indicate the start and end times for the event, which also have pop-up select menus to assist with data entry.  The end time field is optional.</p>

<p>When starting with a new event, only one row is present in the dates table.  This is the row used to Add a new date and time to the event by clicking the &quot;Add/Edit Dates&quot; button.  Once added, the Add Event screen will refresh and show the added event in the row directly above the Add Event row.  Additional dates and times can be added, and any existing dates and times edited, until all occurrences of the event have been entered.</p>

<p>The final field is the description, which can be used for any additional information and details for your event.  Once all the dates have been entered and you are finished with the event, click on the &quot;Add Event&quot; or &quot;Update Event&quot; button at the bottom of the screen to save your event.  The main calendar screen will automatically refresh to show your changes.</p>

<h4>Delete Event</h4>

<p>Deleting an event will delete all instances of the event, not just a specific date in question.  If only one date is to be deleted then click the &quot;Edit Event&quot; link next to it and delete the specific date using the Edit Event screen.</p>
<h4>CSV Upload Instructions</h4>
<p>This form provides for uploading of event data in a <strong>Comma Separated Values (CSV)</strong> text file to the SuperCali Calendar.</p><p>Order of columns is; title, venue id, contact id, category id, date, start time, end time and description.  The first row of the CSV file, used for column descriptions, is ignored.</p><p>A sample CSV file can be downloaded <a href="files/supercali_import_template.csv" target="_blank">here</a>.  </p>
<h4>Table of Venues/Contacts</h4>
<p>The CSV file uses id numbers to specify venues and contacts.  These correspond to entries in the <strong>Links</strong> section.  For your calendar, the table of Links is as follows:</p>
<table class="grid" style="margin: 10px 0px;">
<tr><th style="width:auto;">ID</th><th style="width:auto;">City</th><th style="width:auto;">State</th><th style="width:auto;">Company</th></tr>
<tr><td style="text-align:center">1</td><td colspan="3">Default: Information shown in event description.</td></tr>
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
<h4>Table of Categories</h4>
<p>The CSV file also uses id numbers to specify categories.  These correspond to entries in the <strong>Categories</strong> section.  For your calendar, the table of Categories is as follows:</p>
<table class="grid" style="margin: 10px 0px;">
<tr><th style="width:auto;">ID</th><th style="width:auto;">Category</th></tr>
<?php
category_table(0);
?>
</table>
<h4>Table of Groups</h4>
<p>The CSV file also uses id numbers to specify groups.  These correspond to entries in the <strong>Groups</strong> section.  For your calendar, the table of Groups is as follows:</p>
<table class="grid" style="margin: 10px 0px;">
<tr><th style="width:auto;">ID</th><th style="width:auto;">Group</th></tr>
<?php
group_table(0);
?>
</table>
<h4>Users</h4>

<p>The users menu allows you to add and edit users and their privileges for using SuperCali.  There are three main areas that can be edited including their e-mail address/password, access privileges and individual category permissions to view, post and moderate other users entries.</p>

<p>The two main access privileges include the ability to add/edit/remove the SuperCali categories and whether or not the user can edit other users.  If a user has category editing privileges, you can select which categories each user can post or moderate (edit) other users posts.  Permission to moderate a category also implies the ability to post events to that category.</p>
<p>Subscribe indicates whether the visitor is notified of posted or proposed events.</p>
<p>To save changes, either click &quot;Add Profile&quot; or &quot;Update Profile&quot;</p>

<h4>Modules</h4>
<p>The modules menu is used to add, edit and delete &quot;modules&quot; used to display information on the calendar.</p>
<p>The first column, Delete, has a checkbox that, if checked, will delete the module from the calendar.  The next two columns, heading and link name, correspond to the headline and navigation link name which are shown to the user.  Active indicates whether the module is currently displayed and Sequence indicates the relative order of each module.</p> 
<p>Year, month, week and day correspond to the module id that certain links imbedded in that module should link to.  Some of these links are not used, depending on the module.
<p>Click &quot;Update Modules&quot; in order to make finalize changes to the modules.</p>
<p>In addition, any module files that are found in the modules directory, but not yet installed, will be shown with a description.  Click on the Add link at the bottom of each description to install the new module.  To install a new module from scratch, upload the module and supporting files to the modules directory first, then visit the modules page to add it.

<h4>Log Out</h4>

<p>To log out of your calendar management session, click the &quot;Log Out&quot; link.</p>


<?php
	include "includes/footer.php";
}
?>
