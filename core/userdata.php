<?php
class UserData extends DataBase {

  function authorize($login,$pass){
	$userlogin = filter_var($login, FILTER_SANITIZE_STRING);

	$query = $this->select("SELECT * FROM `users` WHERE user_login='".$login."'");
	$password = $query[0]["user_password"];

	if($pass != $password || empty($password)) {
	return false;
	} else {
	session_set_cookie_params(3600);
	$start = session_start();
	if ($start != true) return false;
	$_SESSION["user"] = "admin";
	return true;
	}	
  }
  
}
?>