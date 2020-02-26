<?php
class Manage
{
	private $con;

	function __construct(){
		include_once("../db/dbh.php");
		$db = new Database();
		$this->con=$db->connect();
	}//End Constructor

//function for pagination
	private function pagination($con,$table,$pno,$n) {
		//gives you the total number of records from a table
		$query = $con->query("SELECT COUNT(*) AS rows FROM ".$table);
		$row = mysqli_fetch_assoc($query);

		//initialize page number
		$pageno = $pno;
		$numberOfRecordsPerPage = $n;

		//Total number of pages is the total records / number of records per page
		//its the same as the last page ie if the total number of pages is 14 then the last page will be 14. 
		$lastPage = ceil($row["rows"]/$numberOfRecordsPerPage);

		//initialize how we want our pagination to look like...
		$pagination = "<ul class='pagination'>";

		//checks if there is more than one pages and list the number of pages to the end
		if($lastPage != 1) {
			//Check whether pageno is greater than 1 then add Previous
			if ($pageno > 1) {
				$previous = "";
				$previous = $pageno -1;
				$pagination .= "<li class='page-item'><a class='page-link' pn='".$previous."' href='#' style='color:#333;'>Previous</a></li>";
			}//End Check

			for($pages = $pageno -5; $pages < $pageno; $pages++) {
				if($pages > 0) {
					//show list of pages which contain record in a form of pagination before the page number
				$pagination .= "<li class='page-item'><a class='page-link' pn='".$pages."'href='#'>Page".$pages." </a></li>";
			}
			}//End For
			//Show current page number
			$pagination .= "<li class='page-item'><a class='page-link' pn='".$pageno."' href='#' style='color:#333;'>Page ".$pageno." </a></li>";
			//Show the rest of the pages
			for($pages = $pageno +1; $pages <= $lastPage; $pages++) {
				$pagination .= "<li class='page-item'><a class='page-link' pn='".$pages."' href='#'>Page ".$pages." </a></li>";
				if($pages > $pageno + 4){
					break;
				}
			}

			//if lastPageno > than current page number then show the next button
			if ($lastPage > $pageno) {
				$next = $pageno + 1;
				$pagination .= "<li class='page-item'><a class='page-link' pn='".$next."' href='#' style='color:#333;'> Next</a></li>";
			}
			$pagination .= "</ul>";
		}
		//LIMIT 0,10
			//LIMIT 10,10
		$limit = "LIMIT ".($pageno-1)*$numberOfRecordsPerPage.",".$numberOfRecordsPerPage;

		return ["pagination"=>$pagination,"limit"=>$limit];
	}//End pagination function

	public function manageRecordswithPagination($table,$pno) {
			$a = $this->pagination($this->con,$table,$pno,10);
			if($table == "location") {
				$sql = "SELECT * FROM location ".$a["limit"]; 
			} else if($table == "clients"){
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
			 
			 else {
				$sql = "SELECT * FROM ".$table." ".$a["limit"];
			}
			$pre_stmt = $this->con->prepare($sql);
			$pre_stmt->execute() or die($this->con->error);
			$result = $pre_stmt->get_result();
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()) {
					$rows[] = $row;
				}
			}
			return ["rows"=>$rows,"pagination"=>$a["pagination"]];

		}//End Function

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
//GET SINGLE RECORD TO UPDATE
		public function getSingleRecord($table,$pk,$id){
			if($table == "taxpayers"){
				$sql = "SELECT taxpayers.id,taxpayers.taxpayer_name,taxpayers.taxpayer_tin,taxpayers.taxpayer_fileno,taxpayer_sector.sid,taxpayer_taxtype.tid,
					taxpayer_btype.btid
					FROM taxpayers JOIN taxpayer_sector
					ON taxpayers.id = taxpayer_sector.tpid
					JOIN taxpayer_taxtype on taxpayer_taxtype.tpid = taxpayers.id
					JOIN taxpayer_btype on taxpayer_btype.tpid = taxpayers.id WHERE ".$pk." =? LIMIT 1";

			}  else {
				$sql = "SELECT * FROM ".$table. " WHERE ".$pk." =? LIMIT 1";
			}
			$pre_stmt = $this->con->prepare($sql);
			$pre_stmt->bind_param("i",$id);
			$pre_stmt->execute() or die($this->con->error);
			$result = $pre_stmt->get_result();
			if($result->num_rows ==1) {
				$row = $result->fetch_assoc();
			}
			return $row;
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
	//FUNCTION TO SEARCH DATA
	// public function searchTable($table,$name,$pno){
	// 		$a = $this->pagination($this->con,$table,$pno,10);
	// 		if($table == "clients") {
	// 		$sql = "SELECT
	// 					    clients.client_id,
	// 					    location.location_name,
	// 					    clients.client_serial,
	// 					    clients.client_name,
	// 					    clients.client_dob,
	// 					    clients.client_phoneno,
	// 					    clients.client_id_type,
	// 					    clients.client_id_no,
	// 					    clients.client_nok_name,
	// 					    clients.client_nok_name,
	// 					    clients.client_nok_phoneno
	// 					FROM
	// 					    clients
	// 					JOIN location ON clients.location_id = location.location_id
	// 					WHERE clients.client_name LIKE '%".$name."%' OR clients.client_serial LIKE '%".$name."%' ".$a["limit"];		
	// 		}
			
	// 		$result = $this->con->query($sql) or die($this->con->error);
	// 		if($result->num_rows > 0) {
	// 			while($row = $result->fetch_assoc()) {
	// 		$rows[] = $row;
	// 		}
	// 		}
	// 		return ["rows"=>$rows,"pagination"=>$a["pagination"]];			

	// 	}

		//SEARCH FOR ITEMS
		public function searchTable($table,$name,$pno){
			$a = $this->pagination($this->con,$table,$pno,10);
			if($table == "clients") {
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
						WHERE clients.client_name LIKE '%".$name."%' OR clients.client_serial LIKE '%".$name."%' ".$a["limit"];		
			} 
			
			$result = $this->con->query($sql) or die($this->con->error);
			if($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
			$rows[] = $row;
			}
			return ["rows"=>$rows,"pagination"=>$a["pagination"]];
			}
						

		}
		//DASHBOARD GRAPH
		public function graphSQL($table){
			if($table == "client_daily"){
				$sql = "SELECT clients.client_name, daily_collections.client_serial,
			daily_collections.collection_amount FROM daily_collections
			JOIN clients ON daily_collections.client_serial = clients.client_serial
			ORDER BY client_serial";
			}
			if($table == "daily_collections") {
				$sql = "SELECT
			    daily_collections.collection_date,
			    SUM(
			        daily_collections.collection_amount
			    ) AS 'Total'
			FROM
			    daily_collections
			GROUP BY
			    daily_collections.collection_date
			 ORDER BY daily_collections.collection_date DESC
			 LIMIT 20";
			}
			
			$result = $this->con->query($sql) or die($this->con->error);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$rows[] = $row;
				}
			}
			return $rows;
		}

		//MAKING REPORTS
		public function makeReport($table){
			if ($table == "client_total") {
				$sql = "SELECT
			    clients.client_id,
			    clients.client_name,
			    daily_collections.client_serial,
			    daily_collections.collection_date,
			    SUM(
			        daily_collections.collection_amount
			    ) AS 'Total Collection'
			FROM
			    clients
			JOIN daily_collections ON clients.client_serial = daily_collections.client_serial
			GROUP BY
			    daily_collections.client_serial
			ORDER BY
			    clients.client_name";
				
			}
			//Manage Daily Collections
			if($table == "daily_collections"){
				$sql = "SELECT daily_collections.daily_collections_id,
				daily_collections.client_serial,clients.client_name,
				daily_collections.collection_date,daily_collections.collection_amount,daily_collections.collection_type
				FROM daily_collections JOIN clients ON daily_collections.client_serial= clients.client_serial
				ORDER BY daily_collections.daily_collections_id DESC";
			}
			$result = $this->con->query($sql) or die($this->con->error);
			if($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
			$rows[] = $row;
			}
			return $rows;
			}
				

		}




		//SEARCHING REPORTS
		public function searchReport($table,$name,$beg="",$end=""){
			if ($table == "client_total") {
				$sql = "SELECT
			    clients.client_id,
			    clients.client_name,
			    daily_collections.client_serial,
			    daily_collections.collection_date,
			    SUM(
			        daily_collections.collection_amount
			    ) AS 'Total Collection'
			FROM
			    clients
			JOIN daily_collections ON clients.client_serial = daily_collections.client_serial
			WHERE clients.client_name LIKE '%".$name."%' OR clients.client_serial LIKE '%".$name."%'
			GROUP BY
			    daily_collections.client_serial
			ORDER BY
			    clients.client_name";
				
			}
			if($table == "collection_by_date"){
				$sql = "SELECT
					    clients.client_id,
					    daily_collections.daily_collections_id,
					    clients.client_name,
					    clients.client_serial,
					    daily_collections.collection_date,
					    daily_collections.collection_amount
					FROM
					    clients
					JOIN daily_collections ON clients.client_serial = daily_collections.client_serial
					WHERE (clients.client_name LIKE '%".$name."%' OR clients.client_serial LIKE '%".$name."%')
					 AND daily_collections.collection_date BETWEEN '".$beg."' AND '".$end."'";
			}
			$result = $this->con->query($sql) or die($this->con->error);
			if($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
			$rows[] = $row;
			}
			return $rows;
			}				

		}
		public function viewReport($table,$id){
			if($table == "daily_collections"){
				$sql = "SELECT * FROM daily_collections WHERE client_serial =?";
			}
			$pre_stmt = $this->con->prepare($sql);
			$pre_stmt->bind_param("s",$id);
			$pre_stmt->execute() or die($this->con->error);
			$result = $pre_stmt->get_result();
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$rows[] = $row;
				}
				return $rows;
			}

		}
		//VIEW COLLECTION BY DATE
		public function collectionByDate($option,$beg,$end){
			if($option == "DAILY COLLECTION REPORT"){
				$sql = "SELECT
					    clients.client_id,
					    daily_collections.daily_collections_id,
					    clients.client_name,
					    clients.client_serial,
					    daily_collections.collection_date,
					    daily_collections.collection_amount
					FROM
					    clients
					JOIN daily_collections ON clients.client_serial = daily_collections.client_serial
					WHERE
					    daily_collections.collection_date BETWEEN ? AND ?";
			}
			$pre_stmt = $this->con->prepare($sql);
			$pre_stmt->bind_param("ss",$beg,$end);
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
        // $m = new Manage();
        // print_r($m->searchReport("collection_by_date","viv","2019-01-01","2019-12-31"));
       //print_r($m->collectionByDate("DAILY COLLECTION REPORT","2019-05-01","2019-05-31"));
      // print_r($m->makeReport("daily_collections"));
   // print_r($m->searchReport("collection_by_date","gl/","",""));
//print_r($m->manageRecordswithPagination("location",1));
 // print_r($m->graphSQL("daily_collections"));
   // echo   $m->updateRecord("daily_collections",["daily_collections_id"=>1],["collection_date"=>"2019-05-11","susu_officer"=>"Kobby102","collection_amount"=>500]);