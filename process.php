<?php
include_once("../db/constants.php");
include_once("./user.php");
include_once("./DBOperations.php");
include_once("./manage.php");

//Register User
if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password1"])){
	$user = new User();
	$name = $_POST["username"];
	$email = $_POST["email"];
	$password = $_POST["password1"];
	$usertype = $_POST["usertype"];
	$result = $user->createUserAccount($name,$email,$password,$usertype);
	echo $result;
	exit();
}
//User Login
if (isset($_POST["login_email"]) && isset($_POST["login_pass"])) {
	$user = new User();
	$email = $_POST["login_email"];
	$pwd = $_POST["login_pass"];
	$result = $user->userLogin($email,$pwd);
	echo $result;
	exit();
}
//Add Location
if(isset($_POST["location_name"]) && isset($_POST["location_desc"])){
	$obj = new DBOperations();
	$location = $_POST["location_name"];
	$desc = $_POST["location_desc"];
	$result = $obj->addLocation($location,$desc);
	echo $result;
	exit();
}
//Get Location
if (isset($_POST["getLocation"])) {
	$obj = new DBOperations();
	$rows = $obj->getAllRecords("location");
	foreach ($rows as $row) {
		echo "<option value='" . $row["location_id"] . "'>" . $row["location_name"] . "</option>";
	}
	exit();
}
//Add New Client
if(isset($_POST["client_name"]) && isset($_POST["select_location"])) {
	$obj = new DBOperations();
	$name = $_POST["client_name"];
	$location = $_POST["select_location"];
	$dob= $_POST["client_dob"];
	$client_phone = $_POST["client_phone"];
	$client_id_type = $_POST["client_id_type"];
	$client_id_no = $_POST["client_id_no"];
	$nok = $_POST["client_nok"];
	$nok_phone = $_POST["client_nok_phone"];
	$will_take_loans = $_POST["will_take_loans"];
	$result = $obj->addClient($name,$location,$dob,$client_phone,$client_id_type,$client_id_no,$nok,$nok_phone,
		$will_take_loans);
	echo $result;
	exit();
}
//Manage Location
if(isset($_POST["manage_location"])){
	$m = new Manage();
	$pno = $_POST["pageno"];
	$result = $m->manageRecordswithPagination("location",$pno);
	$rows = $result["rows"];
	$pagination = $result["pagination"];
	if(is_array($rows)) {
		if(count($rows) > 0){
			$n = (($pno-1) * 10)+1;
			foreach ($rows as $row) {
				?>
				<tr>
		      <th scope="row"><?php echo $n;?></th>
		      <td><?php echo $row["location_name"];?></td>
		      <td><?php echo $row["location_description"];?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['location_id']; ?>" data-toggle="modal" data-target="#editLocationModal" class="btn btn-info edit_location"><i class="fa fa-edit">&nbsp;</i>Edit</a>
		        <a href="#" did="<?php echo $row['location_id']; ?>" class="btn btn-danger del_location"><i class="fa fa-eraser">&nbsp;</i>Delete</a>
		      </td>
		    </tr>

		<?php
		$n++;
			}
		?>
		<tr><td colspan="4"><?php echo $pagination;?></td></tr>
		<?php
		exit();
		}
	}
}
//GET SINGLE LOCATION RECORD
if(isset($_POST["edit_location"])){
	$m = new Manage();
	$result= $m->getSingleRecord("location","location_id",$_POST["id"]);
	echo json_encode($result);
	exit();
}
//Edit Location
if(isset($_POST["edit_location_name"])) {
	$m = new Manage();
	$lid = $_POST["lid"];
	$name = $_POST["edit_location_name"];
	$description = $_POST["edit_location_desc"];
	$result = $m->updateRecord("location",["location_id"=>$lid],["location_id"=>$lid,"location_name"=>$name,"location_description"=>$description]);
	echo $result;
	exit();
}

//Manage Client
if(isset($_POST["manage_client"])){
	$m = new Manage();
	$pno = $_POST["pageno"];
	$result = $m->manageRecordswithPagination("clients",$pno);
	$rows = $result["rows"];
	$pagination = $result["pagination"];
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
		      	<a href="#" eid="<?php echo $row['client_id']; ?>" data-toggle="modal" data-target="#editClientModal" class="btn btn-outline-primary btn-sm edit_client"><i class="fa fa-eraser">&nbsp;</i>Edit</a>
		        <a href="#" did="<?php echo $row['client_id']; ?>" class="btn btn-sm btn-outline-danger del_client"><i class="fa fa-trash">&nbsp;</i>Delete</a>
		    </td>
		    </tr>

		<?php
		$n++;
			}
		?>
		<tr><td colspan="6"><?php echo $pagination;?></td></tr>
		<?php
		exit();
		}
	}
}

//Manage Collection
if(isset($_POST["manage_collection"])){
	$m = new Manage();
	$pno = $_POST["pageno"];
	$result = $m->manageRecordswithPagination("clients",$pno);
	$rows = $result["rows"];
	$pagination = $result["pagination"];
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
		      	<a href="#" eid="<?php echo $row['client_id']; ?>" data-toggle="modal" data-target="#makeCollectionsModal" class="btn btn-primary btn-sm make_entry"><i class="fa fa-pencil">&nbsp;</i>Make Entry</a>
		      </td>
		    </tr>

		<?php
		$n++;
			}
		?>
		<tr><td colspan="6"><?php echo $pagination;?></td></tr>
		<?php
		exit();
		}
	}
}

      //SEARCH Client
   if(isset($_GET['client_search'])){
       $m = new Manage();
       $s = $_GET['client_search'];
       $pno = $_GET['pageno'];
       $result = $m->searchTable('clients',$s,$pno);
       $pagination = $result['pagination'];
       $rows = $result['rows'];

       $n = ((($pno)-1)*10) +1;
       if (is_array($rows)) {
         if(count($rows)>0) {
         foreach ($rows as $row) {
         ?>
         <tr>
         <td><?php echo $n; ?></td>
         <td><?php echo $row["client_serial"]; ?></td>
         <td><?php echo $row["client_name"]; ?></td>
         <td><?php echo $row["location_name"]; ?></td>
         <td><?php echo $row["client_phoneno"]; ?></td>
         <td><a href="#" data-toggle="modal" data-target="#makeCollectionsModal" eid="<?php echo $row['client_id']; ?>" class="btn btn-tax btn-primary btn-sm make_entry"><i class="fa fa-pencil">&nbsp;</i>Make Entry</a>
         </td>        
      </tr>
         
         <?php
         $n ++;
         }
         ?>
         <td colspan="6"><?php echo $pagination;?></td>
         <?php
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
   //GET SINGLE CLIENT RECORD TO MAKE ENTRY
if(isset($_POST["make_entry"])){
	$m = new Manage();
	$result= $m->getSingleRecord("clients","client_id",$_POST["id"]);
	echo json_encode($result);
	exit();
}

//Daily Collections
if(isset($_POST["client_serial"]) && isset($_POST["collection_date"])) {
	$obj = new DBOperations();
	$serial = $_POST["client_serial"];
	$collection_type = $_POST["select_option"];
	$date = $_POST["collection_date"];
	$amount = $_POST["collection_amount"];
	$result = $obj->addEntry($collection_type,$serial,$date,$amount);
	echo $result;
	exit();
}

//GRAPH FOR DASHBOARD
if(isset($_GET["get_graph"])){
	$m = new Manage();
	$result= $m->graphSQL("daily_collections");
	echo json_encode($result);
	exit();
}

//GET CLIENT TOTAL REPORT
if(isset($_POST["client_total"])){
	$pno = 1;
	$m = new Manage();
	$result = $m->makeReport("client_total");
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
		      <td><?php echo $row["Total Collection"];?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['client_serial']; ?>" data-toggle="modal" data-target="#viewClientCollectionInfoModal" class="btn btn-outline-primary btn-sm view_entry"><i class="fa fa-plus">&nbsp;</i>View Info</a>
		      </td>
		    </tr>

		<?php
		$n++;
			}
		exit();
		}
	}

}

    //SEARCH Client
   if(isset($_GET['client_total_search'])){
       $m = new Manage();
       $s = $_GET['client_total_search'];
       $pno = 1;
       $result = $m->searchReport('client_total',$s);
       $rows = $result;

       $n = ((($pno)-1)*10) +1;
       if (is_array($rows)) {
         if(count($rows)>0) {
         foreach ($rows as $row) {
         ?>
         <tr>
         <td><?php echo $n; ?></td>
         <td><?php echo $row["client_serial"]; ?></td>
         <td><?php echo $row["client_name"]; ?></td>
         <td><?php echo $row["Total Collection"]; ?></td>
      
         <td><a href="#" data-toggle="modal" data-target="#viewClientCollectionInfoModal" eid="<?php echo $row['client_serial']; ?>" class="btn btn-outline-primary btn-sm  view_entry"><i class="fa fa-plus">&nbsp;</i>View Info</a>
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
         $rows.="</tr>";
         echo $rows;
       }
      
      exit();

   }
   //VIEW CLIENT TOTAL
 //GET CLIENT TOTAL REPORT
if(isset($_POST["view_entry"])){
	$pno = 1;
	$serial = $_POST["id"];
	$m = new Manage();
	$result = $m->viewReport("daily_collections",$serial);
	$rows = $result;
	if(is_array($rows)) {
		if(count($rows) > 0){
			$n = (($pno-1) * 10)+1;
			foreach ($rows as $row) {
				?>
				<tr>
		      <th scope="row"><?php echo $n;?></th>
		      <td><?php echo $row["collection_date"];?></td>
		      <td><?php echo $row["collection_type"];?></td>
		      <td><?php echo $row["collection_amount"];?></td>
		      <td style="color:blue;"><?php echo $row["susu_officer"];?></td>

		<?php
		$n++;
			}
		exit();
		}
	}

}

//EDIT CLIENT DAILY COLLECTION
if(isset($_POST["edit_collection"])){
	$pno = 1;
	$m = new Manage();
	$result = $m->makeReport("daily_collections");
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
		      <td><?php echo $row["collection_date"];?></td>
		      <td><?php echo $row["collection_amount"];?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['daily_collections_id']; ?>" data-toggle="modal" data-target="#editDailyCollectionsModal" class="btn btn-primary btn-sm edit_collection"><i class="fa fa-plus">&nbsp;</i>Edit Info</a>

		      	<a href="#" did="<?php echo $row['daily_collections_id']; ?>" data-toggle="#" data-target="#" class="btn btn-danger btn-sm del_collection"><i class="fa fa-trash">&nbsp;</i>Delete Info</a>
		      </td>
		    </tr>

		<?php
		$n++;
			}
		exit();
		}
	}

}
   //GET SINGLE ENTRY RECORD TO UPDATE COLLECTION
if(isset($_POST["update_collection"])){
	$m = new Manage();
	$result= $m->getSingleRecord("daily_collections","daily_collections_id",$_POST["id"]);
	echo json_encode($result);
	exit();
}

if(isset($_POST["update_collection_id"])) {
	$m = new Manage();
	$id = $_POST["update_collection_id"];
	$serial = $_POST["edit_client_serial"];
	$date = $_POST["edit_collection_date"];
	$amount = $_POST["edit_collection_amount"];
	$type = $_POST["edit_select_option"];
	$result = $m->updateRecord("daily_collections",["daily_collections_id"=>$id],["client_serial"=>$serial,"collection_amount"=>$amount,"collection_date"=>$date,"collection_type"=>$type]);
	echo $result;
	exit();
}
//GET SINGLE LOCATION RECORD
if(isset($_POST["client_edit"])){
	$m = new Manage();
	$result= $m->getSingleRecord("clients","client_id",$_POST["id"]);
	echo json_encode($result);
	exit();
}
if(isset($_POST["edit_client_name"])) {
	$m = new Manage();
	$id = $_POST["edit_client_id"];
	$name = $_POST["edit_client_name"];
	$location = $_POST["edit_select_location"];
	$date = $_POST["edit_client_dob"];
	$phone = $_POST["edit_client_phone"];
	$client_id_type = $_POST["edit_client_id_type"];
	$client_id_no = $_POST["edit_client_id_no"];
	$nok = $_POST["edit_client_nok"];
	$nok_phone = $_POST["edit_client_nok_phone"];
	$result = $m->updateRecord("clients",["client_id"=>$id],["client_name"=>$name,"location_id"=>$location,"client_dob"=>$date,"client_phoneno"=>$phone,"client_id_type"=>$client_id_type,"client_id_no"=>$client_id_no,"client_nok_name"=>$nok,"client_nok_phoneno"=>$nok_phone]);
	echo $result;
	exit();
}
//DELETE LOCATION
if (isset($_POST["delete_location"])) {
   	$m = new Manage();
   	$result= $m->deleteRecord("location","location_id",$_POST["id"]);
   	echo $result;
   	exit();
   }

   //DELETE CLIENT
if (isset($_POST["delete_client"])) {
   	$m = new Manage();
   	$result= $m->deleteRecord("clients","client_id",$_POST["id"]);
   	echo $result;
   	exit();
   }
   //SEARCH CLIENTS INFORMATION
   if(isset($_GET['client_info_search'])){
       $m = new Manage();
       $s = $_GET['client_info_search'];
       $pno = 1;
       $result = $m->searchTable('clients',$s,$pno);
       $pagination = $result['pagination'];
       $rows = $result['rows'];

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
		      	<a href="#" eid="<?php echo $row['client_id']; ?>" data-toggle="modal" data-target="#editClientModal" class="btn btn-outline-primary btn-sm edit_client"><i class="fa fa-eraser">&nbsp;</i>Edit</a>
		        <a href="#" did="<?php echo $row['client_id']; ?>" class="btn btn-sm btn-outline-danger del_client"><i class="fa fa-trash">&nbsp;</i>Delete</a>
		    </td>
		    </tr>
         
         <?php
         $n ++;
         }
         ?>
         <td colspan="6"><?php echo $pagination;?></td>
         <?php
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
   //GET COLLECTION BY DATE REPORT
if(isset($_POST["coll_beg_date"]) && isset($_POST["coll_end_date"])){
	$m = new Manage();
	$option = $_POST["coll_by_date_option"];
	$beg = $_POST["coll_beg_date"];
	$end = $_POST["coll_end_date"];
	$result = $m->collectionByDate($option,$beg,$end);
	$pno =1;
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
		      <td><?php echo $row["collection_date"];?></td>
		      <td><?php echo $row["collection_amount"];?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['daily_collections_id']; ?>" data-toggle="modal" data-target="#editDailyCollectionsModal" class="btn btn-outline-primary btn-sm edit_collection"><i class="fa fa-plus">&nbsp;</i>Edit</a>
		      	<a href="#" did="<?php echo $row['daily_collections_id']; ?>" class="btn btn-outline-danger btn-sm del_collection"><i class="fa fa-trash">&nbsp;</i>Delete</a>
		      </td>
		    </tr>

		<?php
		$n++;
			}
		exit();
		}
	}

}
//SEARCH COLLECTION BY DATE
   if(isset($_GET['coll_by_date_search'])){
       $m = new Manage();
       $s = $_GET['coll_by_date_search'];
       $pno = 1;
       $result = $m->searchReport('collection_by_date',$s,$_GET["beg_date"],$_GET["end_date"]);
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
		      <td><?php echo $row["collection_date"];?></td>
		      <td><?php echo $row["collection_amount"];?></td>
		      <td>
		      	<a href="#" eid="<?php echo $row['daily_collections_id']; ?>" data-toggle="modal" data-target="#editDailyCollectionsModal" class="btn btn-outline-primary btn-sm edit_collection"><i class="fa fa-eraser">&nbsp;</i>Edit</a>
		        <a href="#" did="<?php echo $row['daily_collections_id']; ?>" class="btn btn-sm btn-outline-danger del_collection"><i class="fa fa-trash">&nbsp;</i>Delete</a>
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
   //DELETE  COLLECTION
if (isset($_POST["delete_collection"])) {
   	$m = new Manage();
   	$result= $m->deleteRecord("daily_collections","daily_collections_id",$_POST["id"]);
   	echo $result;
   	exit();
   }





