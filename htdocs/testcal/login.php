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
$page_title = $lang["title_log_in"];

include "includes/header.php";
if ($_SESSION['user_id'] == 1) {
?>
<form action="actions.php" method="post">
<table>
<tr>
<td><?php echo $lang["email"]; ?>:</td><td><input type="text" name="email" size="30"></td>
</tr>
<tr>
<td><?php echo $lang["password"]; ?>:</td><td><input type="password" name="password" size="30"></td>
</tr>
</table>
<input type="hidden" name="return_to" value="<?php echo $_REQUEST["return_to"] ? $_REQUEST["return_to"]:$_SERVER['HTTP_REFERER']; ?>">
<?php include "includes/hidden_fields.php"; ?>
<input type="submit" name="mode" value="Log In" />
</form>
<?php
} else {
	echo "<p>".$lang["logged_in"]."</p>\n";
}

include "includes/footer.php";
?>