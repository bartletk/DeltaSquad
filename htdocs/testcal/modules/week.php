<?php
/*
<?xml version="1.0" encoding="utf-8"?>
<module>
        <name>Week View</name>
        <author>Dana C. Hutchins</author>
        <url>http://supercali.inforest.com/</url>
        <version>1.0.0</version>
        <link_name>Week</link_name>
        <description>Shows a week on one screen.</description>
        <image></image>
		<install_script></install_script>     
</module>
*/
include "modules/day_week_functions.php";
include "includes/header.php";
?>

<?php
$thisweek = $now["week"]["y"][0]."-".$now["week"]["m"][0]."-".$now["week"]["a"][0];
$nextweek =  $next["week"]["y"]."-".$next["week"]["m"]."-".$next["week"]["a"];
grab($thisweek,$nextweek,$c);
echo "<div class=\"frame\">\n";
echo '<div class="cal_top"><a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$prev["week"]["m"],'&a=',$prev["week"]["a"],'&y=',$prev["week"]["y"],'">&lt;</a> ',$lang["week_of"],date('F j, Y', mktime(0,0,0,$now["week"]["m"][0],$now["week"]["a"][0],$now["week"]["y"][0])),' <a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$next["week"]["m"],'&a=',$next["week"]["a"],'&y=',$next["week"]["y"],'">&gt;</a></div>'."\n";
echo "<table class=\"day\"><tr>";
showHours();
echo "<td><table class=\"day\"><tr>";

for ($we=0;$we<7;$we++) {
	echo "<td width=\"14%\" class=\"single_day\">\n";
	showDay($now["week"]["y"][$we],$now["week"]["m"][$we],$now["week"]["a"][$we]);
	echo "</td>";
}
echo "</tr></table></td></tr></table>";
echo "</div>\n";
include "includes/footer.php";
?>