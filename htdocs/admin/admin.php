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
</head>



<?php
//include("../header.php");
?>


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

<h1>Admin Center</h1>
<font size="4">Logged in as <b><?php echo $session->username; ?></b></font><br><br>
Back to [<a href="../index.php">Main Page</a>]<br><br>


<!front end code starts>
	<div class="card card1 card2 update">
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
    <br>
  </form>
<!front end code end>
</div>

<?php
if($form->num_errors > 0){
   echo "<font size=\"4\" color=\"#ff0000\">"
       ."!*** Error with request, please fix</font><br><br>";
}

/**
 * Update User Level
 */
?>


 
 
<!three Tabs for admin functionality>

<div class="card card1">
  <div class="row">
    <div class="col s12">
      <ul class="tabs">
        <li class="tab col s3"><a href="#test1">User</a></li>
        <li class="tab col s3"><a class="active" href="#test2">Deadline</a></li>
        <li class="tab col s3"><a href="#test4">Room</a></li>
      </ul>
    </div>
    
    <!User edit tab>
    <div id="test1" class="col s12 card1">
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
        <input id="CWID" type="text" class="validate" name="cwid" maxlength="50" value="<?php echo $form->value("cwid"); ?>"><?php echo $form->error("cwid"); ?>>
        <label for="name">CWID</label>
      </div>
    </div>
    <input type="hidden" name="subjoin" value="1">
    <button class="btn waves-effect waves-light" type="submit" name="action" value="Set Deadline">Add user
      <i class="material-icons right">send</i>
    </button>


  </div>
</form>


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




<?php
if($form->num_errors > 0){
   echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></td>";
}
?>

Back to [<a href="../index.php">Main Page</a>]<br><br>


</div>
</main>


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
    closeOnSelect: false // Close upon selecting a date,


  })
      });


    });


  });


</script>
</body>
</html>
<?php
}
?>

