<?php
	include("database.php");
	include("mailer.php");
	include("form.php");
	
	class Session
	{
		var $username;     //Username given on sign-up
		var $user_id;       //Random value generated on current login
		var $userlevel;    //The level to which the user pertains
		var $time;         //Time user was last active (page loaded)
		var $logged_in;    //True if user is logged in, false otherwise
		var $userinfo = array();  //The array holding all user info
		var $url;          //The page url current being viewed
		var $referrer;     //Last recorded site page viewed
		var $id;
		/**
			* Note: referrer should really only be considered the actual
			* page referrer in process.php, any other time it may be
			* inaccurate.
		*/
		
		/* Class constructor */
		function Session(){
			$this->time = time();
			$this->startSession();
		}
		
		/**
			* startSession - Performs all the actions necessary to 
			* initialize this session object. Tries to determine if the
			* the user has logged in already, and sets the variables 
			* accordingly. Also takes advantage of this page load to
			* update the active visitors tables.
		*/
		function startSession(){
			global $database;  //The database connection
			session_start();   //Tell PHP to start the session
			
			/* Determine if user is logged in */
			$this->logged_in = $this->checkLogin();
			
			/**
				* Set guest value to users not logged in, and update
				* active guests table accordingly.
			*/
			if(!$this->logged_in){
				$this->username = $_SESSION['username'] = GUEST_NAME;
				$this->userlevel = GUEST_LEVEL;
			}
			
			
			/* Set referrer page */
			if(isset($_SESSION['url'])){
				$this->referrer = $_SESSION['url'];
				}else{
				$this->referrer = "/";
			}
			
			/* Set current url */
			$this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
		}
		
		/**
			* checkLogin - Checks if the user has already previously
			* logged in, and a session with the user has already been
			* established. Also checks to see if user has been remembered.
			* If so, the database is queried to make sure of the user's 
			* authenticity. Returns true if the user has logged in.
		*/
		function checkLogin(){
			global $database;  //The database connection
			/* Check if user has been remembered */
			if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])){
				$this->username = $_SESSION['username'] = $_COOKIE['cookname'];
				$this->user_id   = $_SESSION['user_id']   = $_COOKIE['cookid'];
			}
			
			/* Username and user_id have been set and not guest */
			if(isset($_SESSION['username']) && isset($_SESSION['user_id']) &&
			$_SESSION['username'] != GUEST_NAME){
				/* Confirm that username and user_id are valid */
				if($database->confirmuser_id($_SESSION['username'], $_SESSION['user_id']) != 0){
					/* Variables are incorrect, user not logged in */
					unset($_SESSION['username']);
					unset($_SESSION['user_id']);
					return false;
				}
				
				/* User is logged in, set class variables */
				$this->userinfo  = $database->getUserInfo($_SESSION['username']);
				$this->username  = $this->userinfo['username'];
				$this->user_id    = $this->userinfo['user_id'];
				$this->userlevel = $this->userinfo['userlevel'];
				$this->id = $this->userinfo['id'];
				
				/* auto login hash expires in three days */
				if($this->userinfo['hash_generated'] < (time() - (60*60*24*3))){
					/* Update the hash */
					$database->updateUserField($this->userinfo['username'], 'hash', $this->generateRandID());
					$database->updateUserField($this->userinfo['username'], 'hash_generated', time());
				}
				
				return true;
			}
			/* User not logged in */
			else{
				return false;
			}
		}
		
		/**
			* login - The user has submitted his username and password
			* through the login form, this function checks the authenticity
			* of that information in the database and creates the session.
			* Effectively logging in the user if all goes well.
		*/
		function login($subuser, $subpass, $subremember){
			global $database, $form;  //The database and form object
			
			/* Username error checking */
			$field = "user";  //Use field name for username
			$q = "SELECT valid FROM ".TBL_USERS." WHERE username='$subuser'";
			$valid = $database->query($q);
			$valid = mysql_fetch_array($valid);
			
			if(!$subuser || strlen($subuser = trim($subuser)) == 0){
				$form->setError($field, "* Username not entered");
			}
			else{
				/* Check if username is not alphanumeric */
				if(!ctype_alnum($subuser)){
					$form->setError($field, "* Username not alphanumeric");
				}
			}	  
			
			/* Password error checking */
			$field = "pass";  //Use field name for password
			if(!$subpass){
				$form->setError($field, "* Password not entered");
			}
			
			/* Return if form errors exist */
			if($form->num_errors > 0){
				return false;
			}
			
			/* Checks that username is in database and password is correct */
			$subuser = stripslashes($subuser);
			$result = $database->confirmUserPass($subuser, md5($subpass));
			
			/* Check error codes */
			if($result == 1){
				$field = "user";
				$form->setError($field, "* Username not found");
			}
			else if($result == 2){
				$field = "pass";
				$form->setError($field, "* Invalid password");
			}
			
			/* Return if form errors exist */
			if($form->num_errors > 0){
				return false;
			}
			
			
			if(EMAIL_WELCOME){
				if($valid['valid'] == 0){
					$form->setError($field, "* User's account has not yet been confirmed.");
				}
			}
			
			/* Return if form errors exist */
			if($form->num_errors > 0){
				return false;
			}
			
			
			
			/* Username and password correct, register session variables */
			$this->userinfo  = $database->getUserInfo($subuser);
			$this->username  = $_SESSION['username'] = $this->userinfo['username'];
			$this->user_id    = $_SESSION['user_id']   = $this->generateRandID();
			$this->userlevel = $this->userinfo['userlevel'];
			$this->id = $this->userinfo['id'];
			/* Insert user_id into database and update active users table */
			$database->updateUserField($this->username, "user_id", $this->user_id);
			
			
			/**
				* This is the cool part: the user has requested that we remember that
				* he's logged in, so we set two cookies. One to hold his username,
				* and one to hold his random value user_id. It expires by the time
				* specified in constants.php. Now, next time he comes to our site, we will
				* log him in automatically, but only if he didn't log out before he left.
			*/
			if($subremember){
				setcookie("cookname", $this->username, time()+COOKIE_EXPIRE, COOKIE_PATH);
				setcookie("cookid",   $this->user_id,   time()+COOKIE_EXPIRE, COOKIE_PATH);
			}
			
			/* Login completed successfully */
			return true;
		}
		
		/**
			* logout - Gets called when the user wants to be logged out of the
			* website. It deletes any cookies that were stored on the users
			* computer as a result of him wanting to be remembered, and also
			* unsets session variables and demotes his user level to guest.
		*/
		function logout(){
			global $database;  //The database connection
			/**
				* Delete cookies - the time must be in the past,
				* so just negate what you added when creating the
				* cookie.
			*/
			if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])){
				setcookie("cookname", "", time()-COOKIE_EXPIRE, COOKIE_PATH);
				setcookie("cookid",   "", time()-COOKIE_EXPIRE, COOKIE_PATH);
			}
			
			/* Unset PHP session variables */
			unset($_SESSION['username']);
			unset($_SESSION['user_id']);
			
			/* Reflect fact that user has logged out */
			$this->logged_in = false;
			
			
			/* Set user level to guest */
			$this->username  = GUEST_NAME;
			$this->userlevel = GUEST_LEVEL;
		}
		
		/**
			* register - Gets called when the user has just submitted the
			* registration form. Determines if there were any errors with
			* the entry fields, if so, it records the errors and returns
			* 1. If no errors were found, it registers the new user and
			* returns 0. Returns 2 if registration failed.
		*/
		function register($subuser, $subpass, $subemail, $subname){
			
			global $database, $form, $mailer;  //The database, form and mailer object
			
			/* Username error checking */
			$field = "user";  //Use field name for username
			if(!$subuser || strlen($subuser = trim($subuser)) == 0){
				$form->setError($field, "* Username not entered");
			}
			else{
				/* Spruce up username, check length */
				$subuser = stripslashes($subuser);
				if(strlen($subuser) < 5){
					$form->setError($field, "* Username below 5 characters");
				}
				else if(strlen($subuser) > 30){
					$form->setError($field, "* Username above 30 characters");
				}
				/* Check if username is not alphanumeric */
				else if(!ctype_alnum($subuser)){
					$form->setError($field, "* Username not alphanumeric");
				}
				/* Check if username is reserved */
				else if(strcasecmp($subuser, GUEST_NAME) == 0){
					$form->setError($field, "* Username reserved word");
				}
				/* Check if username is already in use */
				else if($database->usernameTaken($subuser)){
					$form->setError($field, "* Username already in use");
				}
				/* Check if username is banned */
				else if($database->usernameBanned($subuser)){
					$form->setError($field, "* Username banned");
				}
			}
			
			/* Password error checking */
			$field = "pass";  //Use field name for password
			if(!$subpass){
				$form->setError($field, "* Password not entered");
			}
			else{
				/* Spruce up password and check length*/
				$subpass = stripslashes($subpass);
				if(strlen($subpass) < 4){
					$form->setError($field, "* Password too short");
				}
				/* Check if password is not alphanumeric */
				else if(!ctype_alnum(($subpass = trim($subpass)))){
					$form->setError($field, "* Password not alphanumeric");
				}
				/**
					* Note: I trimmed the password only after I checked the length
					* because if you fill the password field up with spaces
					* it looks like a lot more characters than 4, so it looks
					* kind of stupid to report "password too short".
				*/
			}
			
			/* Email error checking */
			$field = "email";  //Use field name for email
			if(!$subemail || strlen($subemail = trim($subemail)) == 0){
				$form->setError($field, "* Email not entered");
			}
			else{
				/* Check if valid email address */
				if(filter_var($subemail, FILTER_VALIDATE_EMAIL) == FALSE){
					$form->setError($field, "* Email invalid");
				}
				/* Check if email is already in use */
				if($database->emailTaken($subemail)){
					$form->setError($field, "* Email already in use");
				}
				
				$subemail = stripslashes($subemail);
			}
			
			/* Name error checking */
			$field = "name";
			if(!$subname || strlen($subname = trim($subname)) == 0){
				$form->setError($field, "* Name not entered");
				} else {
				$subname = stripslashes($subname);
			}
			
			$randid = $this->generateRandID();
			
			/* Errors exist, have user correct them */
			if($form->num_errors > 0){
				return 1;  //Errors with form
			}
			/* No errors, add the new account to the */
			else{
				if($database->addNewUser($subuser, md5($subpass), $subemail, $randid, $subname)){
					if(EMAIL_WELCOME){               
						$mailer->sendWelcome($subuser,$subemail,$subpass,$randid);
					}
					return 0;  //New user added succesfully
					}else{
					return 2;  //Registration attempt failed
				}
			}
		}
		
		/**
			* editAccount - Attempts to edit the user's account information
			* including the password, which it first makes sure is correct
			* if entered, if so and the new password is in the right
			* format, the change is made. All other fields are changed
			* automatically.
		*/
		function editAccount($subcurpass, $subnewpass, $subemail, $subname){
			global $database, $form;  //The database and form object
			/* New password entered */
			if($subnewpass){
				/* Current Password error checking */
				$field = "curpass";  //Use field name for current password
				if(!$subcurpass){
					$form->setError($field, "* Current Password not entered");
				}
				else{
					/* Check if password too short or is not alphanumeric */
					$subcurpass = stripslashes($subcurpass);
					if(strlen($subcurpass) < 4 ||
					!preg_match("^([0-9a-z])+$", ($subcurpass = trim($subcurpass)))){
						$form->setError($field, "* Current Password incorrect");
					}
					/* Password entered is incorrect */
					if($database->confirmUserPass($this->username,md5($subcurpass)) != 0){
						$form->setError($field, "* Current Password incorrect");
					}
				}
				
				/* New Password error checking */
				$field = "newpass";  //Use field name for new password
				/* Spruce up password and check length*/
				$subpass = stripslashes($subnewpass);
				if(strlen($subnewpass) < 4){
					$form->setError($field, "* New Password too short");
				}
				/* Check if password is not alphanumeric */
				else if(!preg_match("^([0-9a-z])+$", ($subnewpass = trim($subnewpass)))){
					$form->setError($field, "* New Password not alphanumeric");
				}
			}
			/* Change password attempted */
			else if($subcurpass){
				/* New Password error reporting */
				$field = "newpass";  //Use field name for new password
				$form->setError($field, "* New Password not entered");
			}
			
			/* Email error checking */
			$field = "email";  //Use field name for email
			if($subemail && strlen($subemail = trim($subemail)) > 0){
				/* Check if valid email address */
				if(filter_var($subemail, FILTER_VALIDATE_EMAIL) == FALSE){
					$form->setError($field, "* Email invalid");
				}
				$subemail = stripslashes($subemail);
			}
			
			/* Name error checking */
			$field = "name";
			if(!$subname || strlen($subname = trim($subname)) == 0){
				$form->setError($field, "* Name not entered");
				} else {
				$subname = stripslashes($subname);
			}
			
			/* Errors exist, have user correct them */
			if($form->num_errors > 0){
				return false;  //Errors with form
			}
			
			/* Update password since there were no errors */
			if($subcurpass && $subnewpass){
				$database->updateUserField($this->username,"password",md5($subnewpass));
			}
			
			/* Change Email */
			if($subemail){
				$database->updateUserField($this->username,"email",$subemail);
			}
			
			/* Change Name */
			if($subname){
				$database->updateUserField($this->username,"name",$subname);
			}
			
			/* Success! */
			return true;
		}
		
		/**
			* isAdmin - Returns true if currently logged in user is
			* an administrator, false otherwise.
		*/
		function isAdmin(){
			return ($this->userlevel == ADMIN_LEVEL ||
			$this->username  == ADMIN_NAME);
		}
		
		/**
			* isinstructor - Returns true if currently logged in user is
			* an instructor or an administrator, false otherwise.
		*/
		function isInstructor(){
			return ($this->userlevel == INSTRUCTOR_LEVEL ||
			$this->userlevel == ADMIN_LEVEL);
		}
		
		/**
			* generateRandID - Generates a string made up of randomized
			* letters (lower and upper case) and digits and returns
			* the md5 hash of it to be used as a user_id.
		*/
		function generateRandID(){
			return md5($this->generateRandStr(16));
		}
		
		/**
			* generateRandStr - Generates a string made up of randomized
			* letters (lower and upper case) and digits, the length
			* is a specified parameter.
		*/
		function generateRandStr($length){
			$randstr = "";
			for($i=0; $i<$length; $i++){
				$randnum = mt_rand(0,61);
				if($randnum < 10){
					$randstr .= chr($randnum+48);
					}else if($randnum < 36){
					$randstr .= chr($randnum+55);
					}else{
					$randstr .= chr($randnum+61);
				}
			}
			return $randstr;
		}
		
		function cleanInput($post = array()) {
			foreach($post as $k => $v){
				$post[$k] = trim(htmlspecialchars($v));
			}
			return $post;
		}
		
		/**
			* addEvent - adds an event part 1 not  complete
		*/
		function addEventA($title, $type, $course, $seats, $notes, $date, $starttime, $endtime){
			header("Location: /addevent.php?t=$title&ty=$type&c=$course&s=$seats&n=$notes&d=$date&st=$starttime&et=$endtime");  
		}
		function addEventB($title, $type, $course, $crn, $seats, $notes, $date, $starttime, $endtime){
			foreach ($crn as $c){
				$crns = $crns . "+" . $c;			
			}
			header("Location: /addevent.php?t=$title&ty=$type&c=$course&crn=$crns&s=$seats&n=$notes&d=$date&st=$starttime&et=$endtime");  
		}
		function addEventC($title, $type, $course, $crn, $seats, $notes, $dateStart, $dateEnd, $room, $series){
			global $database, $form; 
			$user = $this->id;
			$time= date("Y/m/d H:i:s");
			$result = $database->addEvent2($title, $type, $course, $crn, $seats, $notes, $dateStart, $dateEnd, $room, $user, $series, $time);
			if ($result){return TRUE;}			
		}
		function chooseSemester($semester){
			header("Location: /class_select.php?sem=$semester");  
		}
		
		function chooseCourse($course, $sem){
			$courses = "";
			foreach ($course as $c){
				$courses = $courses . "+" . $c;		
			}
			header("Location: /class_select.php?sem=$sem&c=$courses");  
		}
		
		function chooseCrn($crn){
			
			foreach ($crn as $c){
				$crns = $crns . "+" . $c;			
			}
			header("Location: /index.php?crn=$crns");  
		}
	};
	
	
	/**
		* Initialize session object - This must be initialized before
		* the form object because the form uses session variables,
		* which cannot be accessed unless the session has started.
	*/
	$session = new Session;
	
	/* Initialize form object */
	$form = new Form;
	
?>
