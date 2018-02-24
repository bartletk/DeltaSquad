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
$page_title = $lang["title_incorrect_password"];

include "includes/header.php";
if ($_SESSION['user_id'] == 1) {
?>
<p class="warning"><?php echo $lang["your_password_incorrect"]; ?></p>
<form action="actions.php" method="post">
<p><?php echo $lang["enter_email"]; ?>:</p>
<table>
<tr><TD ALIGN="RIGHT"><?php echo $lang["email"]; ?>:</td><td><INPUT TYPE="Text" NAME="email" SIZE="40" value="<?php echo $_REQUEST["email"];?>"></td></tr>
</table>
<input type="hidden" name="return_to" value="<?php echo $_REQUEST["return_to"]; ?>">
<input type="submit" name="mode" value="<?php echo $lang["send_new_password"]; ?>">
</form>
<?php
} else {
	echo "<p>".$lang["already_logged_in"]."</p>\n";
}

include "includes/footer.php";
?>