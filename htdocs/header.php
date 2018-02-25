<?php
include("include/session.php");
?>

<html>
<head>
	<title>Delta Squad Nursing Scheduler/Calendar</title>
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
<?php if ($_REQUEST["size"] == "small") echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/calendar-small.css\">\n"; ?>
<?php if ($css) echo $css; ?>


<script language="JavaScript" src="/js/CalendarPopup.js"></script>
<script language="JavaScript">document.write(getCalendarStyles());</script>
<script language="JavaScript" src="/js/ColorPicker2.js"></script>
<script language="JavaScript" src="/js/miscfunctions.js"></script>
<?php if ($javascript) echo $javascript; ?>
</head>
<body>

<div>


<?php
/**
 * User has already logged in, so display relavent links, including
 * a link to the admin center if the user is an administrator.
 */
if($session->logged_in){
	if(MAIL){
		$q = "SELECT mail_id FROM ".TBL_MAIL." WHERE UserTo = '$session->username' and status = 'unread'";
		$numUnreadMail = $database->query($q) or die(mysql_error());
		$numUnreadMail = mysql_num_rows($numUnreadMail);

		echo "<div><p class='right'>[<a href=\"mail.php\">You have $numUnreadMail Unread Mail</a>]&nbsp;</p></div>";
	}
	?>
		<h1>Logged In</h1>
		<p>Welcome <b><?php echo $session->username; ?></b>, you are logged in.</p>
		<p>[<a href="userinfo.php?user=<?php echo $session->username; ?>">My Account</a>]&nbsp;[<a href="useredit.php">Edit Account</a>]
	<?php
   if($session->isAdmin()){
      echo "[<a href=\"admin/admin.php\">Admin Center</a>]&nbsp;[<a href=\"addevent.php\">Add Event</a>]&nbsp;";
   }
   echo "[<a href=\"process.php\">Logout</a>]";?></p><?php
}
?>
</div>
<div class="nav">
<?php include "includes/nav.php"; ?>
</div>