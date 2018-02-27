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
			* confirmUserID - Checks whether or not the given
			* username is in the database, if so it checks if the
			* given userid is the same userid in the database
			* for that user. If the user doesn't exist or if the
			* userids don't match up, it returns an error code
			* (1 or 2). On success it returns 0.
		*/
		function confirmUserID($username, $userid){
			/* Add slashes if necessary (for query) */
			if(!get_magic_quotes_gpc()) {
				$username = addslashes($username);
			}
			
			/* Verify that user is in database */
			$q = sprintf("SELECT userid FROM ".TBL_USERS." WHERE username= '%s'",
            mysql_real_escape_string($username));
			$result = mysql_query($q, $this->connection);
			if(!$result || (mysql_numrows($result) < 1)){
				return 1; //Indicates username failure
			}
			
			/* Retrieve userid from result, strip slashes */
			$dbarray = mysql_fetch_array($result);
			$dbarray['userid'] = stripslashes($dbarray['userid']);
			$userid = stripslashes($userid);
			
			/* Validate that userid is correct */
			if($userid == $dbarray['userid']){
				return 0; //Success! Username and userid confirmed
			}
			else{
				return 2; //Indicates userid invalid
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
		function addNewUser($username, $password, $email, $userid, $name){
			$time = time();
			/* If admin sign up, give admin user level */
			if(strcasecmp($username, ADMIN_NAME) == 0){
				$ulevel = ADMIN_LEVEL;
				}else{
				$ulevel = USER_LEVEL;
			}
			$q = sprintf("INSERT INTO ".TBL_USERS." VALUES ('%s', '%s', '%s', '%s', '%s', $time, '0', '%s', '0', '0', NULL)",
            mysql_real_escape_string($username),
            mysql_real_escape_string($password),
            mysql_real_escape_string($userid),
            mysql_real_escape_string($ulevel),
            mysql_real_escape_string($email),
            mysql_real_escape_string($name));
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
			$q = sprintf("SELECT prefix,number,crn FROM ".TBL_COURSE."");
			$result = mysql_query($q, $this->connection);
			if(!$result || (mysql_num_rows($result) < 1)){
				return NULL;
			}
			$dbarray = mysql_fetch_array($result);
			return $dbarray;
		}
		
		function getRooms(){
			$q = sprintf("SELECT id,roomNum,capacity,description FROM ".TBL_ROOMS."");
			$result = mysql_query($q, $this->connection);
			if(!$result || (mysql_num_rows($result) < 1)){
				return NULL;
			}
			$dbarray = mysql_fetch_array($result);
			return $dbarray;
		}
		
		function getTypes(){
			$q = sprintf("SELECT id,title FROM ".TBL_TYPES."");
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
			$q = sprintf("INSERT INTO ".TBL_EVENTS." VALUES (NULL, '$title', $type, $course, $crn, $user, $room, $seats, '$notes', $series, '$dateStart', '$dateEnd', '$time', 1)");
			$result = mysql_query($q, $this->connection);
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
