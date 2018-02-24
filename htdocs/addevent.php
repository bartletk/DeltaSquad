<?php
include("header.php");
$page = "addevent.php";
if(!$session->isInstructor()){
	header("Location: main.php");
} else {
	   global $database;
?>
<div>
<h1>Add Event</h1>
<?php
if($form->num_errors > 0){
   echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
}
?>

	<form action="process.php" method="POST" id="addevent">
		<p>Title: </p><p><input type="text" name="title" maxlength="30" value="<?php echo $form->value("title"); ?>"><?php echo $form->error("title"); ?></p>

		<p>Event Type: </p><p><select form="addevent" name="type" maxlength="30" value="<?php echo $form->value("type"); ?>"><?php echo $form->error("type"); ?></p>
<?php
   $q = "SELECT * "
       ."FROM ".TBL_TYPES." ";
   $result = $database->query($q);
   $num_rows = mysql_numrows($result);
   for($i=0; $i<$num_rows; $i++){
      $id  = mysql_result($result,$i,"id");
      $title = mysql_result($result,$i,"title");
      echo "<option value='".$id."'>".$title."</option>";
   }
?>
		</select>
		<p>Course: </p><p><select form="addevent" name="course" maxlength="30" value="<?php echo $form->value("course"); ?>"><?php echo $form->error("course"); ?></p>
<?php
   $q = "SELECT * "
       ."FROM ".TBL_COURSE." ";
   $result = $database->query($q);
   $num_rows = mysql_numrows($result);
   for($i=0; $i<$num_rows; $i++){
      $prefix  = mysql_result($result,$i,"prefix");
      $number = mysql_result($result,$i,"number");
      echo "<option value='".$id."'>".$title."</option>";
   }
?>		
		</select>
		<p>CRN: </p><p><select form="addevent" name="crn" maxlength="30" value="<?php echo $form->value("crn"); ?>"><?php echo $form->error("crn"); ?></p>
		</select>
<?php
   global $database;
   $q = "SELECT * "
       ."FROM ".TBL_COURSE." ";
   $result = $database->query($q);
   $num_rows = mysql_numrows($result);
   for($i=0; $i<$num_rows; $i++){
      $prefix  = mysql_result($result,$i,"prefix");
      $number = mysql_result($result,$i,"number");
      echo "<option value='".$id."'>".$title."</option>";
   }
?>			
		
		<p>Seats Needed: </p><p><input type="text" name="seats" maxlength="30" value="<?php echo $form->value("seats"); ?>"><?php echo $form->error("seats"); ?></p>
		<p>Notes: </p><p><input type="text" name="notes" maxlength="255" value="<?php echo $form->value("notes"); ?>"><?php echo $form->error("notes"); ?></p>	
		
		<p>
			<input type="hidden" name="subAdd" value="1">
			<input type="submit" value="Pick Time/Location">
		</p>
	</form>
</div>

</body>
</html>
<?php
}
?>