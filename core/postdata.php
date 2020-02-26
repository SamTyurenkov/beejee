<?php
class PostData extends Database {
	
 function getPosts($sort,$offset,$amount,$how){
	
	$sort = strip_tags($sort);
	
	if ($sort != null) {
		
	$posts = $this->select("SELECT * FROM `posts` ORDER BY ".$sort." ".$how." LIMIT ".$offset.",".$amount.";");
	
	} else {
		
	$posts = $this->select("SELECT * FROM `posts` ORDER BY ID DESC LIMIT ".$offset.",".$amount.";");	
	
	}
	return $posts;
  }
  
  function addPost($login,$email,$text){
	
	$query = $this->insert("INSERT INTO posts (user_login, user_email, post_text) VALUES ('".$login."','".$email ."','".$text."');");
	if ($query != true) return false;
	return true;
  }
  
  function editPost($id,$login,$email,$text,$edit){
	
	if($edit == true) {
	$query = $this->update("UPDATE posts SET user_login = '".$login."',user_email = '".$email."',post_text = '".$text."',post_edit = TRUE WHERE ID='" . $id . "';");	
	} else {
	$query = $this->update("UPDATE posts SET user_login = '".$login."',user_email = '".$email."',post_text = '".$text."' WHERE ID='" . $id . "';");
	}
	
	if ($query != true) return false;
	return true;
  }
  
  function completePost($id){
	
	$query = $this->update("UPDATE posts SET post_complete = TRUE WHERE ID='" . $id . "';");
	
	if ($query != true) return false;
	return true;
  }
  
}