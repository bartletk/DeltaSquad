<?php
	$referrer = $_GET['ref'];
	?>

	<body>
	<head>
	<script type="text/javascript">
    var timeleft = 3;
    var downloadTimer = setInterval(function(){
    timeleft--;
    document.getElementById("countdowntimer").textContent = timeleft;
    if(timeleft <= 0)
        clearInterval(downloadTimer);
    },1000);
</script>
		<link rel = "stylesheet" type = "text/css" href = "/css/success.css">
		<style>
			<link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
		</style>
	</head>
			<div id= "text">Success</div> 
			<div id="text2"><br>Returning in <span id="countdowntimer">3</span> seconds.</div>

	
	
	</body>
	<?php
	header( "refresh:3;url=$referrer" );
	?>