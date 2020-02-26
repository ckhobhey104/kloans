<?php
/**
 * 
 */
class User
{
	private $con;//Connection variable
	
	function __construct()
	{
		include_once("../db/dbh.php");
		$db = new Database();
		$this->con = $db->connect();
	}//End Constructor

	//CHECK WHETHER EMAIL EXISTS
	//User is already registered or Not
	private function emailExists($email){
		$pre_stmt = $this->con->prepare("SELECT user_id FROM users WHERE user_email=?");
		$pre_stmt->bind_param("s",$email);
		$pre_stmt->execute() or die($this->con->error);
		$result = $pre_stmt->get_result();
		if($result->num_rows > 0){
			return 1;
		} else{
			return 0;
		}
	}
	//CREATE USER ACCOUNT
	public function createUserAccount($username,$email,$password,$usertype){
		if($this->emailExists($email)){
			return "EMAIL_ALREADY_EXISTS";
		}else{
			$pass_hash = password_hash($password, PASSWORD_DEFAULT);
			$date = date("Y-m-d h:m:s");
			$sql = "INSERT INTO `users`(`username`, `user_email`, `user_pwd`, `user_type`, `register_date`, `last_login`) VALUES (?,?,?,?,?,?)";
			$pre_stmt=$this->con->prepare($sql);
			$pre_stmt->bind_param("ssssss",$username,$email,$pass_hash,$usertype,$date,$date);
			$result = $pre_stmt->execute() or die($this->con->error);
			if($result){
				return $this->con->insert_id;
			}else {
				return "SOME_ERROR";
			}
		}
	}
	//USER LOGIN
	public function userLogin($email,$password){
		$sql = "SELECT user_id,username,user_email,user_pwd,user_type,register_date,last_login FROM users WHERE user_email=?";
		$pre_stmt= $this->con->prepare($sql);
		$pre_stmt->bind_param("s",$email);
		$pre_stmt->execute() or die($this->con->error);
		$result = $pre_stmt->get_result();
		if($result->num_rows < 1){
			return "NOT_REGISTERED";
		} else {
			$row = $result->fetch_assoc();
			if(password_verify($password, $row["user_pwd"])){
				$_SESSION["user_id"]=$row["user_id"];
				$_SESSION["username"]=$row["username"];
				$_SESSION["user_email"]=$row["user_email"];
				$_SESSION["user_type"]=$row["user_type"];
				$_SESSION["register_date"]=$row["register_date"];
				$_SESSION["last_login"]=$row["last_login"];

				//UPDATE LOGIN
				$last_login = date("Y-m-d h:m:s");
				$pre_stmt=$this->con->prepare("UPDATE users SET last_login =? WHERE user_email =?");
				$pre_stmt->bind_param("ss",$last_login,$email);
				$result= $pre_stmt->execute() or die($this->con->error);
				if($result) {
					return 1;
				} else {
					return 0;
				}
			} else {
				return "PASSWORD_NOT_MATCHED";
			}
		}
	}
}

 // $user = new User();
 //echo $user->emailExists("kofi@kofi.com");
// echo $user->createUserAccount("Kobby104","kobby@gmail.com","1234567890","Admin");
// echo $user->userLogin("kobby@gmail.com","1234567890");
