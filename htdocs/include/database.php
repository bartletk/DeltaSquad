<?php
	include("constants.php");
	
	class MySQLDB
	{
		var $connection;         //The MySQL database connection
		var $mailCon;
		var $logCon;
		var $num_active_users;   //Number of active users viewing site
		var $num_active_guests;  //Number of active guests viewing site
		var $num_members;        //Number of signed-up users
		/* Note: call getNumMembers() to access $num_members! */
		
		/* Class constructor */
		function MySQLDB(){
			/* Make connection to database */
			$this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS, true) or die(mysql_error());
			mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
			
			$this->mailCon = mysql_connect(DB_SERVER, DB_USER, DB_PASS, true) or die(mysql_error());
			mysql_select_db(DB_NAME, $this->mailCon) or die(mysql_error());
			
			$this->logCon = mysql_connect(DB_SERVER, DB_USER, DB_PASS, true) or die(mysql_error());
			mysql_select_db(DB_NAME, $this->logCon) or die(mysql_error());
		}
		
		/**
			* confirmUserPass - Checks whether or not the given
			* username is in the database, if so it checks if the
			* given password is the same password in the database
			* for that user. If the user doesn't exist or if the
			* passwords don't match up, it returns an error code
			* (1 or 2). On success it returns 0.
		*/
		function confirmUserPass($username, $password){
			/* Add slashes if necessary (for query) */
			if(!get_magic_quotes_gpc()) {
				$username = addslashes($username);
			}
			
			/* Verify that user is in database */
			$q = sprintf("SELECT password FROM ".TBL_USERS." where username = '%s'",
            mysql_real_escape_string($username));
			$result = mysql_query($q, $this->connection);
			if(!$result || (mysql_numrows($result) < 1)){
				return 1; //Indicates username failure
			}
			
			/* Retrieve password from result, strip slashes */
			$dbarray = mysql_fetch_array($result);
			$dbarray['password'] = stripslashes($dbarray['password']);
			$password = stripslashes($password);
			
			/* Validate that password is correct */
			if($password == $dbarray['password']){
				return 0; //Success! Username and password confirmed
			}
			else{
				return 2; //Indicates password failure
			}
		}
		
		/**
			* confirmuser_id - Checks whether or not the given
			* username is in the database, if so it checks if the
			* given user_id is the same user_id in the database
			* for that user. If the user doesn't exist or if the
			* user_ids don't match up, it returns an error code
			* (1 or 2). On success it returns 0.
		*/
		function confirmuser_id($username, $user_id){
			/* Add slashes if necessary (for query) */
			if(!get_magic_quotes_gpc()) {
				$username = addslashes($username);
			}
			
			/* Verify that user is in database */
			$q = sprintf("SELECT user_id FROM ".TBL_USERS." WHERE username= '%s'",
            mysql_real_escape_string($username));
			$result = mysql_query($q, $this->connection);
			if(!$result || (mysql_numrows($result) < 1)){
				return 1; //Indicates username failure
			}
			
			/* Retrieve user_id from result, strip slashes */
			$dbarray = mysql_fetch_array($result);
			$dbarray['user_id'] = stripslashes($dbarray['user_id']);
			$user_id = stripslashes($user_id);
			
			/* Validate that user_id is correct */
			if($user_id == $dbarray['user_id']){
				return 0; //Success! Username and user_id confirmed
			}
			else{
				return 2; //Indicates user_id invalid
			}
		}
		
		/**
			* usernameTaken - Returns true if the username has
			* been taken by another user, false otherwise.
		*/
		function usernameTaken($username){
			if(!get_magic_quotes_gpc()){
				$username = addslashes($username);
			}
			$q = sprintf("SELECT username FROM ".TBL_USERS." WHERE username = '%s'",
            mysql_real_escape_string($username));
			$result = mysql_query($q, $this->connection);
			return (mysql_numrows($result) > 0);
		}
		
		
		/**
			* emailTaken - Returns true if the email has
			* been taken by another user, false otherwise.
		*/
		function emailTaken($email){
			if(!get_magic_quotes_gpc()){
				$email = addslashes($email);
			}
			$q = sprintf("SELECT email FROM ".TBL_USERS." WHERE email = '%s'",
            mysql_real_escape_string($email));
			$result = mysql_query($q, $this->connection);
			return (mysql_num_rows($result) > 0);
		}
		
		/**
			* usernameBanned - Returns true if the username has
			* been banned by the administrator.
		*/
		function usernameBanned($username){
			if(!get_magic_quotes_gpc()){
				$username = addslashes($username);
			}
			$q = sprintf("SELECT username FROM ".TBL_BANNED_USERS." WHERE username = '%s'",
            mysql_real_escape_string($username));
			$result = mysql_query($q, $this->connection);
			return (mysql_numrows($result) > 0);
		}
		
		/**
			* addNewUser - Inserts the given (username, password, email)
			* info into the database. Appropriate user level is set.
			* Returns true on success, false otherwise.
		*/
		function addNewUser($username, $password, $email, $user_id, $name, $CWID){
			$time = time();
			/* If admin sign up, give admin user level */
			if(strcasecmp($username, ADMIN_NAME) == 0){
				$ulevel = ADMIN_LEVEL;
				}else{
				$ulevel = USER_LEVEL;
			}
			$q = sprintf("INSERT INTO ".TBL_USERS." VALUES ('%s', '%s', '%s', '%s', '%s', $time, '0', '%s', '0', '0', NULL, '%s')",
            mysql_real_escape_string($username),
            mysql_real_escape_string($password),
            mysql_real_escape_string($user_id),
            mysql_real_escape_string(5),
            mysql_real_escape_string($email),
            mysql_real_escape_string($name),
			mysql_real_escape_string($CWID));
			return mysql_query($q, $this->connection);
			$log = "User Created:".$username."";
			$this->logIt($log);
		}
		
		/**
			* updateUserField - Updates a field, specified by the field
			* parameter, in the user's row of the database.
		*/
		function updateUserField($username, $field, $value){
			$q = sprintf("UPDATE ".TBL_USERS." SET $field = '$value' WHERE username = '$username'");
			$result = mysql_query($q, $this->connection);
			$log = "Updated user field: ".$field." of ".$username."";
			$this->logIt($log);
		}
		
		/**
			* getUserInfo - Returns the result array from a mysql
			* query asking for all information stored regarding
			* the given username. If query fails, NULL is returned.
		*/
		function getUserInfo($username){
			$q = sprintf("SELECT * FROM ".TBL_USERS." WHERE username = '%s'",
            mysql_real_escape_string($username));
			$result = mysql_query($q, $this->connection);
			/* Error occurred, return given name by default */
			if(!$result || (mysql_numrows($result) < 1)){
				return NULL;
			}
			/* Return result array */
			$dbarray = mysql_fetch_array($result);
			return $dbarray;
		}
		
		function getUserInfoFromHash($hash){
			$q = sprintf("SELECT * FROM ".TBL_USERS." WHERE hash = '%s'",
			mysql_real_escape_string($hash));
			$result = mysql_query($q, $this->connection);
			if(!$result || (mysql_num_rows($result) < 1)){
				return NULL;
			}
			$dbarray = mysql_fetch_array($result);
			return $dbarray;
		}
		
		
		/**
			* query - Performs the given query on the database and
			* returns the result, which may be false, true or a
			* resource identifier.
		*/
		function query($query){
			return mysql_query($query, $this->connection);
		}
		
		/**
			GET FUNCTIONS - Gets courses and rooms.
		*/
		function getCourses(){
			$q = sprintf("SELECT prefix,course_number,crn FROM ".TBL_COURSE."");
			$result = mysql_query($q, $this->connection);
			if(!$result || (mysql_num_rows($result) < 1)){
				return NULL;
			}
			$dbarray = mysql_fetch_array($result);
			return $dbarray;
		}
		
		function getRooms(){
			$q = sprintf("SELECT room_number,capacity,description FROM ".TBL_ROOMS."");
			$result = mysql_query($q, $this->connection);
			if(!$result || (mysql_num_rows($result) < 1)){
				return NULL;
			}
			$dbarray = mysql_fetch_array($result);
			return $dbarray;
		}
		/**
			Add Event Functions
			Handles all three pages of add event.
			If the name has "A" after it, it handles the events with repetition options.
		*/
        function addEvent2($title, $type, $course, $crn, $seats, $notes, $dateStart, $dateEnd, $room, $user, $series, $time, $conflict){
			if ($type == 0){
				$type = "Class";
				} else if ($type == 1){
				$type = "Clinical";
				} else if ($type == 2){
				$type = "Exam";
				} else if ($type == 3){
				$type = "Event";
			}
			$title = str_replace ( "'" , "\'" , $title );
			$notes = str_replace ( "'" , "\'" , $notes );
			$crn = substr($crn, 1);
			$crns = explode(" ",$crn);
			if ($conflict < 1){
				$approval = "approved";
				} else {
				$approval = "pending"; 
				$date = date('m/d/Y')." at ".date('g:i.s')." ".date('a');
				$message = "An event was created that needs your attention. Either the event conflicts with another, the room does not have sufficient capacity, or the event was created outside of the scheduled deadline. The event is ".$eventid." - ".$title.". <a href=\'./viewconflict.php?e=".$eventid."\'>Please click here for more information and to approve or reject the event.</a>.";
				$this->mailIt($message);
			}
			foreach ($crns as $c){
				$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, '$type', $c, $user, '$room', '$notes', $series, '$dateStart', '$dateEnd', '$time', '$approval')");
				//$myfile = fopen("error.txt", "a") or die(print_r($q));
				$result = mysql_query($q, $this->connection);
				//$myfile = fopen("error.txt", "a") or die(print_r($q));
			}
			$log = "Event created with no conflicts:".$title."";
			$this->logIt($log);
			if(!$result || (mysql_num_rows($result) < 1)){
				return NULL;
			}
			$dbarray = mysql_fetch_array($result);
			return TRUE;
			
		}
		
		function addEvent2A($title, $type, $course, $crn, $seats, $notes, $dateStart, $dateEnd, $room, $user, $series, $time, $repeat, $repeatm, $repeatt, $repeatw, $repeatth, $repeatf, $re, $conflict){
		//	$myfile = fopen("error.txt", "a") or die(print_r($conflict));
			$title = str_replace ( "'" , "\'" , $title );
			$notes = str_replace ( "'" , "\'" , $notes );
			$crn = substr($crn, 1);
			$crns = explode(" ",$crn);
			if ($type == 0){
				$type = "Class";
				} else if ($type == 1){
				$type = "Clinical";
				} else if ($type == 2){
				$type = "Exam";
				} else if ($type == 3){
				$type = "Event";
			}
			if ($conflict < 1){
				$approval = "approved";
				$log = "Event created with no conflicts:".$title."";
				$this->logIt($log);
				} else {
				$approval = "pending"; 
				$date = date('m/d/Y')." at ".date('g:i.s')." ".date('a');
				$message = "An event was created that needs your attention. Either the event conflicts with another, the room does not have sufficient capacity, or the event was created outside of the scheduled deadline. The event is ".$eventid." - ".$title.". <a href=\'./viewconflict.php?e=".$eventid."\'>Please click here for more information and to approve or reject the event.</a>.";
				$log = "Event created with conflicts: ".$title."";
				$this->logIt($log);
				$this->mailIt($message);
			}
			foreach ($crns as $c){
				$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, '$type', $c, $user, '$room', '$notes', $series, '$dateStart', '$dateEnd', '$time', '$approval')");				
				$result = mysql_query($q, $this->connection);				
				//$myfile = fopen("error.txt", "a") or die(print_r($re));
				$dateStartOriginal = new DateTime($dateStart,new \DateTimeZone('UTC'));
				$dateEndOriginal = new DateTime($dateEnd,new \DateTimeZone('UTC'));
				$re1 = new DateTime($re,new \DateTimeZone('UTC'));
				$dateStartOriginalHours = $dateStartOriginal->format('H');
				$dateStartOriginalMinutes = $dateStartOriginal->format('i');
				$dateEndOriginalHours = $dateEndOriginal->format('H');
				$dateEndOriginalMinutes = $dateEndOriginal->format('i');
				if ($repeatm == 1) {
					$dateStartA = clone $dateStartOriginal;
					$dateEndA = clone $dateEndOriginal;
					$dateStartA->modify('next monday');
					$dateEndA->modify('next monday');
					$dateEndA->setTime($dateEndOriginalHours, $dateEndOriginalMinutes);
					$dateStartA->setTime($dateStartOriginalHours, $dateStartOriginalMinutes);					
					while ($re1 > $dateStartA) {
						$dateStart1=$dateStartA->format('Y-m-d H:i:s');
						$dateEnd1=$dateEndA->format('Y-m-d H:i:s');
						$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, '$type', $c, $user, '$room', '$notes', $series, '$dateStart1', '$dateEnd1', '$time', '$approval')");
						//$myfile = fopen("error.txt", "a") or die(print_r($q));
						$result = mysql_query($q, $this->connection);	
						$dateStartA->modify('+7 day');
						$dateEndA->modify('+7 day');
					}
				} 
				
				if ($repeatt == 1){
					$dateStartA = clone $dateStartOriginal;
					$dateEndA = clone $dateEndOriginal;
					$dateStartA->modify('next tuesday');
					$dateEndA->modify('next tuesday');
					$dateEndA->setTime($dateEndOriginalHours, $dateEndOriginalMinutes);
					$dateStartA->setTime($dateStartOriginalHours, $dateStartOriginalMinutes);
					while ($re1 > $dateStartA) {
						$dateStart1=$dateStartA->format('Y-m-d H:i:s');
						$dateEnd1=$dateEndA->format('Y-m-d H:i:s');
						$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, '$type', $c, $user, '$room', '$notes', $series, '$dateStart1', '$dateEnd1', '$time', '$approval')");
						$result = mysql_query($q, $this->connection);
						$dateStartA->modify('+7 day');
						$dateEndA->modify('+7 day');
					}
				} 
				if ($repeatw == 1){
					
					$dateStartA = clone $dateStartOriginal;
					$dateEndA = clone $dateEndOriginal;
					$dateStartA->modify('next wednesday');
					$dateEndA->modify('next wednesday');
					$dateEndA->setTime($dateEndOriginalHours, $dateEndOriginalMinutes);
					$dateStartA->setTime($dateStartOriginalHours, $dateStartOriginalMinutes);
					while ($re1 > $dateStartA) {
						$dateStart1=$dateStartA->format('Y-m-d H:i:s');
						$dateEnd1=$dateEndA->format('Y-m-d H:i:s');
						$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, '$type', $c, $user, '$room', '$notes', $series, '$dateStart1', '$dateEnd1', '$time', '$approval')");
						$result = mysql_query($q, $this->connection);
						$dateStartA->modify('+7 day');
						$dateEndA->modify('+7 day');
					}
				} 
				if ($repeatth == 1){
					$dateStartA = clone $dateStartOriginal;
					$dateEndA = clone $dateEndOriginal;
					$dateStartA->modify('next thursday');
					$dateEndA->modify('next thursday');
					$dateEndA->setTime($dateEndOriginalHours, $dateEndOriginalMinutes);
					$dateStartA->setTime($dateStartOriginalHours, $dateStartOriginalMinutes);
					while ($re1 > $dateStartA) {
						$dateStart1=$dateStartA->format('Y-m-d H:i:s');
						$dateEnd1=$dateEndA->format('Y-m-d H:i:s');
						$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, '$type', $c, $user, '$room', '$notes', $series, '$dateStart1', '$dateEnd1', '$time', '$approval')");
						$result = mysql_query($q, $this->connection);
						$dateStartA->modify('+7 day');
						$dateEndA->modify('+7 day');
					}
				} 
				if ($repeatf == 1){
					$dateStartA = clone $dateStartOriginal;
					$dateEndA = clone $dateEndOriginal;
					$dateStartA->modify('next friday');
					$dateEndA->modify('next friday');
					$dateEndA->setTime($dateEndOriginalHours, $dateEndOriginalMinutes);
					$dateStartA->setTime($dateStartOriginalHours, $dateStartOriginalMinutes);
					while ($re1 > $dateStartA) {
						$dateStart1=$dateStartA->format('Y-m-d H:i:s');
						$dateEnd1=$dateEndA->format('Y-m-d H:i:s');
						$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, '$type', $c, $user, '$room', '$notes', $series, '$dateStart1', '$dateEnd1', '$time', '$approval')");
						$result = mysql_query($q, $this->connection);	
						$dateStartA->modify('+7 day');
						$dateEndA->modify('+7 day');
					}
				}
			}
			if(!$result || (mysql_num_rows($result) < 1)){
				return NULL;
			}
			$dbarray = mysql_fetch_array($result);
			return TRUE;
			
		}
				/**
			StudentCourses - Checks to see if a given student's CWID is in the personal schedule table.
		*/
		function studentCourses($CWID){
			$q = sprintf("SELECT * FROM ".TBL_SCHED." WHERE CWID=$CWID");
			$result = mysql_query($q, $this->connection);
			if(!$result || (mysql_num_rows($result) < 1)){
				return FALSE;
				} else {
				$dbarray = mysql_fetch_array($result);
				return $dbarray;
			}
		}
		
		function clearStudentSched($CWID){
			$q = sprintf("DELETE FROM ".TBL_SCHED." WHERE CWID=$CWID");
			$result = mysql_query($q, $this->connection);
			}
		function studentAdd($CWID, $crn){
			$this->clearStudentSched($CWID);
			foreach ($crn as $c){
				$q = sprintf("INSERT INTO `".TBL_SCHED."`(`CWID`, `crn`) VALUES ($CWID, $c);");
				$result = mysql_query($q, $this->connection);	
			}
			if ($result){
				return TRUE;
				} else {
				return FALSE;
			}
		}
		
		
		
		
		
		
		function editEventB($title, $type, $seats, $notes, $dateStart, $dateEnd, $room, $conflict, $eventid){
			if ($type == 0){
				$type = "Class";
				} else if ($type == 1){
				$type = "Clinical";
				} else if ($type == 2){
				$type = "Exam";
				} else if ($type == 3){
				$type = "Event";
			}
			$title = str_replace ( "'" , "\'" , $title );
			$notes = str_replace ( "'" , "\'" , $notes );
			if ($conflict < 0){
				$approval = "accepted";
				} else {
				$approval = "pending"; 
				$message = "An event was created that needs your attention. Either the event conflicts with another, the room does not have sufficient capacity, or the event was created outside of the scheduled deadline. The event is ".$eventid." - ".$title.". <a href=\'./viewconflict.php?e=".$eventid."\'>Please click here for more information and to approve or reject the event.</a>.";
				$this->mailIt($message);
			}
			$q = sprintf("Update ".TBL_EVENTS." set title = '$title', type = '$type', attendees = $seats, room_number = '$room', notes = '$notes', dateStart = '$dateStart', dateEnd = '$dateEnd', timeCreated = ".time().", status = '$approval'  where event_id = $eventid");
			$result = mysql_query($q);
			$log = "Event updated: ".$title."";
			$this->logIt($log);
			return TRUE;
			
		}
		
		
		
		function editEventC($eventid, $notes){
			$notes = str_replace ( "'" , "\'" , $notes );
			$q = sprintf("Update ".TBL_EVENTS." set notes = '$notes' where event_id = $eventid");
			$result = mysql_query($q, $this->connection);
			$log = "Event updated notes in event: ".$eventid."";
			$this->logIt($log);
			return TRUE;
			
		}
		
		
		function deleteEvent($eventid){
			$q = sprintf("DELETE FROM ".TBL_EVENTS." where event_id = $eventid");
			$result = mysql_query($q, $this->connection);
			$log = "Event deleted: ".$eventid."";
			$this->logIt($log);
			
			if(!$result || (mysql_num_rows($result) < 1)){
				return NULL;
			}
			$dbarray = mysql_fetch_array($result);
			return TRUE;
			
		}
		
		function reject($eventid){
			$info = mysql_query("SELECT * FROM ".TBL_EVENTS." e JOIN ".TBL_USERS." u ON e.CWID = u.CWID WHERE e.event_id=$eventid",$this->connection);
			$to = mysql_result($info,0,"username");
			$title = mysql_result($info,0,"title");
			$type = mysql_result($info,0,"type");
			$crn = mysql_result($info,0,"crn");
			$room_number = mysql_result($info,0,"room_number");
			$notes = mysql_result($info,0,"notes");
			$dateStart = mysql_result($info,0,"dateStart");
			$dateEnd = mysql_result($info,0,"dateEnd");
			$body = "The following event was rejected: <br> Title: $title <br> Type: $type <br> CRN: $crn <br> Room: $room_number <br> Notes: $notes <br> Start date/time: $dateStart <br> Ending date/time: $dateEnd <br><br>";
			$this->sendReply($to,'Your event has been rejected.',$body,'admin');
			$q = sprintf("DELETE FROM ".TBL_EVENTS." WHERE event_id = $eventid");
			$result = mysql_query($q, $this->connection);
			$log = "Event rejected & removed: ".$eventid."";
			$test = $this->logIt($log);
		}
		function approveAll($eventid){
			$q = sprintf("UPDATE ".TBL_EVENTS." SET status = 'approved' WHERE series = $eventid");
			$result = mysql_query($q, $this->connection);
			$log = "Event series approved: ".$eventid."";
			$test = $this->logIt($log);
		}
		function approve($eventid){
			$q = sprintf("UPDATE ".TBL_EVENTS." SET status = 'approved' WHERE event_id = $eventid");
			$result = mysql_query($q, $this->connection);
			$log = "Event approved: ".$eventid."";
			$test = $this->logIt($log);
		}
		function logIt($msg){
			global $session;
			$logQ = sprintf("INSERT INTO ".TBL_LOG." VALUES (NULL,".$session->CWID.",'$msg',NULL,'".$session->referrer."')");
			$result = mysql_query($logQ, $this->logCon);
			return $result;
		}
		
		function mailIt($msg){
			global $session;
			$date = date('m/d/Y')." at ".date('g:i.s')." ".date('a');
			$q2 = "INSERT INTO mail (UserTo, UserFrom, Subject, Message, SentDate, status) VALUES ('".SCHED_ADMIN."','admin','An event needs your attention.','$msg','$date','unread')";
			$mail = mysql_query($q2, $this->mailCon);
			return $mail;
		}
		
		function deleteMail($mail_id){
			$q = sprintf("DELETE FROM ".TBL_MAIL." where mail_id = $mail_id");
			$result = mysql_query($q, $this->connection);
			return TRUE;
			
		}
		function readMail($mail_id){
			$q = sprintf("UPDATE ".TBL_MAIL." SET status = 'read' where mail_id = $mail_id");
			$result = mysql_query($q, $this->connection);
			if(!$result || (mysql_num_rows($result) < 1)){
				return NULL;
			}
			$dbarray = mysql_fetch_array($result);
			return TRUE;
		}
		
		function sendReply($mailTo,$mailSubject,$mailBody,$mailFrom){
			global $session;
			$date = date('m/d/Y')." at ".date('g:i.s')." ".date('a');
			$q = "INSERT INTO mail (UserTo, UserFrom, Subject, Message, SentDate, status) VALUES ('$mailTo','$mailFrom','$mailSubject','$mailBody','$date','unread')";
			$result = mysql_query($q, $this->connection);
		}
	
	};
	
	
	
	/* Create database connection */
	$database = new MySQLDB;
	
	?>
		