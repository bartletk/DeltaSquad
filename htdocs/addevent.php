<!DOCTYPE html>
<html>
<head>
	 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" type="text/css" href="css/addevent.css">

	<title></title>
</head>
<body>

</body>
</html>
<?php
	// When uncommented, the php doesnot shows
	//include("top_header.php");
	include("header.php");
	$page = "addevent.php";
	if(!$session->isInstructor() && !$session->isAdmin()){
		header("Location: index.php");
		} else {
		global $database;
		
		
		
		$q = "select CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END AS tail FROM ".TBL_DEADLINES." WHERE (CURRENT_TIME() BETWEEN open AND close) AND type='schedule'";
		$result = $database->query($q);
		$tail = mysql_result($result,0,"tail");
		if ($tail == 0 || $session->isAdmin()) {
		?>
		<div class="Card card1">

<h5><strong>Add Event</strong></h5>

<div class="row">
    <form class="col s12">
			<?php
				if($form->num_errors > 0){
					echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
				}
			?>
		
			<form action="process.php" method="POST" class="col s12" id="addevent">
				

				<!-- gg-->

<div class="row">
	<div class="input-field col s12">
		<input type="text" name="title" maxlength="30" value="<?php echo $_GET['t']; ?>"></p>
		<label for="title">Title</label>
	</div>

	<label for="eveny_type">Event Type:<label>

	<p>
	<input type="radio" id="class1" name="type" value="0" <?php if (isset($_GET['ty']) && $_GET['ty'] == 0){echo "checked";} ?> />
	<label for="class1">Class</label>
	</p>
	<p>
	<input type="radio" id="Clinical" name="type" value="1" <?php if (isset($_GET['ty']) && $_GET['ty'] == 1){echo "checked";} ?> />
	<label for="Clinical">Clinical</label>
	</p>
	<p>
	<input type="radio" id="exam" name="type" value="2" <?php if (isset($_GET['ty']) && $_GET['ty'] == 2){echo "checked";} ?> />
	<label for="exam">Exam</label>
	</p>
	<p>
	<input type="radio" id="event" name="type" value="3" <?php if (isset($_GET['ty']) && $_GET['ty'] == 3){echo "checked";} ?>  />
	<label for="event">Event</label>
	</p>

</div>



 <div class="row">
        <div class="input-field col s12">
          <input placeholder="seats needed" id="seats" type="text"  maxlength="30" value="<?php echo $_GET['s']; ?>">
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input placeholder="note" id="notes" type="text" name="notes" maxlength="255" value="<?php echo $_GET['n']; ?>" >
        </div>
      </div>
      <div class="row col s12" >
        <input type="text" placeholder="date" class="datepicker" value="<?php echo $_GET['d']; ?>">
        
      </div>

      <div class="row col s12">
         <input placeholder="start time" type="text" class="timepicker" name="starttime" maxlength="30" value="<?php echo $_GET['st']; ?>">
        
      </div>

      <div class="row col s12">
         <input placeholder="end time" type="text" class="timepicker" name="endtime" maxlength="30" value="<?php echo $_GET['et']; ?>">
        
      </div>


    <div>
      
      <p>
      <input type="checkbox" id="repeat" name="repeat" maxlength="30" value="1" <?php if ($_GET['repeat']== 1){echo "checked";}?> />
      <label for="repeat">check if you want to repeat</label>
      </p>

      <p>
      <input type="checkbox" name="repeatm" maxlength="30" value="1" <?php if ($_GET['repeatm'] == 1){echo "checked";}?> id="repeatm" />
      <label for="repeatm">Monday</label>
      </p>

      <p>
      <input type="checkbox" name="repeatt" maxlength="30" value="1" <?php if ($_GET['repeatt']== 1){echo "checked";}?> id="repeatt" />
      <label for="repeatt">Tuesday</label>
      </p>

      <p>
      <input type="checkbox" name="repeatw" maxlength="30" value="1" <?php if ($_GET['repeatt']== 1){echo "checked";}?> id="repeatw" />
      <label for="repeatw">Wednesday</label>
      </p>

      <p>
      <input type="checkbox" name="repeatth" maxlength="30" value="1" <?php if ($_GET['repeatt']== 1){echo "checked";}?> id="repeatth"/>
      <label for="repeatth">Thursday</label>
      </p>

      <p>
      <input type="checkbox" name="repeatf" maxlength="30" value="1" <?php if ($_GET['repeatt']== 1){echo "checked";}?> id="repeatf"/>
      <label for="repeatf">Friday</label>
      </p>


 	</div>

 	<br>
  <div>
    <div class="row col s12" >
        <input name="re" value="<?php echo $_GET['re']; ?>" type="text" placeholder="Repeat Until" class="datepicker">
        
      </div>


  </div>

<br>

<!--gg-->			
<!--dropdown to course-->
      <div class="input-field col s12">
    <select form="addevent" name="course" maxlength="30" value="<?php echo $_GET['c']?>">
	<?php
				$q = "SELECT * FROM ".TBL_COURSE;
				$result = $database->query($q);
				$num_rows = mysql_numrows($result);
				for($i=0; $i<$num_rows; $i++){
					$num = mysql_result($result,$i,"course_number");
					$title = mysql_result($result,$i,"title");
					echo "<option value='".$num."'>".$num." - ".$title."</option>";
				}
			?>		
		</select>

    <label>Course</label>
  </div>

  <input type="hidden" name="addeventA" value="1">
<button class="btn waves-effect waves-light" type="submit" name="action">Pick CRN
    <i class="material-icons right">send</i>
  </button>
  <hr>
  <hr>

 <!--dropdown to select crn-->	
</form>


<?php
if (isset($_GET['c'])){
	$courses[] = explode(" ", trim($_GET['c']));
	
	
?>

<form action="process.php" method="POST" id="addeventB">
 <div class="input-field col s12">
    <select name="crn[]" size=5 multiple>
<?php
		$q = "SELECT * FROM ".TBL_CRN." WHERE course_number = ".$_GET['c'];
		$result = $database->query($q);
		$num_rows = mysql_numrows($result);
		for($i=0; $i<$num_rows; $i++){
			$crn  = mysql_result($result,$i,"crn");
			echo "<option value='".$crn."'>".$crn."</option>";
			
			
		}
	?>	
</select>
    <label>CRN</label>

	<input type="hidden" name="title" value="<?php echo $_GET['t']?>">
	<input type="hidden" name="type" value="<?php echo $_GET['ty']?>">
	<input type="hidden" name="course" value="<?php echo $_GET['c']?>">
	<input type="hidden" name="seats" value="<?php echo $_GET['s']?>">
	<input type="hidden" name="notes" value="<?php echo $_GET['n']?>">
	<input type="hidden" name="date" value="<?php echo $_GET['d']?>">
	<input type="hidden" name="starttime" value="<?php echo $_GET['st']?>">
	<input type="hidden" name="endtime" value="<?php echo $_GET['et']?>">
	<input type="hidden" name="repeat" value="<?php echo $_GET['repeat']?>">
	<input type="hidden" name="repeatm" value="<?php echo $_GET['repeatm']?>">
	<input type="hidden" name="repeatt" value="<?php echo $_GET['repeatt']?>">
	<input type="hidden" name="repeatw" value="<?php echo $_GET['repeatw']?>">
	<input type="hidden" name="repeatth" value="<?php echo $_GET['repeatth']?>">
	<input type="hidden" name="repeatf" value="<?php echo $_GET['repeatf']?>">
	<input type="hidden" name="re" value="<?php echo $_GET['re']?>">
	<input type="hidden" name="addeventB" value="1">
  </div>
  <button class="btn waves-effect waves-light" type="submit" name="action">Pick Room
    <i class="material-icons right">send</i>
  </button>
<hr>
<hr>
</form>
<?php 
}
	if (isset($_GET['crn'])){
?>

<form action="process.php" method="POST" id="addeventC">
 <div class="input-field col s12">
    <select form="addeventC" name="room" maxlength="30" value="<?php echo $form->value("room"); ?>"><?php echo $form->error("room"); ?></p>
						<?php
							$datetimeStart = "".$_GET['d']." ".$_GET['st'].":00";
							$datetimeEnd = "".$_GET['d']." ".$_GET['et'].":00";
							$q = "SELECT DISTINCT room_number FROM ".TBL_ROOMS." WHERE room_number = 'Offsite' OR NOT EXISTS (SELECT * FROM ".TBL_ROOMS.", ".TBL_EVENTS." where ".TBL_EVENTS.".dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND ".TBL_EVENTS.".dateStart <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s') AND ".TBL_ROOMS.".room_number = ".TBL_EVENTS.".room_number)";
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$room  = mysql_result($result,$i,"room_number");
								echo "<option value='".$room."'>".$room."</option>";
							}
							
						?>
						
					</select>
    <label>Room</label>
  </div>
  
  	<?php
							$q = sprintf("select MAX(series) AS Max from ".TBL_EVENTS." where series<9000");
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$max  = mysql_result($result,$i,"Max")+1;
								echo "<input type='hidden' name='series' value='".$max."'>";
							}
						?>
						<input type="hidden" name="addeventC" value="1">
						<input type="hidden" name="title" value="<?php echo $_GET['t']; ?>">
						<input type="hidden" name="type" value="<?php echo $_GET['ty']; ?>">
						<input type="hidden" name="course" value="<?php echo $_GET['c']; ?>">
						<input type="hidden" name="crn" value="<?php echo $_GET['crn']; ?>">
						<input type="hidden" name="seats" value="<?php echo $_GET['s']; ?>">
						<input type="hidden" name="notes" value="<?php echo $_GET['n']; ?>">
						<input type="hidden" name="dateStart" value="<?php echo $datetimeStart; ?>">
						<input type="hidden" name="dateEnd" value="<?php echo $datetimeEnd; ?>">
						<input type="hidden" name="repeat" value="<?php echo $_GET['repeat']?>">
						<input type="hidden" name="repeatm" value="<?php echo $_GET['repeatm']?>">
						<input type="hidden" name="repeatt" value="<?php echo $_GET['repeatt']?>">
						<input type="hidden" name="repeatw" value="<?php echo $_GET['repeatw']?>">
						<input type="hidden" name="repeatth" value="<?php echo $_GET['repeatth']?>">
						<input type="hidden" name="repeatf" value="<?php echo $_GET['repeatf']?>">
						<input type="hidden" name="re" value="<?php echo $_GET['re']?>">
					

  <button class="btn waves-effect waves-light" type="submit" name="action">Add Event
    <i class="material-icons right">send</i>
  </button>								
	</form>
	<?php

	}
	} else {
	echo "This form is not available at the current time. Requests will be implemented later. We apologize for the inconvenience.";
?>
</div>
 </div>



  <!--Import jQuery before materialize.js-->
  <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        
          <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

          <script>
              $( document ).ready(function(){
                $(".button-collapse").sideNav();


               $('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 15, // Creates a dropdown of 15 years to control year,
    today: 'Today',
    clear: 'Clear',
    close: 'Ok',
    closeOnSelect: false // Close upon selecting a date,


  })
               $('.timepicker').pickatime({
    default: 'now',
    twelvehour: true, // change to 12 hour AM/PM clock from 24 hour
    donetext: 'OK',
  autoclose: false,
  vibrate: true // vibrate the device when dragging clock hand
})
   $(document).ready(function() {
    $('select').material_select();
  });
        
                });


          </script>
	</body>
	</html>
	<?php
	} 
	}
	

	?>							