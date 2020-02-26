<?php
session_start();
require "core/functions.php";
?>
<!DOCTYPE html>
<html>
<?php 
include_once("templates/header.php");
?>
<body>
<div class="login">
<?php if ($user->isAdmin()) { ?>
<input id="login" type="text" placeholder="Логин" value="admin"></input>
<input id="pass" type="password" placeholder="Пароль" value="****"></input>
<div id="ajaxlogin" class="button" onClick="ajaxlogout()">Выйти</div>
<?php } else { ?>
<input id="login" type="text" placeholder="Логин"></input>
<input id="pass" type="password" placeholder="Пароль"></input>
<div id="ajaxlogin" class="button" onClick="ajaxlogin()">Войти</div>
<?php }; ?>
</div>
<div class="error"></div>

<!-- SECTION WITH POSTS -->
<div class="posts">

<div class="post">
<input id="post_login" type="text" placeholder="Имя"></input>
<input id="post_email" type="email" placeholder="Email"></input>
<textarea id="post_text" type="text" placeholder="Текст задачи"></textarea>
<div id="ajaxaddpost" class="button" onClick="ajaxaddpost()">Добавить задачу</div>
<div class="button" id="close" onClick="editclose()">Отменить редактирование</div>
<div class="error"></div>
</div>
<div class="post">
<div class="title">Сортировка</div>
<div class="sortcontainer"> 
<div id="sortid" class="button" onClick="setSort('ID')">ID</div>
<div id="sortemail" class="button" onClick="setSort('user_email')">Email</div>
<div id="sortname" class="button" onClick="setSort('user_login')">Имя</div>
</div>
</div>
</div>

<div id="showposts" class="posts" style="padding-top:0">


</div>
<div id="nextpage" class="button" onClick="ajaxgetposts()">Следующая страница</div>
<script type="text/javascript">
//LOGIN VARS
var login = document.getElementById("login");
var pass = document.getElementById("pass");
var button = document.getElementById("ajaxlogin");
var error = document.querySelector(".error");

function ajaxlogin() {

var ajax = jQuery.ajax({
	async: true,  
    type: "POST",
    data: {
      login:login.value,
	  pass:pass.value,
	  method: 'ajaxlogin',
    },
    url: "/ajax",
    dataType: 'json',
    success: function(data) {
		if (data.info == 'success') {
      window.location.href = '/';
		} else {
		error.innerHTML = "На этом сайте таких не знают";	
		}
    },
	error: function(errorThrown){ 	
				console.log(errorThrown);
	}
  });  
}  

function ajaxlogout() {
var ajax = jQuery.ajax({
	async: true,  
    type: "POST",
	data: {
	  method: 'ajaxlogout',
    },
    url: "/ajax",
    dataType: 'json',
    success: function(data) {
	if (data.info == 'success') {
	  window.location.href = '/';
	}
    },
	error: function(errorThrown){ 	
				console.log(errorThrown);
	}
  });  
} 

//POST VARS
var plogin = document.getElementById("post_login");
var pemail = document.getElementById("post_email");
var ptext = document.getElementById("post_text");
var pbutton = document.getElementById("ajaxaddpost");
var perror = document.querySelector(".post .error");
var showposts = document.getElementById("showposts");
var how = 1;
var sort = null;
var offset = 0;

function setSort(s) {
	sort = s;

if (how == 1) {
	how = 2;
} else {
	how = 1;
}
	offset = 0;

	ajaxgetposts();
}

function ajaxaddpost() {
if(!validateemail(pemail.value)) {
	perror.innerHTML = 'Email невалиден'; 
		return false;
}
var ajax = jQuery.ajax({
	async: true,  
    type: "POST",
	data: {
      login:plogin.value,
	  email:pemail.value,
	  text:ptext.value,
	  method: 'ajaxaddpost',
    },
    url: "/ajax",
    dataType: 'json',
    success: function(data) {
		console.log(data.info);
	if (data.info == 'success') {
	  offset = 0;
	  ajaxgetposts();
	  perror.innerHTML = 'задача добавлена';
	} else {
	 perror.innerHTML = 'ошибка валидации';
	}
    },
	error: function(jqXHR, textStatus, errorThrown){ 	
				console.log(textStatus);
	}
  });  
} 

function ajaxgetposts() {
var ajax = jQuery.ajax({
	async: true,  
    type: "POST",
	data: {
      sort: sort,
	  offset: offset,
	  amount: 3,
	  how: how,
	  method: 'ajaxgetposts',
    },
    url: "/ajax",
    dataType: 'json',
    success: function(data) {
		console.log(data.info);
	if (data.info == 'success') {
	  for (var i = 0; i < data.data.length; i++) {
		  var status = data.data[i]['post_complete'];
		  if(status == false) {
			status = 'не выполнена';
		  } else if (status == true) {
			status = 'выполнена'; 
		  };
		  if (data.data[i]['post_edit']) {
			status += ' | тут был админ'; 
		  }
		  <?php if ($user->isAdmin()) { ?>
		  if(offset == 0) {
		  showposts.innerHTML = '<div class="post post'+data.data[i]['ID']+'"><div class="title">Задача '+data.data[i]['ID']+' от '+data.data[i]['user_login']+'</div><div class="speed">'+data.data[i]['user_email']+'</div><div class="text">'+data.data[i]['post_text']+'</div><div class="link">'+status+'</div><div class="edit icon-pencil" onClick="selectforedit(this)"></div><div class="complete icon-check" onClick="complete(this)"></div></div></div>'; 
		  } else {
		  showposts.innerHTML += '<div class="post post'+data.data[i]['ID']+'"><div class="title">Задача '+data.data[i]['ID']+' от '+data.data[i]['user_login']+'</div><div class="speed">'+data.data[i]['user_email']+'</div><div class="text">'+data.data[i]['post_text']+'</div><div class="link">'+status+'</div><div class="edit icon-pencil" onClick="selectforedit(this)"></div><div class="complete icon-check" onClick="complete(this)"></div></div></div>';
		  } 
		  <?php } else { ?>
		  if(offset == 0) {
		  showposts.innerHTML = '<div class="post post'+data.data[i]['ID']+'"><div class="title">Задача '+data.data[i]['ID']+' от '+data.data[i]['user_login']+'</div><div class="speed">'+data.data[i]['user_email']+'</div><div class="text">'+data.data[i]['post_text']+'</div><div class="link">'+status+'</div></div></div>'; 
		  } else {
		  showposts.innerHTML += '<div class="post post'+data.data[i]['ID']+'"><div class="title">Задача '+data.data[i]['ID']+' от '+data.data[i]['user_login']+'</div><div class="speed">'+data.data[i]['user_email']+'</div><div class="text">'+data.data[i]['post_text']+'</div><div class="link" >'+status+'</div></div></div>';
		  } 
		  <?php }; ?>
		  offset += 1;
	  }
	}
    },
	error: function(jqXHR, textStatus, errorThrown){ 	
				console.log(textStatus);
	}
  });  
} 
ajaxgetposts();

function validateemail(mail) 
{
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
  {
    return (true)
  }
    return (false)
}

<?php if ($user->isAdmin()) { ?>
//ADMIN VARS
var postid = 0;
var postedit = false;
var temp = '';

function selectforedit(el) {
var login = el.parentElement.getElementsByClassName("title")[0].innerHTML.split(" ");
login.splice(0,3);
login.join(" ");
postid = parseInt(el.parentElement.getElementsByClassName("title")[0].innerHTML.match(/\d+/g)[0]);
plogin.value = login;
pemail.value = el.parentElement.getElementsByClassName("speed")[0].innerHTML;
pbutton.innerHTML = 'Редактировать задачу';
pbutton.setAttribute('onClick','ajaxeditpost()');
perror.innerHTML = '';
temp = ptext.innerHTML = ptext.value = el.parentElement.getElementsByClassName("text")[0].innerHTML;
document.querySelector("#close").style.display = "block";
}

function complete(el) {
postid = parseInt(el.parentElement.getElementsByClassName("title")[0].innerHTML.match(/\d+/g)[0]);
var ajax = jQuery.ajax({
	async: true,  
    type: "POST",
	data: {
	  postid:postid,
	  method: 'ajaxcompletepost',
    },
    url: "/ajax",
    dataType: 'json',
    success: function(data) {
		console.log(data.info);
	if (data.info == 'success') {
	var origin = document.querySelector(".post"+data.id+" .link");
	origin.innerHTML = origin.innerHTML.replace('не ','');
	};
    },
	error: function(jqXHR, textStatus, errorThrown){ 	
				console.log(textStatus);
	}
  }); 
}

function editclose() {
plogin.value = '';
pemail.value = '';
ptext.innerHTML = ptext.value = '';
pbutton.innerHTML = 'Добавить задачу';
pbutton.setAttribute('onClick','ajaxaddpost()');	
ptext.innerHTML = '';
document.querySelector("#close").style.display = "none";
}

function ajaxeditpost() {
if(!validateemail(pemail.value)) {
	perror.innerHTML = 'Email невалиден'; 
		return false;
}
if(ptext.value != temp) postedit = true;

var ajax = jQuery.ajax({
	async: true,  
    type: "POST",
	data: {
      login:plogin.value,
	  email:pemail.value,
	  text:ptext.value,
	  postid:postid,
	  postedit:postedit,
	  method: 'ajaxeditpost',
    },
    url: "/ajax",
    dataType: 'json',
    success: function(data) {
	if (data.info == 'success') {
	window.location.href = '/';
	} else {
		console.log(data.info);
	 perror.innerHTML = 'ошибка валидации';
	}
    },
	error: function(jqXHR, textStatus, errorThrown){ 	
				console.log(textStatus);
	}
  });  
} 
<?php } ?>
</script>
</body>
</html>