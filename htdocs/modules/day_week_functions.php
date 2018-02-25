<?php
/*
Supercali Event Calendar

Copyright 2007 Dana C. Hutchins

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
function showGrid($date) {
	global $title, $niceday, $start_time, $end_time, $venue, $city, $state, $cat, $color, $background, $ed, $usr, $o, $c, $m, $a, $y, $w, $lang,$scale,$ap,$status, $day_week_start_hour, $day_week_end_hour;
	$threshold_hour = $day_week_start_hour ? $day_week_start_hour : 0;
	$threshold_min = $day_week_start_hour ? 0 : 30;
	$txi = ($threshold_hour * 60) + $threshold_min;
	if ($start_time[$date]) {
		ksort($start_time[$date]);
		$scale = 1;
			$wait = 1;
		while (list($t) = each($start_time[$date])) {
			
			
			while (list($id,$value) = each($start_time[$date][$t])) {
				if (preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$value,$parse_start_time)) {
					if ((preg_match("/am/i",$parse_start_time[3])) && ($parse_start_time[1] == 12)) $parse_start_time[1] = $parse_start_time[1] - 12;
					if ((preg_match("/pm/i",$parse_start_time[3])) && ($parse_start_time[1] < 12)) $parse_start_time[1] = $parse_start_time[1] + 12;							
					if (strlen($parse_start_time[1]) == 1) $parse_start_time[1] = "0".$parse_start_time[1];
					
					
					if ($end_time[$date][$t][$id]) {
						preg_match("/([0-9]{1,2}):([0-9]{2})[ ]?([a|p]m)/i",$end_time[$date][$t][$id],$parse_end_time);
						if ((preg_match("/am/i",$parse_end_time[3])) && ($parse_end_time[1] == 12)) $parse_end_time[1] = $parse_end_time[1] - 12;
						if ((preg_match("/pm/i",$parse_end_time[3])) && ($parse_end_time[1] < 12)) $parse_end_time[1] = $parse_end_time[1] + 12;							
						if (strlen($parse_end_time[1]) == 1) $parse_end_time[1] = "0".$parse_end_time[1];
						
					} else {
						$parse_end_time[1] = $parse_start_time[1] + 1;
						if ($parse_end_time[1] > 24) $parse_end_time[1] = 24;
						if ($parse_end_time[1] == 24) $parse_end_time[2] = "00";
						
					}
					
				} elseif($value == "All Day") {
					$parse_start_time[1] = "00";
					$parse_start_time[2] = "00";
					$parse_end_time[1] = "24";
					$parse_end_time[2] = "00";
					
				} elseif($value == "TBA") {
					$parse_start_time[1] = "00";
					$parse_start_time[2] = "00";
					$parse_end_time[1] = "00";
					$parse_end_time[2] = "30";
					
				}
				
				$event_id[] = $id;
				$sxi = ($parse_start_time[1] * 60) + $parse_start_time[2];
				$exi = ($parse_end_time[1] * 60) + $parse_end_time[2];
				if ($sxi < $txi) {
					$sxi = 0;
					if ($exi <= $txi) {
						$exi = 30;
					} else {
						$exi = $exi - $txi + 30;
					}
				} else {
					$sxi = $sxi - $txi + 30;
					$exi = $exi - $txi + 30;
				}
				
				$sh[] = $exi - $sxi - 2;
				$sx[] = $sxi;
				$ex[] = $exi;
				$len = $exi - $sxi;
				$sta[] = $value;
				$end[] = $end_time[$date][$t][$id];
				
			}
		}
		arsort($sh);
		$start_empty[0][0] = 0;
		$end_empty[0][0] = 1440;
		$indent = 0;
		while (list($k,$v) = each($sh)) {
			$found = false;
			
			reset($start_empty);
			while (list($r) = each($start_empty)) {
				reset($start_empty[$r]);
				while (list($kk,$vv) = each($start_empty[$r])) {
					if (($sx[$k] >= $vv) && ($ex[$k] <= $end_empty[$r][$kk])) {
						$end_empty[$r][] = $end_empty[$r][$kk];
						$end_empty[$r][$kk] = $sx[$k];
						$start_empty[$r][] = $ex[$k];
						$start_fill[$r][] = $sx[$k];
						$end_fill[$r][] = $ex[$k];
						$start_event[$r][] = $event_id[$k];
						$sta_e[$r][] = $sta[$k];
						$end_e[$r][] = $end[$k];
						$event_length[$r][] = $v;
						$found = true;
						break 2;
						
						
						
					}
				}
			}
			if (!$found) {
				$indent++;
				$start_empty[$indent][0] = 0;
				$end_empty[$indent][0] = $sx[$k];
				$start_empty[$indent][1] = $ex[$k];
				$end_empty[$indent][1] = 1440;
				$start_fill[$indent][0] = $sx[$k];
				$end_fill[$indent][0] = $ex[$k];
				$start_event[$indent][0] = $event_id[$k];
				$event_length[$indent][0] = $v;
				$sta_e[$indent][0] = $sta[$k];
				$end_e[$indent][0] = $end[$k];
			}
		}
		$columns = $indent+1;;
		$per = 100 / $columns;
		$wide = number_format($per,0);
		$notsowide = $wide-1;
		reset($start_fill);
		while (list($r) = each($start_fill)) {
			while (list($k,$v) = each($start_fill[$r])) {
				$v = $v+30;
				$left = $wide * $r;
				// wrap fix from Vepr				
				echo "<div class=\"wrap\"><div class=\"date\" style=\"";
				echo "height: ".$event_length[$r][$k]."px; top: ".$v."px; width: ".$notsowide."%; left:".$left."%;";
				if ($color[$start_event[$r][$k]]) echo "color: ".$color[$start_event[$r][$k]]."; background: ".$background[$start_event[$r][$k]].";";
				if (($status[$start_event[$r][$k]] == 2) || ($status[$start_event[$r][$k]] == 3)) echo " filter:alpha(opacity=80); opacity:.80; -moz-opacity:.80; zoom: 1; border: 1px dashed ".$color[$start_event[$r][$k]].";";
				echo "\"><div class=\"inner\">";
			
				echo "<div class=\"title\"><a href=\"show_event.php?id=".$start_event[$r][$k]."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('show_event.php?id=".$start_event[$r][$k]."&size=small','pop','600','400'); window.newWindow.focus(); return false\"";
				if ($color[$start_event[$r][$k]]) echo " style=\"color: ".$color[$start_event[$r][$k]]."; background: ".$background[$start_event[$r][$k]].";\"";
				echo ">".$title[$start_event[$r][$k]]."</a></div>\n";
				echo "<span class=\"time\">".$sta_e[$r][$k];
				if ($end_e[$r][$k]) echo " - ".$end_e[$r][$k];
				echo "</span>\n";
				if ($ed[$start_event[$r][$k]]==true) {
					echo "&nbsp;&nbsp;<span class=\"edit\">";
					if (($ap[$start_event[$r][$k]]==true) && (($status[$start_event[$r][$k]] == 2) || ($status[$start_event[$r][$k]] == 3))) echo "[<a href=\"admin_actions.php?id=".$start_event[$r][$k]."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."&mode=".approve."\">".$lang["approve"]."</a>]&nbsp;&nbsp;";
					echo "[<a href=\"edit_event.php?id=".$start_event[$r][$k]."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('edit_event.php?id=".$start_event[$r][$k]."&size=small','pop','650','600'); window.newWindow.focus(); return false\">".$lang["edit"]."</a>]&nbsp;&nbsp;[<a href=\"delete_event.php?id=".$start_event[$r][$k]."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\">".$lang["delete"]."</a>]</span>\n";
				}
				echo "</div></div></div>\n";
			}
		}
	
	}
	//echo "</div>\n";
}


function showHours() {
	global $day_week_start_hour, $day_week_end_hour;
	// build day
	echo "<td class=\"timex\"><table class=\"day\"><tr><td width=\"100%\"><div class=\"time_frame\">\n";
	echo "<div class=\"cell_top\">Time</div>\n";
	echo "<div class=\"cell\">12:00 am ".$day_week_start_min."</div>\n";
	$i = $day_week_start_hour ? $day_week_start_hour : 0;
	$j = $day_week_start_hour ? 0 : 30;
	$max = $day_week_end_hour ? $day_week_end_hour : 24;
	while ($i < $max) {
		if ($j < 10) {
			$j = "0".$j;
			
		}
		if ($i == 0) {
			$h = 12;
			$ap = "am";
		} elseif ($i == 12) {
			$h = $i;
			$ap = "pm";
		} elseif ($i > 12) {
			$h = $i - 12;
			$ap = "pm";
		} else {
			$h = $i;
			$ap = "am";
		}
		echo "<div class=\"cell\">".$h.":".$j." ".$ap."</div>\n";
		$j = $j+30;
		if ($j >= 60) {
			$j = "0";
			$i++;
		}
	}
	echo "</div></td></tr></table></td>";

}

function showDay($dy,$dm,$da,$caption="") {
	global $la, $w, $c, $day_week_start_hour, $day_week_end_hour;
	// build day
	echo "<div class=\"single_day_frame\">";
	echo "<div class=\"cell_top\">";
	if($caption) echo $caption;
	else {
		echo '<a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$dm,'&a=',$da,'&y=',$dy,'">';
		echo date('l, F j', mktime(0,0,0,$dm,$da,$dy));
		echo '</a>';
	}
	echo "</div>";
	echo "<div class=\"cell\" id=\"0:00:".$dm."/".$da."/".$dy."\"></div>\n";
	$i = $day_week_start_hour ? $day_week_start_hour : 0;
	$j = $day_week_start_hour ? 0 : 30;
	$max = $day_week_end_hour ? $day_week_end_hour : 24;
	
	while ($i < $max) {
		if ($j < 10) {
			$j = "0".$j;
			if ($i < 10) $i = $i;
		}
		if ($i == 0) {
			$h = 12;
			$ap = "am";
		} elseif ($i == 12) {
			$h = $i;
			$ap = "pm";
		} elseif ($i > 12) {
			$h = $i - 12;
			$ap = "pm";
		} else {
			$h = $i;
			$ap = "am";
		}
		if ($i < 10) $i = $i;
		//echo "<div class=\"cell\" id=\"".$i.":".$j."\">".$h.":".$j." ".$ap."</div>\n";
		echo "<div class=\"cell\" id=\"".$i.":".$j.":".$dm."/".$da."/".$dy."\"></div>\n";
		$j = $j+30;
		if ($j >= 60) {
			$j = "0";
			$i++;
		}
	}
	
	
	
	$sdate = $dy.$dm.$da;
	echo "<div id=\"dates\">\n";
	showGrid($sdate);
	echo "</div>";
	echo "</div>";


}




if ($superpost) {
$javascript = '<script type="text/javascript">
/*
	Written by Jonathan Snook, http://www.snook.ca/jonathan
	Add-ons by Robert Nyman, http://www.robertnyman.com
*/

function getElementsByClassName(oElm, strTagName, strClassName){
	var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
	var arrReturnElements = new Array();
	strClassName = strClassName.replace(/-/g, "\-");
	var oRegExp = new RegExp("(^|\s)" + strClassName + "(\s|$)");
	var oElement;
	for(var i=0; i<arrElements.length; i++){
		oElement = arrElements[i];
		if(oRegExp.test(oElement.className)){
			arrReturnElements.push(oElement);
		}
	}
	return (arrReturnElements)
}
var start;
var end;
var flagged = Array();
window.onload = function () {
var x = getElementsByClassName(document, "div", "cell")
for (var i=0;i<x.length;i++) {
	//x[i].onmousedown = function () {this.style.backgroundColor="#cccccc"}
	x[i].onmousedown = startup
	x[i].onmouseout = flag
	x[i].onmouseover = whatadrag
	x[i].onmouseup = endup
	//x[i].onmouseout = function () {this.style.backgroundColor="#ffffff"}
	//x[i].onclick = function () {this.innerHTML = this.id}
	//x[i].onclick = click
}
/*
var x = getElementsByClassName(document, "div", "date")
for (var i=0;i<x.length;i++) {
	x[i].onmouseout = contract
	x[i].onmouseover = expand
	
}

function expand () {
	this.oldheight = this.style.height
	this.style.height ="auto"
	this.style.zIndex = 2
}

function contract () {
	this.style.height = this.oldheight
	this.style.zIndex = 1
}
*/
}
function startup () {
	start = this.id
	end = this.id
	this.style.backgroundColor="#cccccc"
	
}

function flag() {
	var next = Math.abs(start - end)
	var cur = Math.abs(start - this.id)
	
}

function whatadrag() {
	
	if (start) {
		this.style.backgroundColor="#cccccc"
		var cur = Math.abs(start - this.id)
		var next = Math.abs(start - end)
		if (cur < next) {
			document.getElementById(end).style.backgroundColor="#ffffff";
		}
		end = this.id;
	}
	
}

function getdatestring(i,j) {
	var h
	var ap
	if (i == 0) {
		h = 12;
		ap = "am";
	} else if (i == 12) {
		h = i;
		ap = "pm";
	} else if (i > 12) {
		h = i - 12;
		ap = "pm";
	} else {
		h = i;
		ap = "am";
	}
	if (j < 30) j = "00";
	var stringy = h + ":" + j + " " + ap;
	return(stringy);

}

function endup() {
	end = this.id
	if (start.substring(2,3) == ":") {
		var i = start.substring(0,2)
		var j = start.substring(3,5)
		var ddate = start.substring(6)
	} else {
		var i = start.substring(0,1)
		var j = start.substring(2,4)
		var ddate = start.substring(5)
	}
	j = parseInt(j);
	i = parseInt(i);
	var sstart = getdatestring(i,j);
	if (end.substring(2,3) == ":") {
		var i = end.substring(0,2)
		var j = end.substring(3,5)
	} else {
		var i = end.substring(0,1)
		var j = end.substring(2,4)
	}
	j = parseInt(j);
	i = parseInt(i);
	j = j + 30;
	if (j == 60) {
		j = "00";
		
		i++;
	}
	var eend = getdatestring(i,j);
	
	
	var adfield = "add_event.php?size=small&next_date=" + ddate + "&next_start=" + sstart + "&next_end=" + eend;
	x=openPic(adfield,"pop","600","400")
	x.focus();
	start = null;
	end = null;
	flagged = Array()
	
	var x = getElementsByClassName(document, "div", "cell")
	
	for (var i=0;i<x.length;i++) {
		x[i].style.backgroundColor="#ffffff"
		//x[i].blur()
	}
}
</script>';
}
?>