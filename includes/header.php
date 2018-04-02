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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title><?php echo $calendar_title." - ".$page_title; ?></title>
<link rel="stylesheet" type="text/css" href="css/calendar.css">
<?php if ($_REQUEST["size"] == "small") echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/calendar-small.css\">\n"; ?>
<?php if ($css) echo $css; ?>


<script language="JavaScript" src="js/CalendarPopup.js"></script>
<script language="JavaScript">document.write(getCalendarStyles());</script>
<script language="JavaScript" src="js/ColorPicker2.js"></script>
<script language="JavaScript" src="js/miscfunctions.js"></script>
<?php if ($ck_editor_path) echo "<script language=\"JavaScript\" src=\"".$ck_editor_path."ckeditor.js\"></script>\n"; ?>
<?php if ($javascript) echo $javascript; ?>


</head>

<body>
<div class="top">
<div class="top_nav">
<?php 
if ($_REQUEST["size"] == "small") {
	echo "<a href=\"javascript:self.close()\" target=\"_self\">"."Close Window"."</a>\n";
}

?>
</div>
</div>
<div class="nav">
<?php include "includes/nav.php"; ?>
</div>
<div class="content">
<?php if ($msg) echo "<p class=\"warning\">".$msg."</p>\n"; ?>