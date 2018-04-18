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

  <nav>
            <div class="nav-wrapper">
              <a href="#!" class="brand-logo"><img src="ulmlogo.png" alt="logo" class="logo" ></a>
              <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
              <ul class="right hide-on-med-and-down">
              
                <li><a href="">My Account</a></li>
                <li><a href="">Edit Account</a></li>
                <li><a href="">Admin Center</a></li>
                <li><a href="">Add Event</a></li>
                <li><a href="index.php">Calendar</a></li>
                <li><a href="process.php">Logout</a></li>
               
              </ul>
              <ul class="side-nav" id="mobile-demo">
                
                <li><a href="">My Account</a></li>
                <li><a href="">Edit Account</a></li>
                <li><a href="">Admin Center</a></li>
                <li><a href="">Add Event</a></li>
                <li><a href="">Calendar</a></li>
                <li><a href="">Logout</a></li>
                
            </div>
  </nav>

<div class="card card-2">
  <h6><strong>Logged In.</strong></h6>
    Welcome <strong>admin</strong>, you are logged in.
</div>

  <!--Import jQuery before materialize.js-->
  <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.js"></script>
          <script>
              $( document ).ready(function(){
                $(".button-collapse").sideNav();

              })
          </script>

