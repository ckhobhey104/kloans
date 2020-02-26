<?php
class Report {
	private $con;
	function __construct(){
		include_once "../../db/dbh.php";
		$db = new Database();
		$this->con=$db->connect();
	}
	public function repaymentDueReport($fromDate,$toDate){
		$sql = "SELECT clients.client_serial,clients.client_name,
		active_loans.active_loan_id,active_loans.active_loan_name,active_loans.total_amount,
		active_loans.amount_paid,active_loans.amount_remaining,active_loans.next_payment_date
		FROM clients JOIN active_loans ON clients.client_id = active_loans.client_id
		WHERE active_loans.amount_remaining > 0 AND active_loans.next_payment_date BETWEEN ? AND ?";
		$pre_stmt = $this->con->prepare($sql);
		$pre_stmt->bind_param("ss",$fromDate,$toDate);
		$pre_stmt->execute() or die($this->con->error);
		$result = $pre_stmt->get_result();
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$rows[] = $row;
			}
			return $rows;
		}
	}
}
 // $report = new Report();
 // print_r($report->repaymentDueReport("2019-01-01","2019-12-31"));