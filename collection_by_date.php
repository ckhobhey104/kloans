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
		<div class="overlay"><div class="loader"></div></div>
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
			<input class="form-control" type="text" id="search_coll_by_date" name="search_coll_by_date" placeholder="Search Person">
		</div>
		<div id="feedback"></div>
		<br/>
		<form id="coll_by_date_form" onsubmit="return false">
			<div class="row">
				<div class=" col-md-3">
					<label>From</label>
					<input type="text" id="coll_beg_date" name="coll_beg_date" required/>
				</div>
				<div class=" col-md-3">
					<label>To</label>
					<input type="text" id="coll_end_date" name="coll_end_date" required/>
				</div>
				<div class="col-md-3">
					<label>&nbsp;</label>
					<select id="coll_by_date_option" name="coll_by_date_option" class="form-control" required/>
						<option value="">Choose Report Type</option>
						<option value="DAILY COLLECTION REPORT">DAILY COLLECTION REPORT</option>
					</select>
				</div>
				<div class="col-md-2">
					<label>&nbsp;</label>
						<button type="submit" class="btn btn-block btn-outline-primary">Go</button>
		<!-- 		  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    Choose Action
				  </button>
				  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				    <a class="dropdown-item showAccountDaily" href="#">Show Table</a>
				    <a class="dropdown-item exportToExcel" href="#">Export To Excel</a>
				  </div> -->
				</div>
		</div>
		</form> 
		<br/>            
		<table class="table table-hover table-condensed table-bordered" id="get_collection_by_date">
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">Serial No.</th>
		      <th scope="col">Name</th>
		      <th scope="col">Date</th>
		      <th scope="col">Amount</th>
		      <th scope="col">Action.</th>
		    </tr>
		  </thead>
		  <tbody id="get_coll_by_date">
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
      <?php include_once("./templates/editClient.php");?>
      <?php include_once("./templates/editDailyCollections.php");?>
	</body>
</html>