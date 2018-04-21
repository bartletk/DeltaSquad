<?php
include "top_header.php";
?>

<div class="container white z-depth-2">
	<div class="tablist" >
	<ul class="tabs teal">
		<li class="tab col s3"><a class="white-text active" href="#login">login</a></li>
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
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.js"></script>
<script>
  $( document ).ready(function(){
    $(".button-collapse").sideNav();
  })
</script>

<!-- Main body ends-->


<?php
include "footer.php";
?>