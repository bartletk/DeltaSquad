<?php
include("top_header.php");
include("header.php");
$page = "userinfo.php";
?>

<div class="row">
        <div class="col s12 m6">
          <div class="card">
            <div class="card-content black-text">
              <span class="card-title"><h5>My Account</h5></span>
              <p><strong>Name:</strong> admin</p>
              <p><strong>Username:</strong> admin</p>
              <p><strong>Email</strong>: admin@admin.com</p>
            </div>
            <div class="card-action">
              <a href="#" class="link-color">Edit Account</a>
            </div>
          </div>
        </div>
      </div>
<hr>

  <!--Import jQuery before materialize.js-->
  <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.js"></script>
          <script>
              $( document ).ready(function(){
                $(".button-collapse").sideNav();


              })
          </script>


<div>
<?php
/* Requested Username error checking */
$req_user = trim($_GET['user']);
if(!$req_user || strlen($req_user) == 0 ||
   !preg_match("/^([0-9a-z])+$/i", $req_user) ||
   !$database->usernameTaken($req_user)){
   die("Username not registered");
}

if(MAIL){
 $q = "SELECT mail_id FROM ".TBL_MAIL." WHERE UserTo = '$session->username' and status = 'unread'";
 $numUnreadMail = $database->query($q) or die(mysql_error());
 $numUnreadMail = mysql_num_rows($numUnreadMail);
 
 echo "<div class='grid_5'><p class='right'>[<a href=\"mail.php\">You have $numUnreadMail Unread Mail</a>]&nbsp;</p></div>";
 echo "<div class='clear'></div>";
}

/* Logged in user viewing own account */
if(strcmp($session->username,$req_user) == 0){
   echo "<h1>My Account</h1>";
}
/* Visitor not viewing own account */
else{
   echo "<h1>User Info</h1>";
}

/* Display requested user information */
$req_user_info = $database->getUserInfo($req_user);

/* Name */
echo "<p><b>Name: ".$req_user_info['name']."</b><br />";

/* Username */
echo "<p><b>Username: ".$req_user_info['username']."</b><br />";

/* Email */
echo "<b>Email:</b> ".$req_user_info['email']."</p>";

/**
 * Note: when you add your own fields to the users table
 * to hold more information, like homepage, location, etc.
 * they can be easily accessed by the user info array.
 *
 * $session->user_info['location']; (for logged in users)
 *
 * ..and for this page,
 *
 * $req_user_info['location']; (for any user)
 */

/* If logged in user viewing own account, give link to edit */
if(strcmp($session->username,$req_user) == 0){
   echo "<a href=\"useredit.php\">Edit Account Information</a><br><br>";
}
?>
</div>
<?php
include("footer.php");
?>

</body>
</html>
