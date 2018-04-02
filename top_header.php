<?php
	include("include/session.php");
?>
<html>
	
	<head>
		<!--Import Google Icon Font-->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Import materialize.css-->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
		
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.js"></script>
		
		<link rel="stylesheet" type="text/css" href="css/userinfo.css">
		<link rel="stylesheet" type="text/css" href="css/navbar.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
		<script type="text/javascript">
			jQuery(function($){
				<?php
					if(isset($_GET['hash'])){
						$hash = $_GET['hash'];
						} else {
						$hash = '';
					}
				?>
				jp_hash = ('<?php echo $hash; ?>'.length)?'<?php echo $hash; ?>':window.location.hash;
				if(jp_hash){
					$.ajax({
						type: "POST",
						url: 'process.php',
						data: 'login_with_hash=1&hash='+jp_hash,
						success: function(msg){
							if(msg){
								alert(msg);
								window.location.href = "main.php";
								} else {
								alert("Invalid Hash");
							}
						}
					});
				}
			});
		</script>
		<link rel="stylesheet" type="text/css" href="/css/calendar.css">
		<script language="JavaScript" src="/js/CalendarPopup.js"></script>
		<script language="JavaScript">document.write(getCalendarStyles());</script>
		<script language="JavaScript" src="/js/ColorPicker2.js"></script>
		<script language="JavaScript" src="/js/miscfunctions.js"></script>
		<script language="JavaScript" src="/js/miscfunctions.js"></script>
	</head>
	
	<body>
		
		<nav>
			<div class="nav-wrapper">
				<a href="#!" class="brand-logo"><img src="ulmlogo.png" alt="logo" class="logo" ></a>
				<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
				<ul class="right hide-on-med-and-down">
					<?php
						/**
							* User has already logged in, so display relavent links, including
							* a link to the admin center if the user is an administrator.
						*/
						if($session->logged_in){ ?> 
						<li><a href="userinfo.php?user=<?php echo $session->username; ?>">My Account</a></li>
						<li><a href="">Edit Account</a></li>
						<?php
							if($session->isAdmin()){
							?>
							<li><a href="/admin/admin.php">Admin Center</a></li>
							<?php } 
							if ($session->isInstructor() || $session->isAdmin()){ ?>
							<li><a href="/addevent.php">Add Event</a></li>
							<li><a href="/mycourse.php">My Courses</a></li>
						<?php } ?>
						<li><a href="index.php">Calendar</a></li>
						<li><a href="process.php">Logout</a></li>
						<?php
							} else {
						?>
						
						<li><a href="login.php">Login</a></li>
					<?php } ?>
					
				</ul>
				<ul class="side-nav" id="mobile-demo">
					<?php
						/**
							* User has already logged in, so display relavent links, including
							* a link to the admin center if the user is an administrator.
						*/
						if($session->logged_in){ ?> 
						<li><a href="userinfo.php?user=<?php echo $session->username; ?>">My Account</a></li>
						<li><a href="">Edit Account</a></li>
						<?php
							if($session->isAdmin()){
							?>
							<li><a href="/admin/admin.php">Admin Center</a></li>
							<?php }
							if ($session->isInstructor() || $session->isAdmin()){ ?>
							<li><a href="/addevent.php">Add Event</a></li>
							<li><a href="/mycourse.php">My Courses</a></li>
						<?php } ?>
						<li><a href="index.php">Calendar</a></li>
						<li><a href="process.php">Logout</a></li>
						<?php
							} else {
						?>
						
						<li><a href="login.php">Login</a></li>
					<?php } ?>
				</ul>
				
			</div>
		</nav>
		<?php
			/**
				* User has already logged in, so display relavent links, including
				* a link to the admin center if the user is an administrator.
			*/
			if($session->logged_in){ ?>
			<div class="card card-2">
				<h6><strong>Logged In.</strong></h6>
				Welcome <strong><?php echo $session->username; ?></strong>, you are logged in.
			</div>
		<?php } ?>
		<!--Import jQuery before materialize.js-->
		<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.js"></script>
		<script>
			$( document ).ready(function(){
				$(".button-collapse").sideNav();
				
			})
		</script>
		
		