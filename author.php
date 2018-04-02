<?php
	include("include/session.php");
	
	if(!$session->isInstructor()){
		header("Location: main.php");
		} else {
	?>
	
	
	<html>
		<head>
			<title>Delta Squad Nursing Scheduler/Calendar</title>
		</head>
		<body>
			
			
			<div>
				<p>you have instructor privileges</p>
			</div>
			
		</body>
	</html>
	<?php
	}
?>