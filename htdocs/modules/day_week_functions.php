<?php
	
	function showGrid($date) {
		GLOBAL $session;
		$dateNew = substr_replace(substr_replace($date, "-", 6, 0), "-", 4, 0);
		$CWID = $session->getCWID();
		$link = mysql_connect (DB_SERVER, DB_USER, DB_PASS) or die ("Could not connect to database, try again later");
		mysql_select_db(DB_NAME,$link);
				// If student
		if(!$session->isInstructor() & !$session->isAdmin()){
			if (isset($_GET['cwid'])&&$_GET['cwid']!=0&&$_GET['cwid']!=NULL){
			$studentCWID = $_GET['cwid'];
				$q = sprintf("SELECT DISTINCT ".TBL_EVENTS.".* from ".TBL_EVENTS." join ".TBL_SCHED." on ".TBL_SCHED.".crn = ".TBL_EVENTS.".crn where (".TBL_SCHED.".cwid = $studentCWID OR series=9100) AND CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE) AND status='approved'");
				} else {
				header ("Location: class_select.php");
			}
			// if teacher
			} elseif (!$session->isAdmin() & $session->isInstructor()) {
			
			if (isset($sem) && ($sem != 0) && ($sem != NULL)){
				$q = sprintf("select  ".TBL_EVENTS.".* from ".TBL_EVENTS." join ".TBL_CRN." ON ".TBL_EVENTS.".crn = ".TBL_CRN.".crn join ".TBL_COURSE." on ".TBL_COURSE.".course_number = ".TBL_CRN.".course_number where CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE) AND (semester = $sem OR semester=0 OR series=9100)");
				} else {
				$q = sprintf("select DISTINCT ".TBL_EVENTS.".* from ".TBL_EVENTS." join ".TBL_CRN." ON ".TBL_EVENTS.".crn = ".TBL_CRN.".crn join ".TBL_COURSE." on ".TBL_COURSE.".course_number = ".TBL_CRN.".course_number where (".TBL_CRN.".instructor = $CWID OR ".TBL_COURSE.".Lead_Instructor = $CWID OR series=9100) AND CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)");
			}
			//if admin
			} else {
			$sem = $_GET['sem'];
			if (isset($sem) && ($sem != 0) && ($sem != NULL)){
				// change to all of a semester's classes
				$q = sprintf("select  ".TBL_EVENTS.".* from ".TBL_EVENTS." join ".TBL_CRN." ON ".TBL_EVENTS.".crn = ".TBL_CRN.".crn join ".TBL_COURSE." on ".TBL_COURSE.".course_number = ".TBL_CRN.".course_number where CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE) AND (semester = $sem OR semester=0 OR series=9100)");	
				//$myfile = fopen("error.txt", "a") or die(print_r($q));
				} else {
				$q = sprintf("SELECT DISTINCT ".TBL_EVENTS.".* FROM ".TBL_EVENTS." WHERE CAST(dateStart AS DATE) = CAST('$dateNew' AS DATE)");
			}
		}
		$result = mysql_query($q, $link);
		$previousEvents[] = "";
		if(!$result || (mysql_num_rows($result) < 1)){
			// NO EVENTS
			} else {
			// EVENTS
			for ($i=0; $i < mysql_num_rows($result); $i++){
				$start_timeF = date("h:i A",strtotime(mysql_result($result,$i,"dateStart")));
				$end_timeF = date("h:i A",strtotime(mysql_result($result,$i,"dateEnd")));
				$start_timeH = date("H",strtotime(mysql_result($result,$i,"dateStart")));
				$end_timeH = date("H",strtotime(mysql_result($result,$i,"dateEnd")));
				$start_timeM = date("i",strtotime(mysql_result($result,$i,"dateStart")));
				$end_timeM = date("i",strtotime(mysql_result($result,$i,"dateEnd")));
				$start_time = date("H:i",strtotime(mysql_result($result,$i,"dateStart")));
				$end_time = date("H:i",strtotime(mysql_result($result,$i,"dateEnd")));
				$event = mysql_result($result,$i,"event_id");
				$series = mysql_result($result,$i,"series");
				$title = mysql_result($result,$i,"title");
				$room = mysql_result($result,$i,"room_number");
				$crn = mysql_result($result,$i,"crn");
				$fromTop = ((($start_timeH) + ($start_timeM / 60)) * (28*2))+24;
				$length = ((strtotime($end_time) - strtotime($start_time))/(60*60))*(28*2);
				if (mysql_result($result,$i,"status") != 'approved') {
					$style = "style='color: white; text-shadow: 1px 1px 2px black, 0 0 25px yellow, 0 0 5px orange;'";
					} else if (mysql_result($result,$i,"series") ==9100) {
					$style = "style='color: white; text-shadow: 1px 1px 2px black, 0 0 25px gray, 0 0 5px black;'";
				} else {	
					$style = "";
				}
				$current[] = "";
				$current[0] = $start_timeF;
				$current[1] = $end_timeF;
				$current[2] = $title;
				$current[3] = $room;
				$current[4] = ($fromTop+24);
				$current[5] = $length;
				$current[6] = $end_time;
				$current[7] = $event;
				$current[8] = $series;
				$current[9] = $crn;
				$current[10] = $style;
				$previousEvents[$i]=$current;
			}
			
			// put the remaining array on the calendar once all events are added to it
			
		}
		if ($previousEvents[0] != ""){
			for($z = 0; $z < sizeof($previousEvents); $z++){
				$i = 1;
				for($y = 1; $y < sizeof($previousEvents); $y++){
					if ($previousEvents[$z][6] <= $previousEvents[$y][6]){
						// start time falls within previous time's range	
						$i++;
						} else {
						// start time is outside of previous time's range
						for ($j = 0; $j <= $i; $j++){
							$removed = array_shift($previousEvents);
							echo "<div class=\"wrap\"><div class=\"date\" style=\"";
							echo "height: ".$removed[5]."px; top: ".$removed[4]."px; width: ".(100 / $i)."%; left:".((100/$i)*$j)."%;";
							echo "\"><div class=\"inner\">";
							echo "<div class=\"title\"><a href='/showevent.php?e=".$removed[7]."&s=".$removed[8]."' ".$removed[10].">";
							echo $removed[2]."</a><br>".$removed[9]."<br>".$removed[3]."</div>\n";
							echo "<span class=\"time\">".$removed[0];
							echo " - ".$removed[1];
							echo "</span>\n";
							echo "</div></div></div>\n";
						}
					}
				}
				for ($f = 0; $f <= sizeof($previousEvents); $f++){
					$removed = array_shift($previousEvents);
					echo "<div class=\"wrap\"><div class=\"date\" style=\"";
					echo "height: ".$removed[5]."px; top: ".$removed[4]."px; width: ".(100 / $i)."%; left:".(100/$i)*$f."%;";
					echo "\"><div class=\"inner\">";
					echo "<div class=\"title\"><a href='/showevent.php?e=".$removed[7]."&s=".$removed[8]."' ".$removed[10].">";
					echo $removed[2]."</a><br>".$removed[9]."<br>".$removed[3]."</div>\n";
					echo "<span class=\"time\">".$removed[0];
					echo " - ".$removed[1];
					echo "</span>\n";
					echo "</div></div></div>\n";
				}
			}
		}
	}
	
	
	
	
	function showHours() {
		
		global $day_week_start_hour, $day_week_end_hour;
		// build day
		echo "<td class=\"timex\"><table class=\"day\"><tr><td width=\"100%\"><div class=\"time_frame\">\n";
		echo "<div class=\"cell_top\">Time</div>\n";
		$i = $day_week_start_hour ? $day_week_start_hour : 0;
		$j = $day_week_start_hour ? 0 : 30;
		$max = $day_week_end_hour ? $day_week_end_hour : 24;
		//echo "<div class=\"cell\">".$h.":".$j." ".$ap."</div>\n";
		echo '		<tr data-time="00:00:00">
		<td class="cell"><span>12am</span></td>
		<td></td>
		</tr>
		<tr data-time="00:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="01:00:00">
		<td class="cell"><span>1am</span></td>
		<td></td>
		</tr>
		<tr data-time="01:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="02:00:00">
		<td class="cell"><span>2am</span></td>
		<td></td>
		</tr>
		<tr data-time="02:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="03:00:00">
		<td class="cell"><span>3am</span></td>
		<td></td>
		</tr>
		<tr data-time="03:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="04:00:00">
		<td class="cell"><span>4am</span></td>
		<td></td>
		</tr>
		<tr data-time="04:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="05:00:00">
		<td class="cell"><span>5am</span></td>
		<td></td>
		</tr>
		<tr data-time="05:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="06:00:00">
		<td class="cell"><span>6am</span></td>
		<td></td>
		</tr>
		<tr data-time="06:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="06:00:00">
		<td class="cell"><span>7am</span></td>
		<td></td>
		</tr>
		<tr data-time="07:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="08:00:00">
		<td class="cell"><span>8am</span></td>
		<td></td>
		</tr>
		<tr data-time="08:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="09:00:00">
		<td class="cell"><span>9am</span></td>
		<td></td>
		</tr>
		<tr data-time="09:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="10:00:00">
		<td class="cell"><span>10am</span></td>
		<td></td>
		</tr>
		<tr data-time="10:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="11:00:00">
		<td class="cell"><span>11am</span></td>
		<td></td>
		</tr>
		<tr data-time="11:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="12:00:00">
		<td class="cell"><span>12pm</span></td>
		<td></td>
		</tr>
		<tr data-time="12:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="13:00:00">
		<td class="cell"><span>1pm</span></td>
		<td></td>
		</tr>
		<tr data-time="13:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="14:00:00">
		<td class="cell"><span>2pm</span></td>
		<td></td>
		</tr>
		<tr data-time="14:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="15:00:00">
		<td class="cell"><span>3pm</span></td>
		<td></td>
		</tr>
		<tr data-time="15:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="16:00:00">
		<td class="cell"><span>4pm</span></td>
		<td></td>
		</tr>
		<tr data-time="16:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="17:00:00">
		<td class="cell"><span>5pm</span></td>
		<td></td>
		</tr>
		<tr data-time="17:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="18:00:00">
		<td class="cell"><span>6pm</span></td>
		<td></td>
		</tr>
		<tr data-time="18:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="19:00:00">
		<td class="cell"><span>7pm</span></td>
		<td></td>
		</tr>
		<tr data-time="19:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="20:00:00">
		<td class="cell"><span>8pm</span></td>
		<td></td>
		</tr>
		<tr data-time="20:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="21:00:00">
		<td class="cell"><span>9pm</span></td>
		<td></td>
		</tr>
		<tr data-time="21:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="22:00:00">
		<td class="cell"><span>10pm</span></td>
		<td></td>
		</tr>
		<tr data-time="22:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>
		
		<tr data-time="23:00:00">
		<td class="cell"><span>11pm</span></td>
		<td></td>
		</tr>
		<tr data-time="23:30:00" >
		<td class="cell"></td>
		<td ></td>
		</tr>';
		echo "</div></td></tr></table></td>";
		
	}
	
	function showDay($dy,$dm,$da,$caption="") {
		global $la, $w, $c, $day_week_start_hour, $day_week_end_hour;
		// build day
		echo "<div class=\"single_day_frame\">";
		echo "<div class=\"cell_top\">";
		if($caption) echo $caption;
		else {
			echo '<a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$dm,'&a=',$da,'&y=',$dy,'&sem=',$sem,'">';
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