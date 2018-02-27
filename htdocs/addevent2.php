<?php
	include("header.php");
	$page = "addevent2.php";
	if(!$session->isInstructor()){
		header("Location: main.php");
		} else {
		global $database;
	?>
	<div>
		<h1>Add Event - Pick location</h1>
		<form action="process.php" method="POST" id="addevent2">
			
			<p>Rooms Available at the specified date/time: </p><p><select form="addevent2" name="room" maxlength="30" value="<?php echo $form->value("room"); ?>"><?php echo $form->error("room"); ?></p>
				<?php
					$datetimeStart = "".$_GET['d']." ".$_GET['st'].":00";
					$datetimeEnd = "".$_GET['d']." ".$_GET['et'].":00";
					echo "Start: ".$datetimeStart." End: ".$datetimeEnd."";
					$q = "SELECT * FROM ".TBL_ROOMS." WHERE NOT EXISTS (SELECT * FROM ".TBL_ROOMS.", ".TBL_EVENTS." where ".TBL_EVENTS.".dateStart >= STR_TO_DATE('$dateStart', '%Y-%m-%d %H:%i:%s') AND ".TBL_EVENTS.".dateStart <= STR_TO_DATE('$dateEnd', '%Y-%m-%d %H:%i:%s') AND ".TBL_ROOMS.".id = ".TBL_EVENTS.".room)";
					
					$result = $database->query($q);
					
					$num_rows = mysql_numrows($result);
					for($i=0; $i<$num_rows; $i++){
						$id  = mysql_result($result,$i,"id");
						$room  = mysql_result($result,$i,"number");
						echo "<option value='".$id."'>".$room."</option>";
					}
					
				?>		
			</select>
			<p>
				<input type="hidden" name="subAdd2" value="1">
				
				<input type="hidden" name="title" value="<?php echo $_GET['t']; ?>">
				<input type="hidden" name="type" value="<?php echo $_GET['ty']; ?>">
				<input type="hidden" name="course" value="<?php echo $_GET['c']; ?>">
				<input type="hidden" name="crn" value="<?php echo $_GET['crn']; ?>">
				<input type="hidden" name="seats" value="<?php echo $_GET['s']; ?>">
				<input type="hidden" name="notes" value="<?php echo $_GET['n']; ?>">
				<input type="hidden" name="dateStart" value="<?php echo $datetimeStart; ?>">
				<input type="hidden" name="dateEnd" value="<?php echo $datetimeStart; ?>">
				
				<input type="submit" value="Add Event">
			</p>
		</form>
		<a href="javascript:history.back();"><input type="button" value="Go back"></a>
		
	</div>
	
</body>
</html>
<?php
}
?>