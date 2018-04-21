
<html>
<head>
	<title></title>
</head>
<link rel="stylesheet" type="text/css" href="css/footer.css">


<?php
	 include("top_header.php");
	 $page = "class_select.php";
	
	 global $database;
?>
	<main>
	<div class="card card-1">
 	 <h3><strong>Welcome to ULM Nursing Calendar</strong></h3>
	</div>

	<div class="parallax-container">
    	 <div class="parallax"><img src="/img/library1.jpg"></div>
 		</div>
		<div class="section white">
	</div>

	


	<fieldset>
		<div class="card card-2 heading">
  		<?php
		if (!isset($_GET['cwid'])){
		?>
  			<h5>Student Login</h5>
		<p>
		<form action="process.php" method="POST" id="studentLogin">
			<div class="input-field col s12">
		<input type="text" name="CWID" maxlength="30"></p>
		<label for="CWID">CWID</label>
	</div>
	<input type="hidden" name="studentLogin" value="1">
	 <button class="btn waves-effect waves-light" type="submit" value="submit">Submit
    <i class="material-icons right">send</i>
  </button>
		</form>
		<?php
		}
		if (isset($_GET['cwid']) && $_GET['cwid'] != 0 && $_GET['cwid'] != NULL && !isset($_GET['sem']) && !isset($_GET['c'])){
		?>
			<form action="process.php" method="POST" id="choosesemester">
				<div class="input-field col s12">
				
				<select form="choosesemester" name="semester">
					<option value="" disabled selected>Choose your Semester</option>
					<option value="1">Semester 1</option>
					<option value="2">Semester 2</option>
					<option value="3">Semester 3</option>
					<option value="4">Semester 4</option>
					<option value="5">Semester 5</option>
				</select>
			</p>
			<input type="hidden" name="choosesemester" value="1">
		<input type="hidden" name="cwid" value="<?php echo $_GET['cwid']; ?>">
			 <button class="btn waves-effect waves-light" type="submit" value="Pick Course">Pick Course
    <i class="material-icons right">send</i>
  </button>
		</div>
		</form>
		<?php
		}
			if (isset($_GET['sem']) && !isset($_GET['c'])){
				$sem = $_GET['sem']; 
			?>
			<form action="process.php" method="POST" id="choosecourse">
				<div class="input-field col s12">
				<p>
					<label>Choose Courses: </label><br/>
					<select multiple name="course[]" size=3>
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
				<input type="hidden" name="cwid" value="<?php echo $_GET['cwid']; ?>">
				<input type="hidden" name="choosecourse" value="1">
				<input type="hidden" name="sem" value="<?php echo $sem; ?>">
				<button class="btn waves-effect waves-light" type="submit" value="Pick CRNs">Pick CRNs
    
   				 <i class="material-icons right">send</i>
  						</button>
			</div>
			</form>
			<?php
			}
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
					<input type="hidden" name="cwid" value="<?php echo $_GET['cwid']; ?>">
					<button class="btn waves-effect waves-light" type="submit" value="View Schedule">View Schedule
    
    					<i class="material-icons right">send</i>
 				 	</button>
				</form>
				<?php
				
			}
		?>
		
		
		
		
	</fieldset>
</div>




</form>

<div class="parallax-container">
    	 <div class="parallax"><img src="/img/anurse.jpg"></div>
 		</div>
		<div class="section white">
	</div>

<?php
	//include "footer.php";
	?>
</main>

<footer class="page-footer">
          <div class="footercontainer">
            <div class="row">
              <div class="col l6 s12">
                <h4 class="white-text">ULM Nursing Calendar</h4>
                <p class="grey-text text-lighten-4">Never miss a class because we got you covered!</p>
              </div>
             
            </div>
          </div>
          <div class="footer-copyright">
            <div class="footercontainer">
            © 2018 DeltaSquad
            <a class="grey-text text-lighten-4 right" href="https://www.ulm.edu">ULM</a>
            </div>
    </div>
</footer>
    


<!--Import jQuery before materialize.js-->
  <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        
          <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

          <script>
              $( document ).ready(function(){
                $(".button-collapse").sideNav();

                $(document).ready(function() {
    $('select').material_select();
  });
 $(document).ready(function(){
    $('.parallax').parallax();
  });

                });


          </script>
	</body>
	</html>		