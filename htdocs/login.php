<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<!-- <link rel="stylesheet" type="text/css" href="css/form.css"> -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="css/loginAndRegister.css">
        
	</head>
	<body>
	</body>
	<header>
<nav>
            <div class="nav-wrapper">
              <a href="#!" class="brand-logo"><img src="ulmlogo.png" alt="logo" class="logo" ></a>
              <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
              <ul class="right hide-on-med-and-down">
                <li><a href="">Home</a></li>
                <li><a href="">About</a></li>
                <li><a href="">Login</a></li>
               
              </ul>
              <ul class="side-nav" id="mobile-demo">
                <li><a href="">Calendar</a></li>
                <li><a href="">About</a></li>
                <li><a href="">Login</a></li>
                
            </div>
          </nav>
</header>

<!-- Main body begins-->
<main>
<div class="container white z-depth-2">
	<div class="tablist" >
	<ul class="tabs teal">
		<li class="tab col s3"><a class="white-text active" href="#login">login</a></li>
		<li class="tab col s3"><a class="white-text" href="#register">register</a></li>
	</ul>
	</div>
	<div id="login" class="col s12">
		<form class="col s12" action="process.php" method="POST">
			<div class="form-container card-2" >
				<h3 class="teal-text">Welcome </h3>
				<div class="emailpass">
				<div class="row">
					<div class="input-field col s12">
						<input class="validate" type="text" required="" name="user" maxlength="30"  value="">
						<label for="username">Username</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<input class="validate" type="password" name="pass" required="" maxlength="30" value="">
						<label for="password">Password</label>

					</div>
				</div>
				</div>
				<br>
				<input type="hidden" name="remember" value="true">
				
				<input type="hidden" name="sublogin" value="1">
				<center>
					<button class="btn waves-effect waves-light teal" type="submit" name="action">Login</button>
					<br>
					<br>
			
				</center>
			</div>
		</form>
	</div>
	<div id="register" class="col s12">
		<form class="col s12">
			<div class="form-container card-2">
				<h3 class="teal-text">Welcome</h3>
				<div class="row">
					<div class="input-field col s6">
						<input id="first_name" type="text" class="validate">
						<label for="first_name">Name</label>
					</div>
					<div class="input-field col s6">
						<input id="user_name" type="text" class="validate">
						<label for="user_name">User Name</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<input id="email" type="email" class="validate">
						<label for="email">Email</label>
					</div>
				</div>
				
				<div class="row">
					<div class="input-field col s12">
						<input id="password" type="password" class="validate">
						<label for="password">Password</label>
					</div>
				</div>
				<input type="hidden" name="remember" value="true">
				<input type="hidden" name="sublogin" value="1">

				<center>
					<button class="btn waves-effect waves-light teal" value="login" type="submit" name="action" >Submit</button>
				</center>
			</div>
		</form>
	</div>
</div>
</main>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.js"></script>
<script>
  $( document ).ready(function(){
    $(".button-collapse").sideNav();
  })
</script>

<!-- Main body ends-->


<!-- Alsa code begins-->
<?php
	//include("header.php");
	//$page = "login.php";
		/**
			* User not logged in, display the login form.
			* If user has already tried to login, but errors were
			* found, display the total number of errors.
			* If errors occurred, they will be displayed.
		*/
	//	if($form->num_errors > 0){
	//		echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
	//	}
	//?>

<!-- 
			<input class="form-control" type="text" required="" name="user" placeholder="Username" maxlength="30" value=
			"<?php// echo $form->value("user"); ?>">
			<?php //echo $form->error("user"); ?>
	

			
			<input class="form-control" type="password" name="pass" placeholder="Password" required="" maxlength="30" value="
			<?php //echo $form->value("pass"); ?>"><?php //echo $form->error("pass"); ?>
			 -->
<!-- Alsa code end-->

<footer class="page-footer">
          <div class="footercontainer">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">ULM Nursing Calendar</h5>
                <p class="grey-text text-lighten-4">Never miss a class because we got you covered!</p>
              </div>
             
            </div>
          </div>
          <div class="footer-copyright">
            <div class="footercontainer">
            © 2018 DeltaSquad
            <a class="grey-text text-lighten-4 right" href="https://www.ulm.edu">ULM</a>
            </div>
    </div>
</footer>

</html>