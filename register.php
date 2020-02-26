<!DOCTYPE html>
<?php 
include_once("./db/constants.php");
if(!isset($_SESSION["user_id"])) {
header("location:".DOMAIN."/index.php");
}
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
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
	<div class="content">
				<?php include_once("./templates/navbar.php"); ?>
		         <div class="container">
				<div class="card mx-auto" style="width: 30rem;">
				<div class="card-header"><h5>Register</h5></div>
				<div class="card-body">
				<form id="register_form" onsubmit="return false" autocomplete="off">
				<div class="form-group">
				<label for="username">Full Name</label>
			    <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username">
			    <small id="u_error" class="form-text text-muted"></small>
				</div>
			<div class="form-group">
				<label for="email">Email address</label>
				<input type="text" name="email" class="form-control" id="email"
				aria-describedby="emailHelp" placeholder="Email">
				<small id="e_error" class="form-text text-muted">We will never share your email with anyone else</small>
			</div>
				<div class="form-group">
				<label for="password1">Password</label>
				<input type="password" name="password1" class="form-control" id="password1" placeholder="Password">
				<small id="p1_error" class="form-text text-muted"></small>
			</div>
				<div class="form-group">
				<label for="password2">Re-Enter Password</label>
				<input type="password" name="password2" class="form-control" id="password2" placeholder="Password">
				<small id="p2_error" class="form-text text-muted"></small>
				</div>
				<div class="form-group">
				<label for="usertype">Usertype</label>
				<select name="usertype" class="form-control" id="usertype">
				<option value="">Choose Usertype</option>
				<option value="1">Admin</option>
				<option value="2">Other</option>
				</select>
				<small id="t_error" class="form-text text-muted"></small>
				</div>
				<button type="submit" name="user_register" class="btn btn-dark"><span class="fa fa-user"></span>&nbsp;Register</button>
				<span><a href="index.php" style="color:grey;">Back To Homepage</a></span>
			</form>
			</div>
			<div class="card-footer text-muted">
			</div>
			</div>
			</div>
		      </div>

	
</body>
</html>