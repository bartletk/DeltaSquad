<?php
define("BASEURL", "http://18.219.59.60/");

/**
 * Database Constants - these constants are required
 * in order for there to be a successful connection
 * to the MySQL database. Make sure the information is
 * correct.
 */
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "wp8dqoJzgg9x");
define("DB_NAME", "nursing");

/**
 * Database Table Constants - these constants
 * hold the names of all the database tables used
 * in the script.
 */
define("TBL_USERS", "users");
define("TBL_MAIL", "mail");
define("TBL_COURSE", "course");
define("TBL_ROOMS", "rooms");
define("TBL_TYPES", "types");

/**
 * Special Names and Level Constants - the admin
 * page will only be accessible to the user with
 * the admin name and also to those users at the
 * admin user level. Feel free to change the names
 * and level constants as you see fit, you may
 * also add additional level specifications.
 * Levels must be digits between 0-9.
 */
define("ADMIN_NAME", "admin");
define("GUEST_NAME", "student");
define("ADMIN_LEVEL", 9);
define("INSTRUCTOR_LEVEL", 5); // instructor
define("USER_LEVEL",  1); // student
define("GUEST_LEVEL", 0); 


/**
 * Cookie Constants - these are the parameters
 * to the setcookie function call, change them
 * if necessary to fit your website. If you need
 * help, visit www.php.net for more info.
 * <http://www.php.net/manual/en/function.setcookie.php>
 */
define("COOKIE_EXPIRE", 60*60*24*100);  //100 days by default
define("COOKIE_PATH", "/");  //Avaible in whole domain

/**
 * Email Constants - these specify what goes in
 * the from field in the emails that the script
 * sends to users, and whether to send a
 * welcome email to newly registered users.
 */
define("EMAIL_FROM_NAME", "Nursing Scheduler");
define("EMAIL_FROM_ADDR", "alymcmurray@gmail.com");
define("EMAIL_WELCOME", false);

/**
 * This constant forces all users to have
 * lowercase usernames, capital letters are
 * converted automatically.
 */
define("ALL_LOWERCASE", false);

/**
 * This defines the absolute path
 */
define("ABSPATH", dirname(__FILE__).'/');

/**
 * This boolean constant controls wheter or
 * not the user to user mail function is active
 */
define("MAIL", false)
?>
