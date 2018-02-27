
<?php
include("header.php");
$page = "main.php";
if(!$session->logged_in){
	header("Location: /class_select.php");
} else {
header("Location: /index.php"); 
}
	
	?>




</body>
</html>
