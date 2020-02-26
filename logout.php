<?php
include_once("./db/constants.php");
if(isset($_SESSION["user_id"])) {
	session_destroy();
}
header("location:".DOMAIN."/index.php");