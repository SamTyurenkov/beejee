<?php
$user = new User();

class User {
	
	function isAdmin() {
		if(isset($_SESSION["user"]) && $_SESSION["user"] == "admin")
			return true;
	}
	
	function canPost() {
		return true;
	}
	
	function canEdit() {
		if ($this->isAdmin())
		return true;
	}
}
?>