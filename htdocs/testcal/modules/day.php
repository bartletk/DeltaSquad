<?php
/*
<?xml version="1.0" encoding="utf-8"?>
<module>
        <name>Day View</name>
        <author>Dana C. Hutchins</author>
        <url>http://supercali.inforest.com/</url>
        <version>1.0.0</version>
        <link_name>Day</link_name>
        <description>Shows a day on one screen.</description>
        <image></image>
		<install_script></install_script>     
</module>
*/
include "modules/day_week_functions.php";
include "includes/header.php";
?>

<?php
$thisday = $y."-".$m."-".$a;
$nextday =  $next["day"]["y"]."-".$next["day"]["m"]."-".$next["day"]["a"];
grab($thisday,$nextday,$c);
echo "<div class=\"frame\">\n";
echo '<div class="cal_top"><a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$prev["day"]["m"],'&a=',$prev["day"]["a"],'&y=',$prev["day"]["y"],'">&lt;</a> ',date('l, F j, Y', mktime(0,0,0,$m,$a,$y)),' <a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$next["day"]["m"],'&a=',$next["day"]["a"],'&y=',$next["day"]["y"],'">&gt;</a></div>'."\n";
echo "<table class=\"day\"><tr>";
showHours();
echo "<td><table class=\"day\"><tr>";
echo "<td width=\"100%\" class=\"single_day\">\n";
showDay($y,$m,$a,"Events");
echo "</td>";
echo "</tr></table></td></tr></table>";
echo "</div>\n";
include "includes/footer.php";
?>