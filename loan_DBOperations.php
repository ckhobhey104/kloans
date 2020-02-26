<?php
class loanDBOperations{
	private $con;
	function __construct(){
		include_once("../../db/dbh.php");
		$db = new Database();
		$this->con = $db->connect();
	}
	//FUNCTION DISBURSE LOANS
    public function disburseLoan($loanName,$clientID,$disbursementDate,$principal,$loanTerm,$loanRate,$startPaymentDate,$paymentInterval){
        switch ($paymentInterval) {
            case 'WEEKLY':
                $numberOfInstalments = $loanTerm * 4;
                break;
            case 'BI-WEEKLY':
                $numberOfInstalments= $loanTerm * 2;
                break;
            case 'MONTHLY':
                $numberOfInstalments = $loanTerm * 1;
                break;
            case 'QUATERLY':
                $numberOfInstalments = $loanTerm/3;
                break;            
            default:
                return 'SOME_ERROR';
                break;
        }
    	//RATE IN PERCENTAGE FORMAT
    	$loanRate = $loanRate/100;
    	//INTEREST
    	$interest = $principal*$loanRate*$loanTerm;
    	//GET TOTAL AMOUNT
    	$totalAmount = $principal+$interest;
        //AMOUNT TO BE PAID IN INSTALMENT
        $instalmentAmount = $totalAmount/$numberOfInstalments;
        //REMAINING INSTALMENT
        $instalmentRemaining = 0;
        //DATE FOR NEXT PAYMENT
        $nextPaymentDate = $startPaymentDate;
        //AMOUNT PAID SO FAR
    	$amountPaid = 0;
    	$amountRemaining = $totalAmount-$amountPaid;
        //IS IT A RESCHEDULED LOAN '0' MEANS NO
    	$isRescheduled = "0";

    	//SQL TO INSERT
    	$sql="INSERT INTO `active_loans`(`active_loan_name`, `client_id`, `disbursement_date`, `loan_principal`, `loan_term`, `loan_rate`, `total_amount`,`instalment_amount`,`instalment_remaining`, `amount_paid`, `amount_remaining`, `isrescheduled`, `start_payment_date`, `next_payment_date`, `payment_interval`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    	$pre_stmt = $this->con->prepare($sql);
    	$pre_stmt->bind_param("sisddddddddssss",$loanName,$clientID,$disbursementDate,$principal,$loanTerm,$loanRate,$totalAmount,$instalmentAmount,$instalmentRemaining,$amountPaid,$amountRemaining,$isRescheduled,$startPaymentDate,$nextPaymentDate,$paymentInterval);
    	$result = $pre_stmt->execute() or die($this->con->error);
    	if($result){
    		return "ENTRY_SUBMITTED";
    	}else{
    		return "SOME_ERROR";
    	}

    } 
     //DELETE RECORDS
    public function deleteRecord($table,$pk,$id){
        $sql = "DELETE FROM ".$table." WHERE ".$pk."=?";
        $pre_stmt = $this->con->prepare($sql);
        $pre_stmt->bind_param("i",$id);
        $result = $pre_stmt->execute() or die($this->con->error);
        if($result){
            return "DELETED";
        } else {
            return "SOME_ERROR";
        }
    }

    //CHECK WHETHER CLIENT HAS ALREADY PAID
    private function paymentExists($activeLoanID){
        $sql = "SELECT payment_id FROM payments WHERE active_loan_id=?";
        $pre_stmt = $this->con->prepare($sql);
        $pre_stmt->bind_param("i",$activeLoanID);
        $pre_stmt->execute() or die($this->con->error);
        $result=$pre_stmt->get_result();
        if($result->num_rows > 0){
            return 1;
        } else {
            return 0;
        }
    }
    
    //EDIT LOAN DISBURSEMENT
    public function updateDisbursement($activeLoanID,$loanName,$disbursementDate,$principal,$loanTerm,$loanRate,$startPaymentDate,$paymentInterval) {
        // SET VALUES
        //RATE IN PERCENTAGE FORMAT
        $loanRate = $loanRate/100;
        //INTEREST
        $interest = $principal*$loanRate*$loanTerm;
        //GET TOTAL AMOUNT
        $totalAmount = $principal+$interest;
        
        switch ($paymentInterval) {
            case 'WEEKLY':
                $numberOfInstalments = $loanTerm * 4;
                break;
            case 'BI-WEEKLY':
                $numberOfInstalments= $loanTerm * 2;
                break;
            case 'MONTHLY':
                $numberOfInstalments = $loanTerm * 1;
                break;
            case 'QUATERLY':
                $numberOfInstalments = $loanTerm/3;
                break;            
            default:
                return 'SOME_ERROR';
                break;
        }

        $instalmentAmount = $totalAmount/$numberOfInstalments;
        $instalmentRemaining = 0;
        $nextPaymentDate = $startPaymentDate;
        $amountPaid = 0;
        $amountRemaining = $totalAmount-$amountPaid;
        if($this->paymentExists($activeLoanID)){
            return "LOAN REPAYMENT STARTED!!! CAN'T UPDATE";
        }else {
        $sql = "UPDATE `active_loans` SET `active_loan_name`=?,`disbursement_date`=?,`loan_principal`=?,`loan_term`=?,`loan_rate`=?,`total_amount`=?,`instalment_amount`=?,`instalment_remaining`=?,`amount_paid`=?,`amount_remaining`=?,`start_payment_date`=?,`next_payment_date`=?,`payment_interval`=? WHERE `active_loan_id`=?" ;
        $pre_stmt = $this->con->prepare($sql);
        $pre_stmt->bind_param("ssddddddddsssi",$loanName,$disbursementDate,$principal,$loanTerm,$loanRate,$totalAmount,$instalmentAmount,$instalmentRemaining,$amountPaid,$amountRemaining,$startPaymentDate,$nextPaymentDate,$paymentInterval,$activeLoanID); 
        $result = $pre_stmt->execute() or die($this->con->error);
        if($result){
            return "UPDATED";
        } else{
            return "SOME_ERROR";
        }          
        }
    } 
    //LOAN REPAYMENT
    public function loanRepayment($activeLoanID,$amountPaid,$paymentDate,$paymentInterval,$oldPaymentDate,$notes){
        $originalLoanID = 1;
        $officer = $_SESSION['username'];
        //INSERT INTO PAYMENT TABLE
        $sql = "INSERT INTO `payments`(`active_loan_id`, `original_loan_id`, `payment_amount`, `payment_date`, `officer`, `payment_notes`) VALUES (?,?,?,?,?,?)";
        $pre_stmt = $this->con->prepare($sql);
        $pre_stmt->bind_param("iidsss",$activeLoanID,$originalLoanID,$amountPaid,$paymentDate,$officer,$notes);
        $result = $pre_stmt->execute() or die($this->con->error);
        if($result){
            switch ($paymentInterval) {
                case 'WEEKLY':
                    $date = $oldPaymentDate;
                    $nextDate = strtotime($date);
                    $nextDate = strtotime("+7 day",$nextDate); 
                    $nextPaymentDate = date("Y-m-d",$nextDate);
                    break;
                case 'BI-WEEKLY':
                    $date = $oldPaymentDate;
                    $nextDate = strtotime($date);
                    $nextDate = strtotime("+2 week",$nextDate); 
                    $nextPaymentDate = date("Y-m-d",$nextDate);
                    break;
                case 'MONTHLY':
                    $date = $oldPaymentDate;
                    $nextDate = strtotime($date);
                    $nextDate = strtotime("+1 month",$nextDate); 
                    $nextPaymentDate = date("Y-m-d",$nextDate);
                    break;
                case 'QUATERLY':
                    $date = $oldPaymentDate;
                    $nextDate = strtotime($date);
                    $nextDate = strtotime("+3 month",$nextDate); 
                    $nextPaymentDate = date("Y-m-d",$nextDate);
                    break;
                
                default:
                    return "SOME_ERROR";            
                    break;
            }
            //GET TOTAL PAYMENT FROM THE CLIENT
            $result =$this->con->query("SELECT SUM(payment_amount) AS payment_amount FROM payments WHERE active_loan_id=".$activeLoanID);
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()){
                    $amountPaid =$row["payment_amount"];
                }
            }
            //GET TOTAL AMOUNT TO BE PAID
            $totalAmountToBePaid = $this->con->query("SELECT total_amount FROM active_loans WHERE active_loan_id=".$activeLoanID);
            if($totalAmountToBePaid->num_rows>0){
                while($row = $totalAmountToBePaid->fetch_assoc()){
                    $totalAmount = $row["total_amount"];
                }
            }
            //GET AMOUNT REMAINING
            $amountRemaining = $totalAmount - $amountPaid;
            //UPDATE NEXT PAYMENT DATE
            $sql = "UPDATE active_loans SET amount_paid=?,amount_remaining=?,next_payment_date =? WHERE active_loan_id =?";
            $pre_stmt = $this->con->prepare($sql);
            $pre_stmt->bind_param("ddsi",$amountPaid,$amountRemaining,$nextPaymentDate,$activeLoanID);
            $result = $pre_stmt->execute() or die($this->con->error);
            if($result){
                return "PAYMENT_MADE";
            } else {
                return "SOME_ERROR";
            }

        }


    }
    //FUNCTION TO UPDATE RECORDS
    public function updateRecord($table,$where,$fields) {
        $sql ="";
        $condition = "";
        foreach ($where as $key => $value) {
            //id=5 AND m_name = 'something'
            $condition .= $key . "='" . $value."'";
        }
        $condtion = substr($condition, 0, -5);

        foreach ($fields as $key => $value) {
            //UPDATE Table SET m_name =qty where id=""
        $sql .= $key . "='" . $value . "', ";   
        }
        $sql = substr($sql, 0, -2);
        $sql ="UPDATE ".$table." SET ".$sql. " WHERE ".$condition;
        if (mysqli_query($this->con,$sql)) {
            return "UPDATED";
        } else {
             return "SOMETHING_WRONG";
        }

    }
    //DELETE PAYMENT
    public function deletePayment($activeLoanID,$paymentID){
        //SHOW PAYMENT INTERVAL AND NEXT PAYMENT DAY
        $result = $this->con->query("SELECT * FROM active_loans WHERE active_loan_id=".$activeLoanID);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $paymentInterval = $row["payment_interval"];
                $oldPaymentDate = $row["next_payment_date"];
            }
        }
        


        switch ($paymentInterval) {
                case 'WEEKLY':
                    $date = $oldPaymentDate;
                    $nextDate = strtotime($date);
                    $nextDate = strtotime("-7 day",$nextDate); 
                    $nextPaymentDate = date("Y-m-d",$nextDate);
                    break;
                case 'BI-WEEKLY':
                    $date = $oldPaymentDate;
                    $nextDate = strtotime($date);
                    $nextDate = strtotime("-2 week",$nextDate); 
                    $nextPaymentDate = date("Y-m-d",$nextDate);
                    break;
                case 'MONTHLY':
                    $date = $oldPaymentDate;
                    $nextDate = strtotime($date);
                    $nextDate = strtotime("-1 month",$nextDate); 
                    $nextPaymentDate = date("Y-m-d",$nextDate);
                    break;
                case 'QUATERLY':
                    $date = $oldPaymentDate;
                    $nextDate = strtotime($date);
                    $nextDate = strtotime("-3 month",$nextDate); 
                    $nextPaymentDate = date("Y-m-d",$nextDate);
                    break;
                
                default:
                    return "SOME_ERROR";            
                    break;
                }
                //UPDATE ACTIVE LOANS TABLE
                $sql = "UPDATE active_loans SET next_payment_date = ? WHERE active_loan_id=?";
                $pre_stmt=$this->con->prepare($sql);
                $pre_stmt->bind_param("si",$nextPaymentDate,$activeLoanID);
                $result = $pre_stmt->execute() or die($this->con->error);
                if($result){
                    // DELETE PAYMENTS
                    $pre_stmt = $this->con->prepare("DELETE FROM payments WHERE payment_id=?");
                    $pre_stmt->bind_param("i",$paymentID);
                    $result = $pre_stmt->execute() or die($this->con->error);
                    if($result){
                        //SET AMOUNT PAID
                        $result =$this->con->query("SELECT SUM(payment_amount) AS payment_amount FROM payments WHERE active_loan_id=".$activeLoanID);
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                if($row["payment_amount"] > 0){
                                    $amountPaid = $row["payment_amount"];
                                } else if(is_null($row["payment_amount"])) {
                                    $amountPaid=0;
                                } else{
                                    $amountPaid = 0;
                                }
                            }
                        }
                 //GET TOTAL AMOUNT TO BE PAID
                $totalAmountToBePaid = $this->con->query("SELECT total_amount FROM active_loans WHERE active_loan_id=".$activeLoanID);
                if($totalAmountToBePaid->num_rows>0){
                    while($row = $totalAmountToBePaid->fetch_assoc()){
                       $totalAmount = $row["total_amount"];
                    }
                  }
            //GET AMOUNT REMAINING
            $amountRemaining = $totalAmount - $amountPaid;
                        
                        //UPDATE ACTIVE LOAN AMOUNT PAID
                        $sql = "UPDATE active_loans SET amount_paid=?, amount_remaining=? WHERE active_loan_id=?";
                        $pre_stmt=$this->con->prepare($sql);
                        $pre_stmt->bind_param("ddi",$amountPaid,$amountRemaining,$activeLoanID);
                        $result= $pre_stmt->execute() or die($this->con->error);
                        if($result){
                            return "DELETED";
                        }else{
                            return "SOME_ERROR";
                        }
                    }
                }
    }    
}
 // $obj = new loanDBOperations();
// echo $obj->disburseLoan("Loan To Kwame",2,"2019-09-01",1000,5,5,"2019-09-23","BI-WEEKLY");
// echo $obj->loanRepayment(2,50,"2019-03-02","WEEKLY","2019-09-03","");