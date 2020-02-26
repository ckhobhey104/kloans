<?php
include_once("../../db/constants.php");
include_once("../user.php");
include_once("./loan_DBOperations.php");
include_once("./loan_manage.php");
include_once("./loan_reports.php");

//SHOW CLIENTS FOR LOAN DISBURSAL
if(isset($_POST["disburseLoan"])){
	$pno = 1;
	$m = new Manage();
	$result = $m->showTable("clients");
	$rows = $result;
	if(is_array($rows)) {
		if(count($rows) > 0){
			$n = (($pno-1) * 10)+1;
			foreach ($rows as $row) {
				?>
				<tr>
		      <th scope="row"><?php echo $n;?></th>
		      <td><?php echo $row["client_serial"];?></td>
		      <td><?php echo $row["client_name"];?></td>
		      <td><?php echo $row["location_name"];?></td>
		      <td><?php echo $row["client_phoneno"];?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['client_id']; ?>" data-toggle="modal" data-target="#disburseLoanModal" class="btn btn-primary btn-sm disburse_loan"><i class="fa fa-check">&nbsp;</i>Give Loan</a>
		      </td>
		    </tr>

		<?php
		$n++;
			}
		exit();
		}
	}

}
//SEARCH CLIENT
   if(isset($_GET['client_search'])){
       $m = new Manage();
       $s = $_GET['client_search'];
       $pno = $_GET['pageno'];
       $result = $m->searchClients('clients',$s);
       $rows = $result;

       $n = ((($pno)-1)*10) +1;
       if (is_array($rows)) {
         if(count($rows)>0) {
         foreach ($rows as $row) {
         ?>
         <tr>
         <th scope="row"><?php echo $n;?></th>
		 <td><?php echo $row["client_serial"];?></td>
		 <td><?php echo $row["client_name"];?></td>
		 <td><?php echo $row["location_name"];?></td>
		 <td><?php echo $row["client_phoneno"];?></td>
		 <td>
		 <a href="#" eid="<?php echo $row['client_id']; ?>" data-toggle="modal" data-target="#disburseLoanModal" class="btn btn-primary btn-sm disburse_loan"><i class="fa fa-plus">&nbsp;</i>Give Loan</a>
		 </td>        
      </tr>
         
         <?php
         $n ++;
         }
     
       }

       } else {
         $rows = "";
         $rows.= "<tr>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="</tr>";
         echo $rows;
       }
      
      exit();

   }
   //DISBURSE LOAN
   if(isset($_POST["disburse_loan"])){
	$m = new Manage();
	$result = $m->getSingleRecord("clients","client_id",$_POST["id"]);
	echo json_encode($result);
	exit();
}
if(isset($_POST["loan_purpose"]) && isset($_POST["disbursal_date"])){
	$obj = new loanDBOperations();
	$loanName = $_POST["loan_purpose"];
	$clientID = $_POST["client_id"];
	$disbursementDate = $_POST["disbursal_date"];
	$principal =$_POST["loan_principal"];
	$loanTerm = $_POST["loan_term"];
	$loanRate = $_POST["loan_interest"];
	$startPaymentDate = $_POST["start_payment"];
	$paymentInterval= $_POST["interval"];
	$result=$obj->disburseLoan($loanName,$clientID,$disbursementDate,$principal,$loanTerm,$loanRate,$startPaymentDate,$paymentInterval);
	echo $result;
	exit();
}
//VIEW DISBURSAL
if(isset($_POST["view_disbursal"])){
	$pno = 1;
	$m = new Manage();
	$result = $m->showTable("active_loans");
	$rows = $result;
	if(is_array($rows)) {
		if(count($rows) > 0){
			$n = (($pno-1) * 10)+1;
			foreach ($rows as $row) {
				$interest = $row["total_amount"]-$row["loan_principal"];
				?>
				<tr>
		      <th scope="row"><?php echo $n;?></th>
		      <td><?php echo $row["active_loan_name"];?></td>
		      <td><?php echo $row["client_name"];?></td>
		      <td><?php echo $row["payment_interval"];?></td>
		      <td><?php echo sprintf('%0.2f',$row["loan_principal"]);?></td>
		      <td><?php echo sprintf('%0.2f',$interest);?></td>
		      <td><?php echo sprintf('%0.2f',$row["instalment_amount"]);?></td>
		      <td><?php echo sprintf('%0.2f',$row["total_amount"]);?></td>
		      <td><?php echo sprintf('%0.2f',$row["amount_paid"]);?></td>
		      <td><?php echo sprintf('%0.2f',$row["amount_remaining"]);?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['active_loan_id']; ?>" data-toggle="modal" data-target="#editDisburseLoanModal" class="btn btn-success btn-sm view_loan"><i class="fa fa-plus">&nbsp;</i>View</a>
		      	<a href="#" did="<?php echo $row['active_loan_id']; ?>" class="btn btn-danger btn-sm del_loan"><i class="fa fa-trash">&nbsp;</i>Delete</a>
		      </td>
		    </tr>

		<?php
		$n++;
			}
		exit();
		}
	}

}
//SEARCH VIEW DISBURSAL
if(isset($_GET['search_view_disbursal'])){
       $m = new Manage();
       $s = $_GET['search_view_disbursal'];
       $pno = 1;
       $result = $m->searchClients('active_loans',$s);
       $rows = $result;

       $n = ((($pno)-1)*10) +1;
       if (is_array($rows)) {
         if(count($rows)>0) {
         foreach ($rows as $row) {
         	$interest = $row["total_amount"]-$row["loan_principal"];
         ?>
          <tr>
		      <th scope="row"><?php echo $n;?></th>
		      <td><?php echo $row["active_loan_name"];?></td>
		      <td><?php echo $row["client_name"];?></td>
		      <td><?php echo $row["payment_interval"];?></td>
		      <td><?php echo sprintf('%0.2f',$row["loan_principal"]);?></td>
		      <td><?php echo sprintf('%0.2f',$interest);?></td>
		      <td><?php echo sprintf('%0.2f',$row["instalment_amount"]);?></td>
		      <td><?php echo sprintf('%0.2f',$row["total_amount"]);?></td>
		      <td><?php echo sprintf('%0.2f',$row["amount_paid"]);?></td>
		      <td><?php echo sprintf('%0.2f',$row["amount_remaining"]);?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['active_loan_id']; ?>" data-toggle="modal" data-target="#editDisburseLoanModal" class="btn btn-success btn-sm view_loan"><i class="fa fa-plus">&nbsp;</i>View</a>
		      	<a href="#" did="<?php echo $row['active_loan_id']; ?>"  class="btn btn-danger btn-sm del_loan"><i class="fa fa-trash">&nbsp;</i>Delete</a>
		      </td>
		    </tr>

         
         <?php
         $n ++;
         }
     
       }

       } else {
         $rows = "";
         $rows.= "<tr>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="</tr>";
         echo $rows;
       }
      
      exit();

   }
   //DELETE DISBURSAL
   if (isset($_POST["delete_disbursal"])) {
   	$obj = new loanDBOperations();
   	$result= $obj->deleteRecord("active_loans","active_loan_id",$_POST["id"]);
   	echo $result;
   	exit();
   }

   //VIEW SINGLE DISBURSAL
 if(isset($_POST["edit_disbursal"]) && isset($_POST["id"])){
   	$m = new Manage();
	$result = $m->getSingleRecord("active_loans","active_loan_id",$_POST["id"]);
	echo json_encode($result);
	exit();
   }

   //EDIT/UPDATE DISBURSAL
   if(isset($_POST["edit_loan_purpose"]) && isset($_POST["edit_disbursal_date"])){
   	$obj = new loanDBOperations();
   	$loanName = $_POST["edit_loan_purpose"];
	$disbursementDate = $_POST["edit_disbursal_date"];
	$principal =$_POST["edit_loan_principal"];
	$loanTerm = $_POST["edit_loan_term"];
	$loanRate = $_POST["edit_loan_interest"];
	$startPaymentDate = $_POST["edit_start_payment"];
	$paymentInterval= $_POST["edit_interval"];
	$activeLoanID=$_POST["edit_active_loan_id"];
	$result = $obj->updateDisbursement($activeLoanID,$loanName,$disbursementDate,$principal,$loanTerm,$loanRate,$startPaymentDate,$paymentInterval);
	echo $result;
	exit();
   }
   //LOAN REPAYMENT
if(isset($_POST["loan_repayment"])){
	$pno = 1;
	$m = new Manage();
	$result = $m->showTable("active_loans");
	$rows = $result;
	if(is_array($rows)) {
		if(count($rows) > 0){
			$n = (($pno-1) * 10)+1;
			foreach ($rows as $row) {
				$interest = $row["total_amount"]-$row["loan_principal"];
				?>
				<tr>
		      <th scope="row"><?php echo $n;?></th>
		      <td><?php echo $row["active_loan_name"];?></td>
		      <td><?php echo $row["client_name"];?></td>
		      <td><?php echo $row["payment_interval"];?></td>
		      <td><?php echo $row["loan_principal"];?></td>
		      <td><?php echo $interest;?></td>
		      <td><?php echo $row["total_amount"];?></td>
		      <td><?php echo $row["amount_paid"];?></td>
		      <td><?php echo $row["amount_remaining"];?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['active_loan_id']; ?>" data-toggle="modal" data-target="#repayLoanModal" class="btn btn-primary btn-sm repay_loan"><i class="fa fa-eraser">&nbsp;</i>Repay Loan</a>
		      </td>
		    </tr>

		<?php
		$n++;
			}
		exit();
		}
	}

}

//SEARCH CLIENT FOR REPAYMENT
   if(isset($_GET['search_client_for_repayment'])){
       $m = new Manage();
       $s = $_GET['search_client_for_repayment'];
       $pno = 1;
       $result = $m->searchClients('loan_repayment',$s);
       $rows = $result;

       $n = ((($pno)-1)*10) +1;
       if (is_array($rows)) {
         if(count($rows)>0) {
         foreach ($rows as $row) {
         	$interest = $row["total_amount"]-$row["loan_principal"];
         ?>
         <tr>
         <th scope="row"><?php echo $n;?></th>
		 <td><?php echo $row["active_loan_name"];?></td>
		      <td><?php echo $row["client_name"];?></td>
		      <td><?php echo $row["payment_interval"];?></td>
		      <td><?php echo $row["loan_principal"];?></td>
		      <td><?php echo $interest;?></td>
		      <td><?php echo $row["total_amount"];?></td>
		      <td><?php echo $row["amount_paid"];?></td>
		      <td><?php echo $row["amount_remaining"];?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['active_loan_id']; ?>" data-toggle="modal" data-target="#repayLoanModal" class="btn btn-primary btn-sm repay_loan"><i class="fa fa-eraser">&nbsp;</i>Repay Loan</a>
		      </td>       
      </tr>
         
         <?php
         $n ++;
         }
     
       }

       } else {
         $rows = "";
         $rows.= "<tr>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="</tr>";
         echo $rows;
       }
      
      exit();

   }
    if(isset($_POST["repay_loan"]) && isset($_POST["id"])){
   	$m = new Manage();
	$result = $m->getSingleRecord("loan_repayment","active_loan_id",$_POST["id"]);
	echo json_encode($result);
	exit();

   }
    //REPAY LOAN
if(isset($_POST["payment_date"]) && isset($_POST["payment_amount"])){
	$obj = new loanDBOperations();
	$activeLoanID = $_POST["active_loan_id"];
	$amountPaid = $_POST["payment_amount"];
	$paymentDate = $_POST["payment_date"];
	$oldPaymentDate = $_POST["old_payment_date"];
	$paymentInterval= $_POST["payment_interval"];
	$notes =$_POST["payment_notes"];
	$result=$obj->loanRepayment($activeLoanID,$amountPaid,$paymentDate,$paymentInterval,$oldPaymentDate,$notes);
	echo $result;
	exit();
}
 //SHOW PAYMENTS TO EDIT AND DELETE
if(isset($_POST["view_repayment"])){
	$pno = 1;
	$m = new Manage();
	$result = $m->showTable("payments");
	$rows = $result;
	if(is_array($rows)) {
		if(count($rows) > 0){
			$n = (($pno-1) * 10)+1;
			foreach ($rows as $row) {
				?>
				<tr>
		      <th scope="row"><?php echo $n;?></th>
		      <td><?php echo $row["client_serial"];?></td>
		      <td><?php echo $row["client_name"];?></td>
		      <td><?php echo $row["payment_date"];?></td>
		      <td><?php echo $row["payment_amount"];?></td>
		      <td><?php echo $row["payment_notes"];?></td>

		      <td>
		      	<a href="#" eid="<?php echo $row['payment_id']; ?>" data-toggle="modal" data-target="#editLoanRepaymentModal" class="btn btn-info btn-sm edit_repayment"><i class="fa fa-plus">&nbsp;</i>View</a>
		      	<a href="#" did="<?php echo $row['payment_id']; ?>" aid="<?php echo $row['active_loan_id'];?>" class="btn btn-danger btn-sm del_repayment"><i class="fa fa-trash">&nbsp;</i>Reverse</a>
		      </td>
		    </tr>

		<?php
		$n++;
			}
		exit();
		}
	}

}
//VIEW PAYMENTS
 if(isset($_POST["edit_payment"]) && isset($_POST["id"])){
   	$m = new Manage();
	$result = $m->getSingleRecord("payments","payment_id",$_POST["id"]);
	echo json_encode($result);
	exit();

   }
   //UPDATE PAYMENT RECORD
if(isset($_POST["edit_payment_id"])) {
	$obj = new loanDBOperations();
	$id = $_POST["edit_payment_id"];
	$payment_date = $_POST["edit_payment_date"];
	$payment_amount = $_POST["edit_payment_amount"];
	$payment_notes = $_POST["edit_payment_notes"];
	$result = $obj->updateRecord("payments",["payment_id"=>$id],["payment_date"=>$payment_date,"payment_amount"=>$payment_amount,"payment_notes"=>$payment_notes]);
	echo $result;
	exit();
}

 //SEARCH VIEW REPAYMENT
if(isset($_GET['search_view_repayment'])){
       $m = new Manage();
       $s = $_GET['search_view_repayment'];
       $pno = 1;
       $result = $m->searchClients('payments',$s);
       $rows = $result;

       $n = ((($pno)-1)*10) +1;
       if (is_array($rows)) {
         if(count($rows)>0) {
         foreach ($rows as $row) {
         ?>
         <tr>
		      <th scope="row"><?php echo $n;?></th>
		      <td><?php echo $row["client_serial"];?></td>
		      <td><?php echo $row["client_name"];?></td>
		      <td><?php echo $row["payment_date"];?></td>
		      <td><?php echo $row["payment_amount"];?></td>
		      <td><?php echo $row["payment_notes"];?></td>

		      <td>
		      	<a href="#" eid="<?php echo $row['payment_id']; ?>" data-toggle="modal" data-target="#editLoanRepaymentModal" class="btn btn-info btn-sm edit_repayment"><i class="fa fa-plus">&nbsp;</i>View</a>
		      	<a href="#" did="<?php echo $row['payment_id']; ?>" aid="<?php echo $row['active_loan_id']; ?>" class="btn btn-danger btn-sm del_repayment"><i class="fa fa-trash">&nbsp;</i>Delete</a>
		      </td>
		    </tr>

         
         <?php
         $n ++;
         }
     
       }

       } else {
         $rows = "";
         $rows.= "<tr>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="<td><?php echo ''; ?></td>";
         $rows.="</tr>";
         echo $rows;
       }
      
      exit();

   }
   //DELETE REPAYMENT
   if (isset($_POST["delete_repayment"])) {
   	$obj = new loanDBOperations();
   	$result= $obj->deletePayment($_POST["activeLoanID"],$_POST["paymentID"]);
   	echo $result;
   	exit();
   }

   //REPORTS

//Repayment due Report
if(isset($_POST["loans_due_beg_date"]) && isset($_POST["loans_due_end_date"])){
	$pno = 1;
	$report = new Report();
	$result = $report->repaymentDueReport($_POST["loans_due_beg_date"],$_POST["loans_due_end_date"]);
	$rows = $result;
	if(is_array($rows)) {
		if(count($rows) > 0){
			$n = (($pno-1) * 10)+1;
			foreach ($rows as $row) {
				?>
				<tr>
		      <th scope="row"><?php echo $n;?></th>
		      <td><?php echo $row["next_payment_date"];?></td>
		      <td><?php echo $row["client_name"];?></td>
		      <td><?php echo $row["active_loan_name"];?></td>
		      <td><?php echo $row["total_amount"];?></td>
		      <td><?php echo $row["amount_paid"];?></td>
		      <td><?php echo $row["amount_remaining"];?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['active_loan_id']; ?>" data-toggle="modal" data-target="#clientPaymentDetailsModal" class="btn btn-success btn-sm view_loan"><i class="fa fa-plus">&nbsp;</i>View Details</a>
		      </td>
		    </tr>

		<?php
		$n++;
			}
		exit();
		}
	}	

}