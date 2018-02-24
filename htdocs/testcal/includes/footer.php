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
</div>
<div class="bottom">
<?php if (($include_child_categories) || ($include_parent_category)) { ?>
<p style="float: right"><?php echo $lang["calendar_display_start"]; ?>
<?php
if ($include_child_categories) {
	echo "sub categories";
	$sub = 1;
}
if ($include_parent_categories) {
	if ($sub) echo " and ";
	echo "parent categories";
}
?>
<?php echo $lang["calendar_display_end"]; ?></p>
<?php } ?>
<p><a href="http://supercali.inforest.com/" target="_blank">SuperCali Event Calendar</a></p>
</div>
<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<SCRIPT LANGUAGE="JavaScript">cp.writeDiv()</SCRIPT>
</body>
</html>
<?php mysql_close($link); ?>