<?php
	
	include("include/session.php");
	
?>
<html>
	
	<head>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="css/other.css">
		<link rel="stylesheet" type="text/css" href="../css/other.css">
		<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.js"></script>
		<link rel="stylesheet" type="text/css" href="css/addevent.css">
		<link rel="stylesheet" type="text/css" href="css/userinfo.css">
		<link rel="stylesheet" type="text/css" href="css/navbar.css">
		<link rel="stylesheet" type="text/css" href="css/loginAndRegister.css">
		<link rel="stylesheet" type="text/css" href="css/mycourse.css">
		<link rel="stylesheet" type="text/css" href="css/useredit.css">
		<link rel="stylesheet" type="text/css" href="css/form.css">
		<link rel="stylesheet" type="text/css" href="css/roomview.css">
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
	</head>
	
	<body>
		<?php
		$q = "SELECT mail_id FROM ".TBL_MAIL." WHERE UserTo = '$session->username' and status = 'unread'";
						$numUnreadMail = $database->query($q) or die(mysql_error());
						$numUnreadMail = mysql_num_rows($numUnreadMail);
						?>
		<nav>
			<div class="nav-wrapper">
				<a href="#!" class="brand-logo"><img src="/img/ulmlogo.png" alt="logo" class="logo" ></a>
				<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
				<ul class="right hide-on-med-and-down">
					<?php
						/**
							* User has already logged in, so display relavent links, including
							* a link to the admin center if the user is an administrator.
						*/
						if($session->logged_in){ ?> 
						<li><a href="useredit.php">Edit Account</a></li>
						<?php
							if($session->isAdmin()){
							?>
							<li><a href="/admin/admin.php">Admin Center</a></li>
							<?php } 
							if ($session->isInstructor() || $session->isAdmin()){ ?>
							<li><a href="/addevent.php">Add Event</a></li>
							<li><a href="/mycourse.php">My Courses </a></li>
							<li><a href="/mail.php">Messages<?php if ($numUnreadMail > 0){echo " ($numUnreadMail)"; } ?></a></li>
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
						<li><a href="useredit.php">Edit Account</a></li>
						<?php
							if($session->isAdmin()){
							?>
							<li><a href="/admin/admin.php">Admin Center</a></li>
							<?php }
							if ($session->isInstructor() || $session->isAdmin()){ ?>
							<li><a href="/addevent.php">Add Event</a></li>
							<li><a href="/mycourse.php">My Courses</a></li>
							<li><a href="/mail.php">Messages<?php if ($numUnreadMail > 0){echo " ($numUnreadMail)"; } ?></a></li>
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
				<?php
				if ($page == "index.php"){
					echo '<a href="javascript:window.print()"><img src="/img/print.png" alt="Print" id="print-button" /></a>';
					}
				?>
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
		
		