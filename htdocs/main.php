
<?php
include("header.php");
$page = "main.php";
if(!$session->logged_in){
	?>
		<h1>Student View</h1>
	<?php
   echo "[<a href=\"login.php\">Login</a>]";?></p><?php
}
?>
<div id='calendar'></div>




</body>
</html>
