		<link rel="stylesheet" type="text/css" href="admin.css">
<?php
	include("../top_header.php");
	
	/**
		* User not an administrator, redirect to main page
		* automatically.
	*/
	if(!$session->isAdmin()){
		header("Location: ../index.php");
	}
	else{
		/**
			* Administrator is viewing page, so display all
			* forms.
		*/
	?>
	<div class="card card1">
	<div class="col s12 box">
	1. Archive previous semester & remove data from previous semester <br>
							<form action="adminprocess.php" method="POST" id="archive">
							<input type="hidden" name="subarchive" value="1">
							<button class="btn waves-effect waves-light" type="submit" onclick= "confirmButton()">Archive & Reset
								<i class="material-icons right">send</i>
							</button>
						</form>
						<hr>
	2. Create semester deadline and schedule deadline <br>
	
								<h5>Set Deadlines</h5>
							<form id="deadlines" action="adminprocess.php" method="POST">
								<div class="row col s12" >
									<input type="text" placeholder="Date-open" class="datepicker" name="dateOpen"type="date" value="<?php echo $form->value("dateOpen"); ?>"><?php echo $form->error("dateOpen"); ?>
									<input type="text" placeholder="Date-close" class="datepicker" name="dateClose"value="<?php echo $form->value("dateClose"); ?>"><?php echo $form->error("dateClose"); ?>
									
									<p><select placeholder="type" form="deadlines" name="type" maxlength="30" value="<?php echo $form->value("type"); ?>"><?php echo $form->error("type"); ?>
										<option value='schedule'>Schedule</option>
										<option value='semester'>Semester</option>
									</select></p>
									
									<input type="hidden" name="subdeadline" value="1">
									<button class="btn waves-effect waves-light" type="submit" name="action" value="Set Deadline">Set Deadline
										<i class="material-icons right">send</i>
									</button>
									
								</div>
								<br>
								<hr class="style13">
							</form>
	3. Add in new semester's sections & their instructors <br>
	<h5>Add Section/CRN</h5>
										<br>
										<form action="adminprocess.php" method="POST" id="addsection"">
										<div class="input-field col s12">
											<select class="drop" form="lead" name="course">
												<?php
													$q = "SELECT * FROM ".TBL_COURSE."";
													$result = $database->query($q);
													$num_rows = mysql_numrows($result);
													for($i=0; $i<$num_rows; $i++){
														$num = mysql_result($result,$i,"course_number");
														$title = mysql_result($result,$i,"title");
														echo "<option value='".$num."'>".$num." - ".$title."</option>";
													}
												?>	
											</select>
											<label>Select a course</label>
										</div>
										<div class="input-field col s12">
											<input id="coursename" type="text" class="validate" name="crn" maxlength="30">
											<label for="coursename">CRN</label>
										</div>
										<input type="hidden" name="subaddsection" value="1">
										<button class="btn waves-effect waves-light" type="submit" >Add Section
											<i class="material-icons right">send</i>
										</button>
									</form>
															
						<h5>Assign instructor to section:</h5>
						<br>
						<form class="col s12" action="adminprocess.php" method="POST" id="instruct">
							
							<!select a instructor name dropdown>
							<div class="input-field col s12">
								<select class="drop" form="instruct" name="user">
									
									<?php
										$q = "SELECT * FROM ".TBL_USERS." WHERE userlevel >=5";
										$result = $database->query($q);
										$num_rows = mysql_numrows($result);
										for($i=0; $i<$num_rows; $i++){
											$username  = mysql_result($result,$i,"username");
											$cwid = mysql_result($result, $i, "CWID");
											echo "<option value='".$cwid."'>".$username."</option>";
										}
									?>  
								</select>
								<label>Select a instructor's username</label>
							</div>
							
							<!Select a course name dropdown>
							<div class="input-field col s12">
								<select name="crn[]" size=5 multiple>
									<option value="" disabled selected>Select sections to assign</option>
									<?php
										$q2 = "SELECT * FROM ".TBL_COURSE;
										$result2 = $database->query($q2);
										$num_rows2 = mysql_numrows($result2);
										for ($p=0; $p<$num_rows2; $p++){
											$c1 = mysql_result($result2,$p,"course_number");
											if ($c1 != 0){
												echo "<optgroup label=\"".$c1."\">";
												$q3 = "SELECT * FROM ".TBL_CRN." WHERE course_number = $c1";
												$result3 = $database->query($q3);
												$num_rows3 = mysql_numrows($result3);
												for($j=0; $j<$num_rows3; $j++){
													$crn  = mysql_result($result3,$j,"crn");
													echo "<option value='".$crn."'>".$crn."</option>";
												}
											}
										}
									?>	
								</select>
								<label>Select a course</label>
							</div>
							
							<input type="hidden" name="subinstruct" value="1">
							<button class="btn waves-effect waves-light" type="submit" >Add Instructor
								<i class="material-icons right">send</i>
							</button>
							
							<br>
							<hr class="style13">
							
						</form>
	4. Assign lead instructors <br>
	
	<h5>Assign Lead Instructor Role:</h5>
								<br>
								<form class="col s12" action="adminprocess.php" method="POST" id="lead">
								
								<!select a instructor name dropdown>
								<div class="input-field col s12">
									<select class="drop" form="lead" name="user">
										
										<?php
											$q = "SELECT * FROM ".TBL_USERS." WHERE userlevel >=5";
											$result = $database->query($q);
											$num_rows = mysql_numrows($result);
											for($i=0; $i<$num_rows; $i++){
												$username  = mysql_result($result,$i,"username");
												$cwid = mysql_result($result, $i, "CWID");
												echo "<option value='".$cwid."'>".$username."</option>";
											}
										?>  
									</select>
									<label>Select a instructor's username</label>
								</div>
								
								<!Select a course name dropdown>
								<div class="input-field col s12">
									<select class="drop" form="lead" name="leadcourse">
										<?php
											$q = "SELECT * FROM ".TBL_COURSE."";
											$result = $database->query($q);
											$num_rows = mysql_numrows($result);
											for($i=0; $i<$num_rows; $i++){
												$num = mysql_result($result,$i,"course_number");
												$title = mysql_result($result,$i,"title");
												echo "<option value='".$num."'>".$num." - ".$title."</option>";
											}
										?>  
									</select>
									<label>Select a course</label>
								</div>
								
								<input type="hidden" name="sublead" value="1">
								<button class="btn waves-effect waves-light" type="submit" >Add Lead Instructor
									<i class="material-icons right">send</i>
								</button>
								
								<br>
								<hr class="style13">
								
							</form>
							
	5. Finally, delete any unused professors, rooms, and courses, and add any additional new ones. <br>
	</div>
	</div>
	<?php
	}
	include "../footer.php";
?>