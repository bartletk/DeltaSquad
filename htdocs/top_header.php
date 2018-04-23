<?php
	
	include("include/session.php");
	
?>
<html>
	
	<head>
		<title>ULM Nursing Scheduler</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="/css/other.css">
		<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />
		<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/css/calendar.css">
		<link rel="stylesheet" type="text/css" href="/css/addevent.css">
		<link rel="stylesheet" type="text/css" href="/css/navbar.css">
		<link rel="stylesheet" type="text/css" href="/css/loginAndRegister.css">
		<link rel="stylesheet" type="text/css" href="/css/mycourse.css">
		<?php 
	
			if ($session->url == "roomview.php"){
			?>
			<link rel="stylesheet" type="text/css" href="/css/roomview.css">
			<?php 
			}
		?>
		<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<script src="/js/materialize.js"></script>
		<!--<script src="/js/hash.js"></script>-->
	</head>
	<body>
		<?php
			$q = "SELECT mail_id FROM ".TBL_MAIL." WHERE UserTo = '$session->username' and status = 'unread'";
			$numUnreadMail = $database->query($q) or die(mysql_error());
			$numUnreadMail = mysql_num_rows($numUnreadMail);
		?>
		<nav>
			<div class="nav-wrapper">
				<a href="/index.php<?php if (isset($_GET['cwid'])){echo '?cwid='.$_GET['cwid'];} ?>" class="brand-logo"><img src="/img/ulmlogo.png" alt="logo" class="logo" ></a>
				<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
				<ul class="right hide-on-med-and-down">
					<?php
						/**
							* User has already logged in, so display relavent links, including
							* a link to the admin center if the user is an administrator.
						*/
						if($session->logged_in){ ?> 
						<?php
							if($session->isAdmin()){
							?>
							<li><a href="/admin/admin.php">Admin Center</a></li>
							<?php } 
							if ($session->isInstructor() || $session->isAdmin()){ ?>
							<li><a href="/addevent.php">Add Event</a></li>
							<li><a href="/mycourse.php">My Courses </a></li>
							<li><a href="/messages.php">Messages<?php if ($numUnreadMail > 0){echo " ($numUnreadMail)"; } ?></a></li>
						<?php } ?>
						<li><a href="/index.php">Calendar</a></li>
						<li><a href="/process.php">Logout</a></li>
						<?php
							} else {
						?>
						<li><a href="/login.php">Faculty Login</a></li>
					<?php } ?>
				</ul>
				<ul class="side-nav" id="mobile-demo">
					<?php
						/**
							* User has already logged in, so display relavent links, including
							* a link to the admin center if the user is an administrator.
						*/
						if($session->logged_in){ ?>
						<?php
							if($session->isAdmin()){
							?>
							<li><a href="/admin/admin.php">Admin Center</a></li>
							<?php }
							if ($session->isInstructor() || $session->isAdmin()){ ?>
							<li><a href="/addevent.php">Add Event</a></li>
							<li><a href="/mycourse.php">My Courses</a></li>
							<li><a href="/messages.php">Messages<?php if ($numUnreadMail > 0){echo " ($numUnreadMail)"; } ?></a></li>
						<?php } ?>
						<li><a href="/index.php">Calendar</a></li>
						<li><a href="/process.php">Logout</a></li>
						<?php
							} else {
						?>
						
						<li><a href="/login.php">Faculty Login</a></li>
					<?php } ?>
				</ul>
				
			</div>
		</nav>
		<?php
			/**
				* User has already logged in, so display relavent links, including
				* a link to the admin center if the user is an administrator.
			*/
			if($session->logged_in || isset($_GET['cwid'])){ ?>
			<div class="card card-2">
				<h6><strong>Logged In.</strong></h6>
				Welcome <strong><?php echo $session->username; ?></strong>, you are logged in.
				<?php
					echo '<a href="javascript:window.print()"><img src="/img/print.png" alt="Print" id="print-button" /></a>';
				?>
			</div>
			<?php } 
			if ($page == "index.php"){
			?>
			<div class="card card-3" style="
			margin-left: 5%;
			margin-right: 5%;
			padding: 1em 3em;
			text-align: center;
			font-size: 18px;
			bottom: -28px;
			background-color: gainsboro;
			">
				<?php
					echo "<div id='viewsleft'>";
				echo "Calendar Views"; ?>:<br>&nbsp;&nbsp;&nbsp;
				<?php
					$sem = $_GET['sem'];
					$studentCWID = $_GET['cwid'];
					$rm = $_GET['rm'];
					$cm = $_GET['cm'];
					$q = "SELECT module_id, link_name from modules where active = 1 order by sequence";
					$query = mysql_query($q);
					if (!$query) $msg .= "Database Error : ".$q;
					else {
						$i = false;
						while($row = mysql_fetch_row($query)) {
							if ($i == true) echo " | ";
							echo "<a href=\"index.php?o=".$row[0]."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."&sem=".$sem."&cwid=".$studentCWID."&rm=".$rm."&cm=".$cm."\"";
							if ($o == $row[0]) echo " class=\"selected\"";
							echo ">".$row[1]."</a>";
							$i = true;
						}
					}	
					echo "</div><div id='viewsright'>";
					if ($session->isAdmin()){
						if (isset($cm) && $cm != NULL && $cm != 0){
							$filter = "Viewing Pending Mode";
						} else if (isset($rm) && $rm != NULL && $rm != 0){
						$filter = "Viewing Room: ".$rm;
						} else if (isset($sem) && $sem != NULL && $sem != 0){
						$filter = "Viewing Semester: ".$sem;
					} else {
						$filter = "Filter";
					}
					echo '
					<div class="input-field col s12">
					<select name="option" class="drop wid">
					<option value="" disabled selected>'.$filter.'</option>
					<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=0">View My Courses</option>
					<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=1">View Semester 1 </option>
					<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=2">View Semester 2 </option>
					<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=3">View Semester 3 </option>
					<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=4">View Semester 4 </option>
					<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=5">View Semester 5 </option>
					<option value="/roomview.php">View by Room</option>
					<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&cm=1">View Pending Only</option>
					</select>
					</div>
					';
					} else if ($session->isInstructor()){
					echo '
					<div class="input-field col s12">
					<select name="option" class="drop wid">
					<option value="" disabled selected>Filter</option>
					<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=0">View My Courses</option>';
					$q = "select semester, course_number, CRN from ".TBL_COURSE." natural join ".TBL_CRN." where Lead_Instructor = $session->CWID or instructor = $session->CWID GROUP BY semester ORDER BY semester ASC";
					$result = mysql_query($q);
					
					for ($i=0; $i < mysql_num_rows($result); $i++){
						$num = mysql_result($result,$i,"semester");
						echo '<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem='.$num.'">View Semester '.$num.'</option>';
					}
					echo '<option value="/roomview.php">View by Room</option></select></div>';	
					}
					if (!$session->isAdmin() && !$session->isInstructor()){
						echo "<a href='/class_select.php?cwid=".$_GET['cwid']."'>Change your courses</a>";
					}
					echo "</div>";
				}
			?>
		</div>
		<script>
			$( document ).ready(function(){
				$(".button-collapse").sideNav();
				
			})
		</script>
		
	<main>	