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
		<script src="../files/script/Chart.min.js"></script>
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
		<script type="text/javascript" src="js/graph.js"></script>
		
	</head>
	<body>
		<div class="wrapper">
			<?php include_once("./templates/sidebar.php");?>
			<div class="content">
				<?php include_once("./templates/navbar.php");?>
		          <!--Main Content -->
		          <div class="container-fluid">
		          	<!-- Breadcrumbs-->
		        <ol class="breadcrumb">
		          <li class="breadcrumb-item">
		            <a href="#">Chart of Accounts</a>
		          </li>
		          <li class="breadcrumb-item active">Charts</li>
		        </ol>

		          </div>
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
	</body>
</html>