<?php
session_start();
require 'data/Pconfig.php';
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
<!doctype html> 
<html> 
<head>
	<meta charset="utf-8"> 
	<meta name="description" content="Nuclear Gaming Panel">
	<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Nuclear Gaming, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
	<meta name="author" content="Michael Kux">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NG :: User Settings</title> 
</head> <center></br></br>
<body> 
<h1>Spieler Job Switcher</h1>
<?php
$job = $_POST['job'];
$_name1 = $_POST['name1'][0];
?>
<br>
<form method="POST">
	<input style="width:150;height:32px" type="text" name="name1[]" value="Name:  " /></br></br>
<form method="POST">
	<input type="radio" 	id="1" name="job" 	value="1">  	<label for="1">Holzf&auml;ller</label>
	<input type="radio" 	id="2" name="job" 	value="2">  	<label for="2">Fischer</label> 
 </br></br>
	<input type="radio" 	id="1" name="job" 	value="4">  	<label for="4">G&auml;rtner</label>
	<input type="radio" 	id="2" name="job" 	value="5">  	<label for="5">Versorger</label> 
	<input type="radio" 	id="3" name="job" 	value="6">  	<label for="6">Bauer</label></br></br>
	<input type="radio" 	id="2" name="job" 	value="7">  	<label for="7">J&auml;ger</label> 
	<input type="radio" 	id="3" name="job" 	value="8">  	<label for="8">Schmied</label> 
	<input type="radio" 	id="1" name="job" 	value="9">  	<label for="9">Braumeister</label></br></br>
	<input type="radio" 	id="2" name="job" 	value="10">  	<label for="10">Informatiker</label> 
	<input type="radio" 	id="3" name="job" 	value="11">  	<label for="11">Beamter</label>
	<input type="radio" 	id="3" name="job" 	value="0">  	<label for="0">Harzer</label></br></br></br></br>
	<br>
	<input style="width:200;height:30px" type="submit" value="Save" name="savt" />
</form>
<?php				//Theme Settings
	
	if(isset($_POST['savt']))
	{
	$statement = $pdo->prepare("UPDATE `users` SET `job` = '$job' WHERE name = '$_name1' ");
	$result = $statement->execute(array("UPDATE `users` SET `job` = '$job' WHERE name = '$_name1' "));
	echo" <font color='#01DF01'>Gespeichert.! ";
	}
	else
	{

	}
?>

<br><br>

<br><br><br>
<table>
<tr>
<form action="index.php">
<td><input style="width:100;height:32px" type="submit" value="Start Seite"></td>
</form>
<form action="ssetting.php">
<td><input style="width:100;height:32px" type="submit" value="Zur&uuml;ck"></td>
</form>

</tr>
</table>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</body> 
</html>
