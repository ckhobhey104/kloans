<!DOCTYPE html>
<?php 
include_once("./db/constants.php");
if(!isset($_SESSION["user_id"])) {
header("location:".DOMAIN."/index.php");
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
		<link rel="stylesheet" href="../files/bootstrap/datepicker/css/gijgo.min.css">
		<link rel="stylesheet" href="../files/fonts/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script type="text/javascript" src="../files/bootstrap/datepicker/js/gijgo.min.js"></script>
		<script type="text/javascript" src="js/gigjo_dates.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
		<script type="text/javascript" src="js/manage.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<?php include_once("./templates/sidebar.php");?>
			<div class="content">
				<?php include_once("./templates/navbar.php");?>
		          <!--Main Content -->
<div class="container">
			<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><i class="fa fa-search fa-fw"></i></span>
		</div>
			<input class="form-control" type="text" id="search_client" name="search_client" placeholder="Search Person">
		</div>
		<div id="feedback"></div>
		<br/>             
		<table class="table table-hover table-condensed table-bordered">
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">Serial No.</th>
		      <th scope="col">Name</th>
		      <th scope="col">Location</th>
		      <th scope="col">Phone No.</th>
		      <th scope="col">Action.</th>
		    </tr>
		  </thead>
		  <tbody id="make_collection">
		   <!--  <tr>
		      <th scope="row">1</th>
		      <td>PERSONAL INCOME TAX</td>
		      <td></td>
		      <td>
		      	<a href="#" class="btn btn-info"><i class="fa fa-edit">&nbsp;</i>Edit</a>
		        <a href="#" class="btn btn-danger"><i class="fa fa-eraser">&nbsp;</i>Delete</a>
		      </td>
		    </tr>
		    <tr>
		      <th scope="row">2</th>
		      <td>COMPANY INCOME TAX</td>
		      <td></td>
		      <td>
		      	<a href="#" class="btn btn-info"><i class="fa fa-edit">&nbsp;</i>Edit</a>
		        <a href="#" class="btn btn-danger"><i class="fa fa-eraser">&nbsp;</i>Delete</a>
		      </td>
		    </tr> -->
		    
		  </tbody>
		</table>
	</div>

		</div><!--End wrapper-->


		<script>

       $(document).ready(function(){

      	$('#sidebarCollapse').on('click',function(e){
        	e.preventDefault();
          $('#sidebar').toggleClass('active');
        });
       })
	
    </script>
    <?php include_once("./templates/addLocation.php");?>
     <?php include_once("./templates/addClient.php");?>
      <?php include_once("./templates/editLocation.php");?>
      <?php include_once("./templates/makeCollection.php");?>
	</body>
</html>