<!DOCTYPE html>
<?php
	include("top_header.php");
	$page = "class_select.php";
	
	global $database;
?>
<body>
	<fieldset>
		<legend>Student Login</legend>
		<p>
		<form action="process.php" method="POST" id="studentLogin">
			<div class="input-field col s12">
		<input type="text" name="CWID" maxlength="30"></p>
		<label for="CWID">CWID</label>
	</div>
	<input type="hidden" name="studentLogin" value="1">
	<input type="submit" value="Submit">
		</form>
		<?php
		if (isset($_GET['c'])){
		?>
			<form action="process.php" method="POST" id="choosesemester">
				<label>Choose Your Semester: </label><br/>
				<select form="choosesemester" name="semester">
					<option value="1">Semester 1</option>
					<option value="2">Semester 2</option>
					<option value="3">Semester 3</option>
					<option value="4">Semester 4</option>
					<option value="5">Semester 5</option>
				</select>
			</p>
			<input type="hidden" name="choosesemester" value="1">
			<input type="submit" value="Pick Courses">
		</form>
		<?php
			if (isset($_GET['sem'])){
				$sem = $_GET['sem']; 
			?>
			<form action="process.php" method="POST" id="choosecourse">
				<p>
					<label>Choose Courses: (press ctrl and click to select multiple courses) </label><br/>
					<select name="course[]" size=3 multiple>
						<?php
							$q = "SELECT * FROM ".TBL_COURSE." WHERE semester = $sem";
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$num = mysql_result($result,$i,"course_number");
								$title = mysql_result($result,$i,"title");
								echo "<option value='".$num."'>".$num." - ".$title."</option>";
							}
						?>	
					</select>
				</p>
				<input type="hidden" name="choosecourse" value="1">
				<input type="hidden" name="sem" value="<?php echo $sem; ?>">
				<input type="submit" value="Pick CRNs">
			</form>
			<?php
				if (isset($_GET['c'])){
					$courses[] = explode(" ", trim($_GET['c']));
					
					
				?>
				<form action="process.php" method="POST" id="choosecrn">
					<p>
						<label>CRN: </label><br/>
						
						
			            <select name="crn[]" size=5 multiple>
							<?php
								
								foreach ($courses as $c){
									foreach ($c as $c1){
										echo "<optgroup label=\"".$c1."\">";
										$q = "SELECT * FROM ".TBL_CRN." WHERE course_number = $c1";
										$result = $database->query($q);
										$num_rows = mysql_numrows($result);
										for($i=0; $i<$num_rows; $i++){
											$crn  = mysql_result($result,$i,"crn");
											echo "<option value='".$crn."'>".$crn."</option>";
										}
									}
								}
							?>	
						</select>
					</p> 
					<input type="hidden" name="choosecrn" value="1">
					<input type="submit" value="View Schedule">
				</form>
				<?php
				}
				}
			}
		?>
		
		
		
		
	</fieldset>
</form>
<?php
	include "footer.php";
	?>		