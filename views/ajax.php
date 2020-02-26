<?php 
header('Content-Type: application/json');  
session_start();
require "core/functions.php";

function exitajax($string = 'fail') {
	$response['info'] = $string;
	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	die();
}

if (!isset($_POST["method"])) {
	exitajax();
} else {
	switch ($_POST["method"]) {
	case 'ajaxlogin': ajaxlogin();break;
	case 'ajaxlogout': ajaxlogout();break;
	case 'ajaxaddpost': ajaxaddpost();break;
	case 'ajaxgetposts': ajaxgetposts();break;
	case 'ajaxeditpost': ajaxeditpost();break;
	case 'ajaxcompletepost': ajaxcompletepost();break;
	}
}
	
function ajaxlogin() {	

	$response = array();

	if (isset($_POST["login"]) && isset($_POST["pass"]) ) {
	$login = $_POST["login"];
	$pass = $_POST["pass"]; 
	} else {
	exitajax();
	}

	$userdata = new UserData();
	$result = $userdata->authorize($login,$pass);
	if ($result == false) exitajax();
	
	$response['info'] = 'success';
	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
    die();
}

function ajaxlogout() {	
	$response = array(); 
	
	$_SESSION = array();

	if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
	}
	session_destroy();

	$response['info'] = 'success';
	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
    die();
}

function ajaxaddpost() {	
	$response = array(); 
	
	if (!empty($_POST["login"]) && !empty($_POST["email"]) && !empty($_POST["text"])) {
	$login = $_POST["login"];
	$email = strtolower($_POST["email"]); 
	$text = $_POST["text"]; 
	} else {
	exitajax();
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exitajax();
	}
	
	$login = strip_tags($login);
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);
	$text = strip_tags($text);
	
	$postdata = new PostData();
	$result = $postdata->addPost($login,$email,$text);
	if ($result == false) exitajax();
	
	$response['info'] = 'success';
	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
    die();
}

function ajaxgetposts() {	
	$response = array(); 
	
	if (!isset($_POST["offset"]) || !isset($_POST["amount"])) 
	exitajax();

	if(!is_numeric($_POST["offset"]) || !is_numeric($_POST["amount"])) 
	exitajax();
	
	$offset = $_POST["offset"];
	$amount = $_POST["amount"];
	
	if(isset($_POST["how"]) && $_POST["how"] == 1) {
		$how = 'DESC';
	} else {
		$how = 'ASC';
	}
	
	if (isset($_POST["sort"])) {
	$sort = $_POST["sort"];
	} else {
	$sort = null;
	}	

	$postdata = new PostData();
	$result = $postdata->getPosts($sort,$offset,$amount,$how);
	
	if ($result == false) exitajax();
	
	$response['data'] = $result;
	$response['info'] = 'success';
	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
    die();
}

function ajaxeditpost() {	
	global $user;
	if (!$user->isAdmin()) exitajax();

	$response = array(); 
	
	if (!isset($_POST["login"]) || !isset($_POST["email"]) || !isset($_POST["text"]) || !isset($_POST["postid"]) || !isset($_POST["postedit"])) 
	exitajax('empty vars');
	
	if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || !is_numeric($_POST["postid"]))
    exitajax('filter block');

	if($_POST["postedit"] === "true")
	{
	$edit = true;
	}
	else
	{
	$edit = false;
	};
	
	$login = strip_tags($_POST["login"]);
	$email = strtolower($_POST["email"]);
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);
	$text = strip_tags($_POST["text"]);
	$id = $_POST["postid"];
	
	$postdata = new PostData();
	$result = $postdata->editPost($id,$login,$email,$text,$edit);
	
	if ($result == false) exitajax('query return false');
	
	$response['info'] = 'success';
	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
    die();
}

function ajaxcompletepost() {	
	global $user;
	if (!$user->isAdmin()) exitajax();
	
	$response = array(); 
	
	if (!isset($_POST["postid"]) || !is_numeric($_POST["postid"]))
	exitajax();
		
	$id = $_POST["postid"];

	$postdata = new PostData();
	$result = $postdata->completePost($id);
	
	if ($result == false) exitajax();
	$response['id'] = $id;
	$response['info'] = 'success';
	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
    die();
}