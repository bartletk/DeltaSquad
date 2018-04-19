<!DOCTYPE html>
<html>
	<head>
		<!--Import Google Icon Font-->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Import materialize.css-->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
		
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		<link rel="stylesheet" type="text/css" href="admin.css">
		
		
		<title>Delta Squad Nursing Scheduler/Calendar</title>
		<style>
			h3{
			text-align:center !important;
			color:#600 !important;
			padding: 20px !important;
			font-weight: 1000 !important;
			}
			
			.card1{
			margin:40px !important;
			margin-top:40px !important;
			} 
			
		</style>
	</head>
	<?php
		include("../top_header.php");
	?>
	<body>
		
		
		<?php
			
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
			<main>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				<div>
					<div class="card" "card1">
						<h3>Admin Center</h3>
					</div>
					
					
					
					
					
					<!three Tabs for admin functionality>
					
					<div class="card card1">
						<div class="row">
							<div class="col s12">
								<ul class="tabs">
									<li class="tab col s3"><a href="#test1">User</a></li>
									<li class="tab col s3"><a class="active" href="#test2">Deadline</a></li>
									<li class="tab col s3"><a href="#test4">Room</a></li>
									<li class="tab col s3"><a href="#test5">Course</a></li>
								</ul>
							</div>
							
							<!User edit tab>
							<div id="test1" class="col s12">
								
								
								
								<h5>Update User Level</h5>
								
								<form action="adminprocess.php" method="POST" id="update" >
									
									<!select a instructor name dropdown>
									<div class="input-field col s12">
										<select form="update" name="upduser">
											<?php
												$q = "SELECT * FROM ".TBL_USERS;
												$result = $database->query($q);
												$num_rows = mysql_numrows($result);
												for($i=0; $i<$num_rows; $i++){
													$username  = mysql_result($result,$i,"username");
													echo "<option value='".$username."'>".$username."</option>";
												}
											?>  
										</select>
										<label>Select username</label>
									</div>
									
									<!Select a Level dropdown>
									<div class="input-field col s12">
										<select>
											<option value="1">Student</option>
											<option value="2">Instructor</option>
											<option value="3">Administrator</option>
										</select>
										<label>Select level</label>
									</div>
									<input type="hidden" name="subupdlevel" value="1">
									<button class="btn waves-effect waves-light" type="submit" value="Update Level">Update Level
										<i class="material-icons right">send</i>
									</button>
									
									<br>
									<hr class="style13">
									
								</form>
								
								
								
								
								
								
								<!delete user form>
								
								<h5>Delete User</h5>
								
								<form action="adminprocess.php" method="POST" id="delete">
									
									<! dropdown to select user to delete>
									
									
									<div class="input-field col s12">
										<select form="delete" name="deluser">
											<?php
												$q = "SELECT * FROM ".TBL_USERS;
												$result = $database->query($q);
												$num_rows = mysql_numrows($result);
												for($i=0; $i<$num_rows; $i++){
													$username  = mysql_result($result,$i,"username");
													echo "<option value='".$username."'>".$username."</option>";
												}
											?>  
										</select>
										<label>Username</label>
									</div>
									<input type="hidden" name="subdeluser" value="1">
									<button class="btn waves-effect waves-light" type="submit" name="action" value="Delete User">Delete User
										<i class="material-icons right">send</i>
									</button>
									
								</form>
								<br>
								<hr class="style13">
								
								
								
								<!add user form>
								<br>
								<h5> Add User </h5>
								<form action="adminprocess.php" method="POST">
									<div class="row">
										
										<div class="row">
											
											<div class="input-field col s12">
												<input id="name" type="text" class="validate" name="name" maxlength="30" value="<?php echo $form->value("name"); ?>"><?php echo $form->error("name"); ?>
												<label for="name">Name</label>
											</div>
											
										</div>
										
										<div class="row">
											
											<div class="input-field col s12">
												<input id="username" type="text" class="validate"  name="user" maxlength="30" value="<?php echo $form->value("user"); ?>"><?php echo $form->error("user"); ?>
												<label for="username">Username</label>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12">
												<input id="password" type="password" class="validate" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>"><?php echo $form->error("pass"); ?>
												<label for="password">Password</label>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12">
												<input id="email" type="email" class="validate" name="email" maxlength="50" value="<?php echo $form->value("email"); ?>"><?php echo $form->error("email"); ?>
												<label for="email">Email</label>
											</div>
										</div>
										<div class="row">
											
											<div class="input-field col s12">
												<input id="CWID" type="text" class="validate" name="cwid" maxlength="50" value="<?php echo $form->value("cwid"); ?>"><?php echo $form->error("cwid"); ?>
												<label for="name">CWID</label>
											</div>
										</div>
										<input type="hidden" name="subjoin" value="1">
										<button class="btn waves-effect waves-light" type="submit" name="action" value="Set Deadline">Add user
											<i class="material-icons right">send</i>
										</button>
										
										
									</div>
								</form>
								
								
								<!front end code starts>
								
								<h5>Assign Lead Instructor Role:</h5>
								<br>
								<form class="col s12 action="adminprocess.php" method="POST" id="lead"">
								
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
								
								<input type="hidden" name="sublead" value="1">
								<button class="btn waves-effect waves-light" type="submit" >Add Lead Instructor
									<i class="material-icons right">send</i>
								</button>
								
								<br>
								<hr class="style13">
								
							</form>
							<!front end code end>
							
							
						</div>
						
						
						<!tab for set deadline>
						<div id="test2" class="col s12 deadlines">
							<?php echo $form->error("deadlines"); ?>
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
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
								<hr class="style13">
							
							<h5>Remove Deadline</h5>
							<form action="adminprocess.php" method="POST" id="removedeadline">
								<select form="removedeadline" name="number">
									<?php
										$q = "SELECT * FROM ".TBL_DEADLINES;
										$result = $database->query($q);
										$num_rows = mysql_numrows($result);
										for($i=0; $i<$num_rows; $i++){
											$number  = mysql_result($result,$i,"id");
											$type = mysql_result($result,$i,"type");
											$open = mysql_result($result,$i,"open");
											$close = mysql_result($result,$i,"close");
											echo "<option value='".$number."'>".$type.": ".$open." - ".$close."</option>";
										}
									?>  
									<label>Deadline to remove</label>
									<input type="hidden" name="subdeadlineRemove" value="1">
									<button class="btn waves-effect waves-light" type="submit" name="action" value="Remove Deadline">Remove Deadline
										<i class="material-icons right">send</i>
									</button>
									<br>
									<br>

								</form>
								<br>
							

							</div>
							
							
							
							
							<!course tab>
							<div id="test5" class="col s12">
								<form action="adminprocess.php" method="POST" id="addcourse">
									<h5>Add Course</h5>
									<div class="input-field col s12">
										<select form="addcourse" name="sem">
											<option value='1'>1</option>
											<option value='2'>2</option>
											<option value='3'>3</option>
											<option value='4'>4</option>
											<option value='5'>5</option>
										</select>
										<label>Semester</label>
									</div>
									<div class="input-field col s12">
										<input id="coursenumber" type="text" class="validate" name="num" maxlength="30">
										<label for="coursenumber">Course Number</label>
									</div>
									<div class="input-field col s12">
										<input id="coursename" type="text" class="validate" name="title" maxlength="30">
										<label for="coursename">Course Title</label>
									</div>
									<input type="hidden" name="subaddcourse" value="1">
									<button class="btn waves-effect waves-light" type="submit" name="action" value="Add Course">Add Course
										<i class="material-icons right">send</i>
									</button>
								</form>
								
								<form action="adminprocess.php" method="POST" id="deletecourse">
									<h5>Delete Course</h5>
									<div class="input-field col s12">
										<select form="deletecourse" name="course_number">
											<?php
												$q = "SELECT * FROM ".TBL_COURSE;
												$result = $database->query($q);
												$num_rows = mysql_numrows($result);
												for($i=0; $i<$num_rows; $i++){
													$number  = mysql_result($result,$i,"course_number");
													$title = mysql_result($result,$i,"title");
													echo "<option value='".$number."'>".$number." - ".$title."</option>";
												}
											?>  
											<label>Course number</label>
										</div>
										<input type="hidden" name="subdelcourse" value="1">
										<button class="btn waves-effect waves-light" type="submit" name="action" value="Delete Course">Delete Course
											<i class="material-icons right">send</i>
										</button>
									</form>
									
									<div class="input-field col s12">
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
									
									<form action="adminprocess.php" method="POST" id="deletesection">
										<h5>Delete Section</h5>
										<div class="input-field col s12">
											<select form="delete" name="section">
												<?php
													$q = "SELECT * FROM ".TBL_COURSE." NATURAL JOIN ".TBL_CRN;
													$result = $database->query($q);
													$num_rows = mysql_numrows($result);
													for($i=0; $i<$num_rows; $i++){
														$number  = mysql_result($result,$i,"crn");
														$course = mysql_result($result,$i,"course_number");
														$title = mysql_result($result,$i,"title");
														echo "<option value='".$number."'>".$course." - ".$title." - ".$number."</option>";
													}
												?>  
												<label>Section</label>
											</div>
											<input type="hidden" name="subdelsection" value="1">
											<button class="btn waves-effect waves-light" type="submit" name="action" value="Delete Section">Delete Section
												<i class="material-icons right">send</i>
											</button>
										</form>
									</div>
									
									
									
									
									<!Room add delete tab>
									<div id="test4" class="col s12">
										<h5> Add Room </h5>
										<form action="adminprocess.php" method="POST">
											<!add room form>
											<div class="row">
												
												<div class="row">
													
													<div class="input-field col s12">
														<input id="roomnumber" type="text" class="validate" name="name" maxlength="30">
														<label for="roomnumber">Room name/Number</label>
													</div>
												</div>
												<div class="row">
													
													<div class="input-field col s12">
														<input id="capacity" type="text" class="validate" name="cap" maxlength="30">
														<label for="capacity">Capacity</label>
													</div>
												</div>
												
												<div class="input-field col s12">
													<input id="description" type="text" class="validate" name="desc">
													<label for="deescription">Description</label>
												</div>
											</div>
											
											
											
											<input type="hidden" name="subaddroom" value="1">
											<button class="btn waves-effect waves-light" type="submit" name="action" value="Add Room">Add Room
												<i class="material-icons right">send</i>
											</button>
											<hr class="style13">
										</form>
										
										
										<!delete room form>
										<form action="adminprocess.php" method="POST" id="delete">
											<h5>Delete Room</h5>
											<div class="input-field col s12">
												<select form="delete" name="number">
													<?php
														$q = "SELECT * FROM ".TBL_ROOMS;
														$result = $database->query($q);
														$num_rows = mysql_numrows($result);
														for($i=0; $i<$num_rows; $i++){
															$number  = mysql_result($result,$i,"room_number");
															echo "<option value='".$number."'>".$number."</option>";
														}
													?>  
													<label>Room number</label>
												</div>
												<input type="hidden" name="subdeluser" value="1">
												<button class="btn waves-effect waves-light" type="submit" name="action" value="Delete Room">Delete room
													<i class="material-icons right">send</i>
												</button>
											</form>
										</div>
										
									</div>
									
								</div>
								
								<!-- Alyssa's Code
									<div class="update">
									<h3>Assign Lead Instructor Role:</h3>
									<?php echo $form->error("leaduser"); ?>
									<form action="adminprocess.php" method="POST" id="lead">
									<p>Username: <select form="lead" name="user">
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
									<p>Course:
									<select form="lead" name="course">
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
									</p>
									<input type="hidden" name="sublead" value="1">
									<input type="submit" value="Add Lead Instructor">
									</form>
									</div>
								-->
								<hr>
								
								
								
							</div>
						</main>
						
						<div class="card1 card">
							<h5>User Log</h5>
						<form action="adminprocess.php" method="POST" id="log">
							<input type="hidden" name="subclearlog" value="1">
							<input type="submit" value="Clear Log">
						</form>
						
						<textarea style="height:200px;padding:20px;">
							<?php
								$q = "SELECT user.name, log.* FROM ".TBL_LOG." join ".TBL_USERS." WHERE user.CWID = log.CWID ORDER BY timestamp DESC";
								//$myfile = fopen("error.txt", "a") or die(print_r($q));
								$result = $database->query($q);
								$num_rows = mysql_numrows($result);
								for($i=0; $i<$num_rows; $i++){
									$CWID = mysql_result($result,$i,"CWID");
									$msg = mysql_result($result,$i,"message");
									$timestamp = mysql_result($result,$i,"timestamp");
									$referrer = mysql_result($result,$i,"referred_page");
									$person = mysql_result($result,$i,"name");
									echo "$msg - by $person at $timestamp. Referrer: $referrer \n";
								}
								
							?>
							
						</textarea>
					
						<hr>
						<form action="adminprocess.php" method="POST" id="backup">
							<input type="hidden" name="subbackup" value="1">
							<button class="btn waves-effect waves-light" type="submit" >Create Backup
								<i class="material-icons right">send</i>
							</button>
						</form>
						<hr>
						<form action="adminprocess.php" method="POST" id="archive">
							<input type="hidden" name="subarchive" value="1">
							<button class="btn waves-effect waves-light" type="submit" onclick= "confirmButton()">Archive & Reset
								<i class="material-icons right">send</i>
							</button>
						</form>
					</div>
						
						<?php
							if($form->num_errors > 0){
								echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></td>";
							}
						?>
						
						Back to [<a href="../index.php">Main Page</a>]<br><br>
						
						
						<footer class="page-footer">
							<div class="container">
								<div class="row">
									<div class="col l6 s12">
										<h4 class="white-text">ULM Nursing Calendar</h4>
										<p class="grey-text text-lighten-4">Never miss a class because we got you covered!</p>
									</div>
									
								</div>
							</div>
							<div class="footer-copyright">
								<div class="container">
									© 2018 DeltaSquad
									<a class="grey-text text-lighten-4 right" href="https://www.ulm.edu">ULM</a>
								</div>
							</div>
						</footer>
						
						<!--Import jQuery before materialize.js-->
						<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
						
						<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.js"></script>
						<script>
							$( document ).ready(function(){
								$(".button-collapse").sideNav();
								
								$(document).ready(function(){
									$('ul.tabs').tabs();
									
									$(document).ready(function() {
										$('select').material_select();
										
										
										$('.datepicker').pickadate({
											selectMonths: true, // Creates a dropdown to control month
											selectYears: 15, // Creates a dropdown of 15 years to control year,
											today: 'Today',
											clear: 'Clear',
											close: 'Ok',
											closeOnSelect: false, // Close upon selecting a date,
											format: "mm/dd/yyyy",
											formatSubmit: "yyyy/mm/dd"
											
											
										})
										$(document).ready(function() {
											$('select').material_select();
										});
									});
									
									
								});
								
								
							});
							
							
						</script>
						<script type = "text/javascript">
							<!--https://www.w3schools.com/jsref/met_win_confirm.asp-->
							function confirmButton() 
							{
								var txt;
								var r = confirm("Are you sure you wish to archive and reset?");
								if (r == true) 
								{
									txt = "You pressed OK!";
								} else 
								{
									txt = "You pressed Cancel!";
								}
								<!--	document.getElementsByClassName("demo").innerHTML = txt;	-->
								<!--		can use this to add text to the screen at somepoint -->
								<!--		or here is where you will put some functionality to the cancel/confirm button-->
								
							}
						</script>
					</body>
				</html>
				<?php
				}
				require('../magpierss/rss_fetch.inc');
				$url = 'https://calendar.ulm.edu/RSSFeeds.aspx?data=qaUdY1wvEciJ2wvy%2fMP8stIuwmDMkzkufjgrCy1C0S9I4u1TluveZQ%3d%3d';
				$safe_url = mysql_real_escape_string($url);
				$rss = fetch_rss($safe_url);
				$clearRSS = sprintf("DELETE FROM ".TBL_EVENTS." WHERE series = 9100");
				$database->query($clearRSS);
				foreach ($rss->items as $item) {
					$title = $item['title'];
					$desc = $item['description'];
					$author = $item['author'];
					$pub = $item['pubdate'];
					$link = $item['link'];
					$notes = "Title: ".$title."<br>Description: ".$desc."<br>Author: ".$author."<br>Published Date: ".$pub."<br><a href=\'".$link."\'>More information on ULM</a>";
					$date = date('Y-m-d H:i:s', $item['date_timestamp']);
					$time = time();
					$fillRSS = "INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', 0, 'event', 0, 86753091, 'offsite', '$notes', 9100, '$date', '$date', '$time', 'approved')";
					$result = $database->query($fillRSS);
					
					
				}
			?>
			
				