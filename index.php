<!DOCTYPE html>
<?php
include_once("./db/constants.php");
if(isset($_SESSION["user_id"])) {
	header("location:".DOMAIN."/dashboard.php");
}

 ?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>GrowSusu&reg;</title>
		<script src="../files/script/jquery-3.3.1.min.js"></script>
		<script src="../files/script/jquery-3.3.1.slim.min.js"></script>
		<script src="../files/sa/node_modules/sweetalert/dist/sweetalert.min.js"></script>
		<script src="../files/script/popper.min.js"></script>
		<script src="../files/bootstrap/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="../files/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="../files/fonts/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script type="text/javascript" src="js/main.js"></script>
	</head>
	<body>
		<div class="overlay"><div class="loader"></div></div>
		<div class="container-fluid bg">
			<div class="row">
				<div class="col-md-4 col-sm-4 col-xs-12"></div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<!--form start-->
					<form class="form-container" id="login-form" onsubmit="return false">
						<h1 class="login_h1">Login</h1>
					  <div class="form-group">
					    <input type="email" class="form-control" id="login_email" name="login_email" placeholder="Email">
					    <small class="login-error"></small>
					  </div>
					  <div class="form-group">
					    <input type="password" class="form-control" id="login_pass" name="login_pass" placeholder="Password">
					  </div>
					  <!-- <div class="checkbox">
					    <label>
					      <input type="checkbox"> Remember me
					    </label>
					  </div> -->
					  <button type="submit" class="btn btn-primary btn-block">Submit</button>
					</form>

					<!--form end-->
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12"></div>
			</div>
		</div>		
	</body>
</html>