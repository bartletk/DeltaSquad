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
		
				<p>Rooms Available at the specified date/time: </p><p><select form="addevent" name="room" maxlength="30" value="<?php echo $form->value("room"); ?>"><?php echo $form->error("room"); ?></p>
		<?php
		$datetimeStart = "".$_GET['d']." ".$_GET['st'].":00";
		$datetimeEnd = "".$_GET['d']." ".$_GET['et'].":00";
		echo "Start: ".$datetimeStart." End: ".$datetimeEnd."";
   $q = "SELECT * FROM ".TBL_ROOMS.", ".TBL_EVENTS." WHERE ".TBL_ROOMS.".id = ".TBL.EVENTS.".room AND NOT EXISTS ( SELECT * FROM ".TBL_ROOMS.", ".TBL_EVENTS." where ".TBL_EVENTS.".dateStart >= STR_TO_DATE('$datetimeStart', '%Y-%m-%d %H:%i:%s') AND ".TBL_EVENTS.".dateStart <= STR_TO_DATE('$datetimeEnd', '%Y-%m-%d %H:%i:%s'))";
   $result = $database->query($q);
   $results = mysql_fetch_array($result);
   $num_rows = mysql_numrows($result);
   for($i=0; $i<$num_rows; $i++){
      $id  = mysql_result($results,$i,"id");
	  $room  = mysql_result($results,$i,"number");
      echo "<option value='".$id."'>".$room."</option>";
   }

?>		
		</select>
		<p>
			<input type="hidden" name="subAdd2" value="1">
			<input type="submit" value="Add Event">
		</p>
	</form>
	<?php
	   echo "Start : ".$datetimeStart." End: ".$datetimeEnd." Query: ";
   print_r($results); print_r($result); echo "NO";
   ?>
</div>

</body>
</html>
<?php
}
?>