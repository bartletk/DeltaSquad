<?php
	include("constants.php");
	
	class MySQLDB
	{
		var $connection;         //The MySQL database connection
		var $num_active_users;   //Number of active users viewing site
		var $num_active_guests;  //Number of active guests viewing site
		var $num_members;        //Number of signed-up users
		/* Note: call getNumMembers() to access $num_members! */
		
		/* Class constructor */
		function MySQLDB(){
			/* Make connection to database */
			$this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
			mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
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
            mysql_real_escape_string($ulevel),
            mysql_real_escape_string($email),
            mysql_real_escape_string($name),
			mysql_real_escape_string($CWID));
			return mysql_query($q, $this->connection);
		}
		
		/**
			* updateUserField - Updates a field, specified by the field
			* parameter, in the user's row of the database.
		*/
		function updateUserField($username, $field, $value){
			$q = sprintf("UPDATE ".TBL_USERS." SET %s = '%s' WHERE username = '%s'",
            mysql_real_escape_string($field),
            mysql_real_escape_string($value),
            mysql_real_escape_string($username));
			return mysql_query($q, $this->connection);
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
			GET FUNCTIONS - gets crap from the database - mostly used in forms
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
		
        function addEvent2($title, $type, $course, $crn, $seats, $notes, $dateStart, $dateEnd, $room, $user, $series, $time){
			$title = str_replace ( "'" , "\'" , $title );
			$notes = str_replace ( "'" , "\'" , $notes );
			$crn = substr($crn, 1);
			$crns = explode(" ",$crn);
			foreach ($crns as $c){
				$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, $type, $c, $user, '$room', '$notes', $series, '$dateStart', '$dateEnd', '$time', 'accepted')");
				$result = mysql_query($q, $this->connection);
				//$myfile = fopen("error.txt", "a") or die(print_r($q));
			}
			if(!$result || (mysql_num_rows($result) < 1)){
				return NULL;
			}
			$dbarray = mysql_fetch_array($result);
			return TRUE;
		}
		
		function addEvent2A($title, $type, $course, $crn, $seats, $notes, $dateStart, $dateEnd, $room, $user, $series, $time, $repeat, $repeatm, $repeatt, $repeatw, $repeatth, $repeatf, $re){
			$title = str_replace ( "'" , "\'" , $title );
			$notes = str_replace ( "'" , "\'" , $notes );
			$crn = substr($crn, 1);
			$crns = explode(" ",$crn);
			foreach ($crns as $c){
				$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, $type, $c, $user, '$room', '$notes', $series, '$dateStart', '$dateEnd', '$time', 'accepted')");				
				$result = mysql_query($q, $this->connection);				
				//$myfile = fopen("error.txt", "a") or die(print_r($q));
				$dateStartOriginal = new DateTime($dateStart,new \DateTimeZone('UTC'));
				$dateEndOriginal = new DateTime($dateEnd,new \DateTimeZone('UTC'));
				$re1 = new DateTime($re,new \DateTimeZone('UTC'));
				$dateStartOriginalHours = $dateStartOriginal->format('h');
				$dateStartOriginalMinutes = $dateStartOriginal->format('i');
				$dateEndOriginalHours = $dateEndOriginal->format('h');
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
						$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, $type, $c, $user, '$room', '$notes', $series, '$dateStart1', '$dateEnd1', '$time', 'accepted')");
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
						$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, $type, $c, $user, '$room', '$notes', $series, '$dateStart1', '$dateEnd1', '$time', 'accepted')");
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
						$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, $type, $c, $user, '$room', '$notes', $series, '$dateStart1', '$dateEnd1', '$time', 'accepted')");
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
						$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, $type, $c, $user, '$room', '$notes', $series, '$dateStart1', '$dateEnd1', '$time', 'accepted')");
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
						$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $seats, $type, $c, $user, '$room', '$notes', $series, '$dateStart1', '$dateEnd1', '$time', 'accepted')");
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
		
		
		
		
	};
	
	
	
	/* Create database connection */
	$database = new MySQLDB;
	
?>
