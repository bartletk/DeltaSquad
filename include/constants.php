<?php
define("BASEURL", "http://18.219.124.71/");

/**
 * Database Constants - these constants are required
 * in order for there to be a successful connection
 * to the MySQL database. Make sure the information is
 * correct.
 */
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "gcLIvSDlyxV5");
define("DB_NAME", "nursing");

/**
 * Database Table Constants - these constants
 * hold the names of all the database tables used
 * in the script.
 */
define("TBL_USERS", "user");
define("TBL_MAIL", "mail");
define("TBL_COURSE", "course");
define("TBL_ROOMS", "room");
define("TBL_EVENTS", "event");
define("TBL_CRN", "section");
define("TBL_DEADLINES", "deadline");
define("TBL_SCHED", "personal_schedule");
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
define("MAIL", false);

// default view
$default_module = 2;

// How to display the titles on the header of the calendar
$week_titles[] = "Sunday";
$week_titles[] = "Monday";
$week_titles[] = "Tuesday";
$week_titles[] = "Wednesday";
$week_titles[] = "Thursday";
$week_titles[] = "Friday";
$week_titles[] = "Saturday";

//used with the quarter view
$week_titles_s[] = "Sun";
$week_titles_s[] = "Mon";
$week_titles_s[] = "Tue";
$week_titles_s[] = "Wed";
$week_titles_s[] = "Thu";
$week_titles_s[] = "Fri";
$week_titles_s[] = "Sat";

//used with the year view
$week_titles_ss[] = "S";
$week_titles_ss[] = "M";
$week_titles_ss[] = "T";
$week_titles_ss[] = "W";
$week_titles_ss[] = "T";
$week_titles_ss[] = "F";
$week_titles_ss[] = "S";

// The default start category for the event calendar
$start_category_id = 1;

// Language File
$language = "lang/en_us.php"; // I H8 U

// Day/week view start hour
$day_week_start_hour = 8;

// display sub-category events along with events in selected category
$include_child_categories = true;

// display events in parent category along with events in selected category
$include_parent_categories = true;
?>
