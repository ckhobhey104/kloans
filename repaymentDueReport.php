<!DOCTYPE html>
<?php 
include_once("./db/constants.php");
if(!isset($_SESSION["user_id"])) {
header("location:".DOMAIN."/index.php");
}
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Growers Loans&reg;</title>
		<script src="../files/script/popper.min.js"></script>
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
		<script type="text/javascript" src="js/loan_js/loan_main.js"></script>
		<script type="text/javascript" src="js/loan_js/loan_manage.js"></script>
		<script type="text/javascript" src="js/loan_js/loan_reports.js"></script>
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
			<input class="form-control" type="text" id="search_repayment_due" name="search_repayment_due" placeholder="Search By Name/Serial No.">
		</div>
		<div id="feedback"></div>
		<br/>  
				<form id="repaymentDueForm" onsubmit="return false">
			<div class="row">
				<div class=" col-md-3">
					<label for="loans_due_beg_date">From</label>
					<input type="text" id="loans_due_beg_date" name="loans_due_beg_date" required/>
				</div>
				<div class=" col-md-3">
					<label for="loans_due_end_date">To</label>
					<input type="text" id="loans_due_end_date" name="loans_due_end_date" required/>
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
		<br />          
		<table class="table table-hover table-condensed viewDisbursalTbl table-bordered">
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">Date</th>
		      <th scope="col">Client</th>
		      <th scope="col">Loan Purpose</th>
		      <th scope="col">Total Amount(Ghc)</th>
		      <th scope="col">Amount Paid(Ghc)</th>
		      <th scope="col">Amount Remaining(Ghc)</th>
		      <th scope="col">Action</th>
		    </tr>
		  </thead>
		  <tbody id="repayment_due">
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
    <?php include_once("templates/addLocation.php");?>
	<?php include_once("templates/addClient.php");?>
	<?php include_once("templates/disburse_loan.php");?>
	<?php include_once("templates/repay_loan.php");?>
	</body>
</html>