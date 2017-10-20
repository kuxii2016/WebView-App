<?php
session_start();
require 'data/MySqlconfig.php';
$pdo = new PDO($mysql, $dbuser, $pass);

$statement = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$result = $statement->execute(array('id' => $_SESSION['userid']));
$dbdata = $statement->fetch();
//USER Daten
//----------
//---Spieler-Name
$userID = $dbdata['id'];
//---Spieler-Name
$username = $dbdata['name'];
//---Spieler-UUID
$uuid = $dbdata['uuid'];
//---Spieler-Geld
$geld = $dbdata['geld'];
//---Spieler-Theme
$theme = $dbdata['theme'];
//---Spieler-Rechte
$rechte = $dbdata['rechte'];
//---Spieler-Box
$rechte = $dbdata['box1'];
//---Spieler-Box
$rechte = $dbdata['box2'];
//		LOGIN Prüfung
function random_string() {
 if(function_exists('random_bytes')) {
 $bytes = random_bytes(16);
 $str = bin2hex($bytes); 
 } else if(function_exists('openssl_random_pseudo_bytes')) {
 $bytes = openssl_random_pseudo_bytes(16);
 $str = bin2hex($bytes); 
 } else if(function_exists('mcrypt_create_iv')) {
 $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
 $str = bin2hex($bytes); 
 } else {
 $str = md5(uniqid('euer_geheimer_string', true));
 } 
 return $str;
}
if(!isset($_SESSION['userid']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
 $identifier = $_COOKIE['identifier'];
 $securitytoken = $_COOKIE['securitytoken'];
 $statement = $pdo->prepare("SELECT * FROM securitytokens WHERE identifier = ?");
 $result = $statement->execute(array($identifier));
 $securitytoken_row = $statement->fetch();
 if(sha1($securitytoken) !== $securitytoken_row['securitytoken']) {
 die('Upps da lief was schief <a href="login.php">Bitte neu Einloggen</a>');
 } else { 
 $neuer_securitytoken = random_string(); 
 $insert = $pdo->prepare("UPDATE securitytokens SET securitytoken = :securitytoken WHERE identifier = :identifier");
 $insert->execute(array('securitytoken' => sha1($neuer_securitytoken), 'identifier' => $identifier));
 setcookie("identifier",$identifier,time()+(3600*24*365));
 setcookie("securitytoken",$neuer_securitytoken,time()+(3600*24*365));
 $_SESSION['userid'] = $securitytoken_row['user_id'];
 }
}
if(!isset($_SESSION['userid'])) {
 die('Bitte zuerst <a href="login.php">Einloggen</a>');
}
	 //schreibt den Sünder in den Log.
	$myfile = fopen("daten/log/log.html", "a");
	fwrite ($myfile, "Spieler: $username War im Chat(APP)</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("daten/log/date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
?>
<?php 	//Menü Theme
if ($username !== false && $theme == 1) {
	echo "<body style='background-color:#151515'><font color='#01DF01'>";
}elseif ($username !== false && $theme == 2){
	echo "<body style='background-color:#B40404'>";
}elseif ($username !== false && $theme == 3){
	echo "<body style='background-color:#08088A'><font color='#FF0000'>";
}elseif ($username !== false && $theme == 4){
	echo "<body style='background-color:#088A08'>";
}elseif ($username !== false && $theme == 5){
	echo "<body style='background-color:#FACC2E'>";
}elseif ($username !== false && $theme == 6){
	echo "<body style='background-color:#FFFFFF'>";}
?>
<html>
<head>
	<title>Nuclear-Gaming Messenger 0.2</title>
	<style type="text/css">
	html {
		height: 100%;
	}
	body {
		margin: 0px;
		padding: 0px;
		height: 100%;
		font-family: Helvetica, Arial, Sans-serif;
		font-size: 14px;
	}
	.msg-container {
		width: 100%;
		height: 100%;
	}
	.header {
		width: 100%;
		height: 10px;
		border-bottom: 1px solid #CCC;
		text-align: center;
		padding: 15px 0px 5px;
		font-size: 18px;
		font-weight: normal;
	}
	.msg-area {
		height: calc(90% - 100px);
		width: 100%;

		overflow-y: scroll;
	}
	.msginput {
		padding: 1px;
		margin: 10px;
		font-size: 18px;
		width: calc(100% - 10px);

	}
	.bottom {
		width: 100%;
		height: 50px;
		position: fixed;
		bottom: 40px;
		border-top: 1px solid #CCC;
		background-color: #EBEBEB;
	}
	#whitebg {
		width: 100%;
		height: 100%;
		background-color: #0b1e04;
		overflow-y: scroll;
		opacity: 0.5;
		display: none;
		position: absolute;
		top: 0px;
		z-index: 900;
	}
	#loginbox {
		width: 300px;
		height: 150px;
		border: 1px solid #CCC;
		background-color: #FFF;
		position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
		z-index: 1001;
		display: none;
	}
	h1 {
		padding: 0px;
		margin: 20px 0px 0px 0px;
		text-align: center;
		font-weight: normal;
	}
	button {
		background-color: #43ACEC;
		border: none;
		color: #FFF;
		font-size: 16px;
		margin: 0px auto;
		width: 150px;
	}
	.buttonp {
		width: 150px;
		margin: 0px auto;
	}

	.msg {
		margin: 10px 10px;
		background-color: #207a00;
		max-width: calc(45% - 20px);
		color: #c7ddbe;
		padding: 10px;
		font-size: 14px;
	}
	.msgfrom {
		background-color: #40f200;
		color: #0d2803;
		margin: 10px 10px 10px 55%;
	}
	.msgarr {
		width: 0;
		height: 0;
		border-left: 8px solid transparent;
		border-right: 8px solid transparent;
		border-bottom: 8px solid #207a00;
		transform: rotate(315deg);
		margin: -12px 0px 0px 45px;
	}
	.msgarrfrom {
		border-bottom: 8px solid #40f200;
		float: right;
		margin-right: 45px;
	}
	.msgsentby {
		color: #43ff00;
		font-size: 12px;
		margin: 4px 0px 0px 10px;
	}
	.msgsentbyfrom {
		float: right;
		margin-right: 12px;
	}
	</style>
</head>

<body onload="checkcookie(); update();">
<div id="whitebg"></div>
<div id="loginbox">

<p class="buttonp"><button onclick="chooseusername()">Achtung Beta Inhalt.!</button></p>
</div>
<div class="msg-container">
	<div class="header"><body link="#ffffff" vlink="#ffffff" alink="#ffffff"> <a href="index.php">----Zurück----</a></div>
	<div id="typing_on"></div>
	<div class="msg-area" id="msg-area"></div>
	<div class="bottom"><input type="text" name="msginput" class="msginput" id="msginput" onkeydown="if (event.keyCode == 13) sendmsg()" value="" placeholder="Max 300 Zeichen,... Enter zum Senden"></div>
</div>
<script type="text/javascript">

var timer = 0;
function reduceTimer(){
timer = timer - 1;
isTyping(true);
}
function isTyping(val){
if(val == 'true'){
document.getElementById('typing_on').innerHTML = "<?php echo $username; ?> is typing...";
}else{

if(timer <= 0){
document.getElementById('typing_on').innerHTML = "";
}else{
setTimeout("reduceTimer();",500);
}
}
}


var msginput = document.getElementById("msginput");
var msgarea = document.getElementById("msg-area");

function chooseusername() {
	var user = "<?php echo $username; ?>";
	document.cookie="messengerUname=" + user
	checkcookie()
}

function showlogin() {
	document.getElementById("whitebg").style.display = "inline-block";
	document.getElementById("loginbox").style.display = "inline-block";
}

function hideLogin() {
	document.getElementById("whitebg").style.display = "none";
	document.getElementById("loginbox").style.display = "none";
}

function checkcookie() {
	if (document.cookie.indexOf("messengerUname") == -1) {
		showlogin();
	} else {
		hideLogin();
	}
}

function getcookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function escapehtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}
function playSound(filename){   
document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';
}
function update() {
	var xmlhttp=new XMLHttpRequest();
	var username = getcookie("messengerUname");
	var output = "";
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				var response = xmlhttp.responseText.split("\n")
				var rl = response.length
				var item = "";
				for (var i = 0; i < rl; i++) {
					item = response[i].split("\\")
					if (item[1] != undefined) {
						if (item[0] == username) {
							output += "<div class=\"msgc\" style=\"margin-bottom: 30px;\"> <div class=\"msg msgfrom\">" + item[1] + "</div> <div class=\"msgarr msgarrfrom\"></div> <div class=\"msgsentby msgsentbyfrom\">Gesendet von: " + item[0] + "</div> </div>";
						} else {
							output += "<div class=\"msgc\"> <div class=\"msg\">" + item[1] + "</div> <div class=\"msgarr\"></div> <div class=\"msgsentby\">Gesendet von: " + item[0] + "</div> </div>";
						}
					}
				}

				msgarea.innerHTML = output;
				msgarea.scrollTop = msgarea.scrollHeight;

			}
		}
	      xmlhttp.open("GET","get-messages.php?username=" + username,true);
	      xmlhttp.send();
}

function sendmsg() {

	var message = msginput.value;
	if (message != "") {
		// alert(msgarea.innerHTML)
		// alert(getcookie("messengerUname"))

		var username = getcookie("messengerUname");

		var xmlhttp=new XMLHttpRequest();

		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				message = escapehtml(message)
				msgarea.innerHTML += "<div class=\"msgc\" style=\"margin-bottom: 20px;\"> <div class=\"msg msgfrom\">" + message + "</div> <div class=\"msgarr msgarrfrom\"></div> <div class=\"msgsentby msgsentbyfrom\">Gesendet von: " + username + "</div> </div>";
				msginput.value = "";
			}
		}
	      xmlhttp.open("GET","update-messages.php?username=" + username + "&message=" + message,true);
	      xmlhttp.send();
		  playSound('notify');
  	}

}

setInterval(function(){ update() }, 2500);
</script>
<audio id="sound" src="notify.wav" type="audio/mp3"></audio>
<div id="sound"></div>
</body>

</html>
