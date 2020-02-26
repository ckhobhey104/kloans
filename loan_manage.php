<?php
class Manage{
	private $con;
	function __construct(){
		include_once("../../db/dbh.php");
		$db = new Database();
		$this->con = $db->connect();
	}
	
	//SHOW TABLES
	public function showTable($table){
		if($table == "active_loans"){
			$sql = "SELECT
				    AL.active_loan_id,
				    AL.active_loan_name,
				    C.client_name,
				    C.client_serial,
				    AL.payment_interval,
				    AL.loan_principal,
				    AL.loan_rate,
				    AL.total_amount,
				    AL.instalment_amount,
				    AL.amount_paid,
				    AL.amount_remaining
				FROM
				    active_loans AS AL
				JOIN clients AS C
				ON
				    AL.client_id = C.client_id
				 WHERE AL.amount_remaining > 0";

		}
		else if ($table == "payments"){
			$sql="SELECT
				    clients.client_id,
				    clients.client_serial,
				    clients.client_name,
				    payments.payment_id,
				    active_loans.active_loan_id,
				    payments.payment_date,
				    payments.payment_amount,
				    payments.payment_notes
				FROM
				    clients
				JOIN active_loans ON clients.client_id = active_loans.client_id
				JOIN payments ON active_loans.active_loan_id = payments.active_loan_id
				ORDER BY payments.payment_date DESC";
		}else if($table == "clients"){
				$sql = "SELECT
						    clients.client_id,
						    location.location_name,
						    clients.client_serial,
						    clients.client_name,
						    clients.client_dob,
						    clients.client_phoneno,
						    clients.client_id_type,
						    clients.client_id_no,
						    clients.client_nok_name,
						    clients.client_nok_name,
						    clients.client_nok_phoneno
						FROM
						    clients
						JOIN location ON clients.location_id = location.location_id";
			}
		else{
			$sql ="SELECT * FROM ".$table;
		}
		$pre_stmt = $this->con->prepare($sql);
		$pre_stmt->execute() or die($this->con->error);
		$result = $pre_stmt->get_result();
		if($result->num_rows >0) {
			while($row = $result->fetch_assoc()){
				$rows[] = $row;
			}
			return $rows;
		}

	}
	//SEARCH FOR ITEMS
	public function searchClients($table,$name){
		if($table == "clients"){
			$sql = "SELECT
						    clients.client_id,
						    location.location_name,
						    clients.client_serial,
						    clients.client_name,
						    clients.client_dob,
						    clients.client_phoneno,
						    clients.client_id_type,
						    clients.client_id_no,
						    clients.client_nok_name,
						    clients.client_nok_name,
						    clients.client_nok_phoneno
						FROM
						    clients
						JOIN location ON clients.location_id = location.location_id
						WHERE clients.client_name LIKE '%".$name."%' OR clients.client_serial LIKE '%".$name."%' ORDER BY client_serial ASC";
		}
		if($table == "loan_repayment"){
			$sql = "SELECT
				    AL.active_loan_id,
				    AL.active_loan_name,
				    C.client_name,
				    C.client_serial,
				    AL.payment_interval,
				    AL.loan_principal,
				    AL.loan_rate,
				    AL.total_amount,
				    AL.amount_paid,
				    AL.amount_remaining
				FROM
				    active_loans AS AL
				JOIN clients AS C
				ON
				    AL.client_id = C.client_id
				WHERE C.client_name LIKE '%".$name."%' OR C.client_serial LIKE '%".$name."%'
				ORDER BY C.client_serial ASC";
		}
		if($table == "active_loans"){
			$sql ="SELECT
				    AL.active_loan_id,
				    AL.active_loan_name,
				    C.client_name,
				    C.client_serial,
				    AL.payment_interval,
				    AL.loan_principal,
				    AL.loan_rate,
				    AL.total_amount,
				    AL.instalment_amount,
				    AL.amount_paid,
				    AL.amount_remaining
				FROM
				    active_loans AS AL
				JOIN clients AS C
				ON
				    AL.client_id = C.client_id
				 WHERE AL.amount_remaining > 0
				 AND (C.client_name LIKE '%".$name."%' OR C.client_serial LIKE '%".$name."%')";
		}
		if($table == "payments"){
			$sql="SELECT
				    clients.client_id,
				    clients.client_serial,
				    clients.client_name,
				    payments.payment_id,
				    active_loans.active_loan_id,
				    payments.payment_date,
				    payments.payment_amount,
				    payments.payment_notes
				FROM
				    clients
				JOIN active_loans ON clients.client_id = active_loans.client_id
				JOIN payments ON active_loans.active_loan_id = payments.active_loan_id
				WHERE clients.client_name LIKE '%".$name."%' OR clients.client_serial LIKE '%".$name."%'
				ORDER BY payments.payment_date DESC";

		}
		$pre_stmt= $this->con->prepare($sql);
		$pre_stmt->execute() or die($this->con->error);
		$result = $pre_stmt->get_result();
		if($result->num_rows >0){
			while($row = $result->fetch_assoc()){
				$rows[] = $row;
			}
			return $rows;
		}
		
	}
	//GET SINGLE RECORD FOR JSON
	public function getSingleRecord($table,$pk,$id){
		if($table == "loan_repayment"){
			$sql= "SELECT clients.client_name, clients.client_serial,
			active_loans.active_loan_id,active_loans.payment_interval,
			active_loans.next_payment_date,active_loans.amount_paid,
			active_loans.amount_remaining,active_loans.total_amount
			FROM clients JOIN active_loans ON clients.client_id = active_loans.client_id
			WHERE ".$pk."=? LIMIT 1";
		} else if ($table == "payments") {
			$sql = "SELECT clients.client_id,clients.client_name,active_loans.active_loan_id,payments.payment_id,payments.payment_date,payments.payment_notes,
			payments.payment_amount FROM clients JOIN active_loans ON clients.client_id = active_loans.client_id
			JOIN payments ON active_loans.active_loan_id = payments.active_loan_id
			WHERE ".$pk."=? LIMIT 1";
		}
		else{
		$sql = "SELECT * FROM ".$table." WHERE ".$pk."=? LIMIT 1";
		}
		$pre_stmt = $this->con->prepare($sql);
		$pre_stmt->bind_param("i",$id);
		$pre_stmt->execute() or die($this->con->error);
		$result = $pre_stmt->get_result();
		if($result->num_rows ==1){
			$row = $result->fetch_assoc();
		}
		return $row;
	}
}
    // $m = new Manage();
// print_r($m->showTable("active_loans"));
   // print_r($m->searchClients("loan_repayment","doe"));
    // print_r($m->getSingleRecord('payments','payment_id',1));