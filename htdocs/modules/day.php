<?php

include "modules/day_week_functions.php";
	include "top_header.php";
?>

<?php
$thisday = $y."-".$m."-".$a;
$nextday =  $next["day"]["y"]."-".$next["day"]["m"]."-".$next["day"]["a"];
echo "<div class=\"frame\">\n";
echo '<div class="cal_top"><a href="index.php?o=',$o,'&w=',$w,'&c=',$c,'&m=',$prev["day"]["m"],'&a=',$prev["day"]["a"],'&y=',$prev["day"]["y"],'&sem=',$sem,'&cwid=',$studentCWID,'&rm=',$rm,'&cm=',$cm,'">&lt;</a> ',date('l, F j, Y', mktime(0,0,0,$m,$a,$y)),' <a href="index.php?o=',$o,'&w=',$w,'&c=',$c,'&m=',$next["day"]["m"],'&a=',$next["day"]["a"],'&y=',$next["day"]["y"],'&sem=',$sem,'&cwid=',$studentCWID,'&rm=',$rm,'&cm=',$cm,'">&gt;</a></div>'."\n";
echo "<table class=\"day\"><tr>";
showHours();
echo "<td><table class=\"day\"><tr>";
echo "<td width=\"100%\" class=\"single_day\">\n";
showDay($y,$m,$a,"Events");
echo "</td>";
echo "</tr></table></td></tr></table>";
echo "</div>\n";

?>