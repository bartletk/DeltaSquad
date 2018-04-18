<html>

<head>
  <!--Import Google Icon Font-->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" type="text/css" href="userinfo.css">
  <link rel="stylesheet" type="text/css" href="navbar.css">
</head>

<body>

<div class="card card-2">
  <h5><strong>Logged In.</strong></h5>
    Welcome <strong>admin</strong>, you are logged in.
</div>
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
</body>

</html>

<?php
include('footer.php');
?>