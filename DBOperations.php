<?php
class DBOperations
{
	private $con;
	function __construct(){
	include_once("../db/dbh.php");
	$db = new Database();
	$this->con =$db->connect();
	}
	//ADD LOCATION FUNCTION
	public function addLocation($location,$description){
		$location = strtoupper($location);
		$sql = "INSERT INTO `location`(`location_name`, `location_description`) VALUES (?,?)";
		$pre_stmt = $this->con->prepare($sql);
		$pre_stmt->bind_param("ss",$location,$description);
		$result = $pre_stmt->execute() or die($this->con->error);
		if($result){
			return "LOCATION_ADDED";
		}else {
			return "SOME_ERROR";
		}
	}
	//Function to get records
	public function getAllRecords($table) {
		$pre_stmt = $this->con->prepare("SELECT * FROM ".$table);
		$pre_stmt->execute() or die($this->con->error);
		$result = $pre_stmt->get_result();
		$rows = array();
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		}
		return "NO_DATA";
	}
	//CHECK WHETHER CLIENT EXISTS
	private function clientExists($name,$phone){
		$pre_stmt = $this->con->prepare("SELECT client_id FROM clients WHERE client_name =? AND client_phoneno =?");
		$pre_stmt->bind_param("ss",$name,$phone);
		$pre_stmt->execute() or die($this->con->error);
		$result = $pre_stmt->get_result();
		if($result->num_rows > 0) {
			return 1;
		} else {
			return 0;
		}

	}
	public function addClient($name,$location,$dob,$phone,$id_type,$id_no,$nok,$nok_phone,$willTakeLoans){
		$name = strtoupper($name);
		$nok = strtoupper($nok);
	if($this->clientExists($name,$phone)){
		return "CLIENT_ALREADY_EXISTS";
	} else {
		$serial ="";
		$sql = "INSERT INTO `clients`(`client_name`, `location_id`, `client_dob`, `client_phoneno`, `client_id_type`, `client_id_no`, `client_nok_name`, `client_nok_phoneno`, `client_serial`) VALUES (?,?,?,?,?,?,?,?,?)";
		$pre_stmt= $this->con->prepare($sql);
		$pre_stmt->bind_param("sisssssss",$name,$location,$dob,$phone,$id_type,$id_no,$nok,$nok_phone,$serial);
		$pre_stmt->execute() or die($this->con->error);
		//GET ID
		$id = $pre_stmt->insert_id;
		$number=str_pad($id,6,"0",STR_PAD_LEFT);
		if($willTakeLoans == "loan_customer"){
			$serial = "GL/LN/".$number;
		} 
		else {$serial = "GL/SN/".$number;}
		
		
		//UPDATE SERIAL NO
		$sql = "UPDATE clients SET client_serial =? WHERE client_id =?";
		$pre_stmt=$this->con->prepare($sql);
		$pre_stmt->bind_param("si",$serial,$id);
		$result= $pre_stmt->execute() or die($this->con->error);
		if(!$result) {
			return "SOME_ERROR";
		}else {
			return "NEW_CLIENT_ADDED";
		}
		}
	}
	public function addEntry($collection_type,$serial,$date,$amount){
		$officer = $_SESSION['username'];
		if($collection_type == "WITHDRAWAL"){
			$amount = $amount * -1;
		}
		$sql = "INSERT INTO `daily_collections`(`client_serial`, `collection_date`, `collection_amount`, `susu_officer`,`collection_type`) VALUES (?,?,?,?,?)";
			$pre_stmt=$this->con->prepare($sql);
			$pre_stmt->bind_param('ssdss',$serial,$date,$amount,$officer,$collection_type);
			$result = $pre_stmt->execute() or die($this->con->error);
			if($result){
				return "ENTRY_SUBMITTED";
			} else {
				return "SOME_ERROR";
			}
	}
}
   // $obj = new DBOperations();
   // echo $obj->addClient("John Doe",1,"1982-01-01","0201234578","PASSPORT","GG72893","Adjoa Doe","0202001850");
  // echo $obj->clientExists("Kobby","");
 // echo $obj->addLocation("KAKRABA","");