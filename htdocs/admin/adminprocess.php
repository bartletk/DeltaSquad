<?php
	include("../include/session.php");
	
	class AdminProcess
	{
		/* Class constructor */
		function AdminProcess(){
			global $session;
			/* Make sure administrator is accessing page */
			if(!$session->isAdmin()){
				header("Location: ../index.php");
				return;
			}
			/* Admin submitted update user level form */
			if(isset($_POST['subupdlevel'])){
				$this->procUpdateLevel();
			}
			/* Admin submitted delete user form */
			else if(isset($_POST['subdeluser'])){
				$this->procDeleteUser();
			}
			/* Admin submitted delete inactive users form */
			else if(isset($_POST['subdelinact'])){
				$this->procDeleteInactive();
			}
			/* Admin submitted ban user form */
			else if(isset($_POST['subbanuser'])){
				$this->procBanUser();
			}
			/* Admin submitted delete banned user form */
			else if(isset($_POST['subdelbanned'])){
				$this->procDeleteBannedUser();
			} 
			else if(isset($_POST['subdeadline'])){
				$this->procDeadline();
			}
			else if (isset($_POST['subjoin'])){
				$this->procRegister();
			}
			else if (isset($_POST['subaddroom'])){
				$this->procAddRoom();
			}
			else if (isset($_POST['subdelroom'])){
				$this->procDeleteRoom();
			}
			else if (isset($_POST['sublead'])){
				$this->procAddLeader();
			}
			///
			else if (isset($_POST['subaddcourse'])){
				$this->procAddCourse();
			}
			else if (isset($_POST['subdelcourse'])){
				$this->procDelCourse();
			}
			else if (isset($_POST['subaddsection'])){
				$this->procAddSection();
			}
			else if (isset($_POST['subdelsection'])){
				$this->procDelSection();
			}
			else if (isset($_POST['subbackup'])){
				$this->procBackup();
			}
			else if (isset($_POST['subarchive'])){
				$this->procArchive();
			}
			else if (isset($_POST['subclearlog'])){
				$this->procClearLog();
			}
			/* Should not get here, redirect to home page */
			else{
				header("Location: ../index.php");
			}
		}
		
		/**
			* procUpdateLevel - If the submitted username is correct,
			* their user level is updated according to the admin's
			* request.
		*/
		function procUpdateLevel(){
			global $session, $database, $form;
			/* Username error checking */
			$subuser = $_POST['upduser'];
			
			/* Errors exist, have user correct them */
			if($form->num_errors > 0){
				$_SESSION['value_array'] = $_POST;
				$_SESSION['error_array'] = $form->getErrorArray();
				header("Location: ".$session->referrer);
			}
			/* Update user level */
			else{
				//$database->updateUserField($subuser, "userlevel", (int)$_POST['updlevel']);
				$myfile = fopen("error.txt", "a") or die(print_r($database->updateUserField($subuser, "userlevel", (int)$_POST['updlevel'])));
				//header("Location: ".$session->referrer);
			}
		}
		
		/**
			* procDeleteUser - If the submitted username is correct,
			* the user is deleted from the database.
		*/
		function procDeleteUser(){
			global $session, $database, $form;
			/* Username error checking */
			$subuser = $this->checkUsername("deluser");
			
			/* Errors exist, have user correct them */
			if($form->num_errors > 0){
				$_SESSION['value_array'] = $_POST;
				$_SESSION['error_array'] = $form->getErrorArray();
				header("Location: ".$session->referrer);
			}
			/* Delete user from database */
			else{
				$q = "DELETE FROM ".TBL_USERS." WHERE username = '$subuser'";
				$database->query($q);
			$log = "User Deleted:".$subuser."";
			$database->logIt($log);
				header("Location: ".$session->referrer);
			}
		}
		
		/**
			* checkUsername - Helper function for the above processing,
			* it makes sure the submitted username is valid, if not,
			* it adds the appropritate error to the form.
		*/
		function checkUsername($uname, $ban=false){
			global $database, $form;
			/* Username error checking */
			$subuser = $_POST[$uname];
			$field = $uname;  //Use field name for username
			if(!$subuser || strlen($subuser = trim($subuser)) == 0){
				$form->setError($field, "* Username not entered<br>");
			}
			else{
				/* Make sure username is in database */
				$subuser = stripslashes($subuser);
				if(strlen($subuser) < 5 || strlen($subuser) > 30 ||
				!preg_match("/^([0-9a-z])+$/i", $subuser) ||
				(!$ban && !$database->usernameTaken($subuser))){
					$form->setError($field, "* Username does not exist<br>");
				}
			}
			return $subuser;
		}
		function procDeadline(){
			global $database, $form;
			$dateOpen = $_POST['dateOpen'];
			$dateClose = $_POST['dateClose'];
			$type = $_POST['type'];
			$q = "INSERT INTO ".TBL_DEADLINES." VALUES (NULL, '".$dateOpen." 00:00:00', '".$dateClose." 00:00:00', '".$type."');";
			$database->query($q);
			$log = "New deadline of type:".$type."";
			$database->logIt($log);
			header("Location: admin.php");
			
		}
		
   		function procRegister(){
			global $session, $form;
			$_POST = $session->cleanInput($_POST);
			/* Convert username to all lowercase (by option) */
			if(ALL_LOWERCASE){
				$_POST['user'] = strtolower($_POST['user']);
			}
			/* Registration attempt */
			$retval = $session->register($_POST['user'], $_POST['pass'], $_POST['email'], $_POST['name'], $_POST['cwid']);
			
			/* Registration Successful */
			if($retval == 0){
				$_SESSION['reguname'] = $_POST['user'];
				$_SESSION['regsuccess'] = true;
				header("Location: ".$session->referrer);
			}
			/* Error found with form */
			else if($retval == 1){
				$_SESSION['value_array'] = $_POST;
				$_SESSION['error_array'] = $form->getErrorArray();
				header("Location: ".$session->referrer);
			}
			/* Registration attempt failed */
			else if($retval == 2){
				$_SESSION['reguname'] = $_POST['user'];
				$_SESSION['regsuccess'] = false;
				header("Location: ".$session->referrer);
			}
		}
		function procAddRoom(){
			global $database, $form;
			$name = $_POST['name'];
			$cap = $_POST['cap'];
			$desc = $_POST['desc'];
			$q = "INSERT INTO ".TBL_ROOMS." VALUES ('".$cap."','".$name."','".$desc."');";
			$database->query($q);
			$log = "New room created:".$name."";
			$database->logIt($log);
			header("Location: admin.php");
			
		}
		function procDeleteRoom(){
			global $session, $database, $form;
			$number = $_POST['number'];
			$q = "DELETE FROM ".TBL_ROOMS." WHERE room_number = '$number'";
			$database->query($q);
			$log = "Room deleted:".$number."";
			$database->logIt($log);
			header("Location: ".$session->referrer);
		}
		function procAddLeader(){
			global $session, $database, $form;
			$q = "UPDATE ".TBL_COURSE." SET Lead_Instructor = ".$_POST['user']." where course_number = ".$_POST['course'];
			$database->query($q);
			$log = "Lead instructor set for:".$_POST['course']."";
			$database->logIt($log);
			header("Location: ".$session->referrer);
		}
////////////////////////////////////////////////////
		//done?
		function procAddCourse(){
			global $database, $form;
			$num = $_POST['num'];
			$title = $_POST['title'];
			$sem = $_POST['sem'];
			$q = "INSERT INTO ".TBL_COURSE." VALUES ('".$num."', NURS, '".$title."', NULL,".$sem.");";
			$database->query($q);
			$log = "New course created:".$num."";
			$database->logIt($log);
			header("Location: admin.php");
		}
		//done??
		function procDelCourse(){
			global $session, $database, $form;
			$number = $_POST['course_number'];
			$q = "DELETE FROM ".TBL_COURSE." WHERE course_number = '$number'";
			$database->query($q);
			$log = "Course Deleted:".$number."";
			$database->logIt($log);
			header("Location: ".$session->referrer);
			}
		//done?
		function procAddSection(){
			global $database, $form;
			$crn = $_POST['crn'];
			$course = $_POST['course'];
			$instructor = $_POST['instructor'];
			$q = "INSERT INTO ".TBL_CRN." VALUES ('".$crn."','".$course."','".$instructor."');";
			$database->query($q);
			$log = "Section Added:".$crn."";
			$database->logIt($log);
			header("Location: admin.php");
		}
		//done?
		function procDelSection(){
			global $session, $database, $form;
			$number = $_POST['section'];
			$q = "DELETE FROM ".TBL_CRN." WHERE crn = '$number'";
			$database->query($q);
			$log = "Section deleted:".$number."";
			$database->logIt($log);
			header("Location: ".$session->referrer);
			}
		// hope dis wurk not testing for a long time yo
		function procBackup(){
			global $session, $database, $form;
			$backup = "../archive/".time().".sql";
			exec('mysqldump --user='.DB_USER.' --password='.DB_PASS.' --host='.DB_SERVER.' '.DB_NAME.' > '.$backup.'');
			$log = "Backup Created";
			$database->logIt($log);
			header("Location: ".$session->referrer);
		}
		function procArchive(){
			global $session, $database, $form;
			$this->procBackup();

			// gonna have to do something
			$log = "Backup Created";
			$database->logIt($log);
			header("Location: ".$session->referrer);
		}
		//done?
		function procClearLog(){
			global $session, $database, $form;
			$q = "DELETE FROM ".TBL_LOG;
			$database->query($q);
			$log = "Cleared log";
			$database->logIt($log);
			header("Location: ".$session->referrer);
		}
		
	};
	
	/* Initialize process */
	$adminprocess = new AdminProcess;
	
?>
