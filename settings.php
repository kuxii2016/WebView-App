<?php
session_start();
require '/var/www/html/data/Pconfig.php';
require '/var/www/html/data/MySqlconfig.php';
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
<center>
<html> 
<head>
	<meta charset="utf-8"> 
	<meta name="description" content="Nuclear Gaming Panel">
	<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Nuclear Gaming, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
	<meta name="author" content="Michael Kux">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NG :: User Settings</title> 
</head> 
<body> 
<?php
$theme = $_POST['themeb'];
$box = $_POST['boxb'];
$boxc = $_POST['boxc'];
$job = $_POST['job'];
?>
<br>
Theme:
<form method="POST">
	<input type="radio" 	id="1" name="themeb" 	value="1">  	<label for="1">Dunkel</label>
	<input type="radio" 	id="2" name="themeb" 	value="2">  	<label for="2">Rot</label> 
	<input type="radio" 	id="3" name="themeb" 	value="3">  	<label for="3">Blau</label> </br></br>
	<input type="radio" 	id="1" name="themeb" 	value="4">  	<label for="4">Gr&uuml;n</label>
	<input type="radio" 	id="2" name="themeb" 	value="5">  	<label for="5">Orange</label> 
	<input type="radio" 	id="3" name="themeb" 	value="6">  	<label for="6">Wei&szlig;</label>
	<br>
	<input style="width:200;height:30px" type="submit" value="Save" name="savt" />
</form>
<?php				//Theme Settings
	
	if(isset($_POST['savt']))
	{
	$statement = $pdo->prepare("UPDATE `users` SET `theme` = '$theme' WHERE name = '$username' ");
	$result = $statement->execute(array("UPDATE `users` SET `theme` = '$theme' WHERE name = '$username' "));
	echo" <font color='#01DF01'>Gespeichert.! ";
	}
	else
	{

	}
?>

<br><br>
Player Box:
<form method="POST">
	<input type="radio" 	id="1" name="boxb" 	value="1">  	<label for="1">AN</label>
	<input type="radio" 	id="2" name="boxb" 	value="2">  	<label for="0">AUS</label> 
	<br>
	<input style="width:200;height:30px" type="submit" value="Save" name="savb" />
</form>
<?php			//Box Settings
	
	if(isset($_POST['savb']))
	{
	$statement = $pdo->prepare("UPDATE `users` SET `box1` = '$box' WHERE name = '$username' ");
	$result = $statement->execute(array("UPDATE `users` SET `box1` = '$box' WHERE name = '$username' "));
	echo" <font color='#01DF01'>Gespeichert.! ";
	}
	else
	{

	}
?>
<br><br>
News Box:
<form method="POST">
	<input type="radio" 	id="1" name="boxc" 	value="1">  	<label for="1">AN</label>
	<input type="radio" 	id="2" name="boxc" 	value="2">  	<label for="0">AUS</label> 
	<br>
	<input style="width:200;height:30px" type="submit" value="Save" name="savc" />
</form>
<?php			//Box Settings
	
	if(isset($_POST['savc']))
	{
	$statement = $pdo->prepare("UPDATE `users` SET `box2` = '$boxc' WHERE name = '$username' ");
	$result = $statement->execute(array("UPDATE `users` SET `box2` = '$boxc' WHERE name = '$username' "));
	echo" <font color='#01DF01'>Gespeichert.! ";
	}
	else
	{

	}
?>
<br><br>
Verfügbare Jobs
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
	<input style="width:200;height:30px" type="submit" value="Save" name="savd" />
</form>
<?php				//Theme Settings

	
	if(isset($_POST['savd']))
	{
	$statement = $pdo->prepare("UPDATE `users` SET `job` = '$job' WHERE name = '$username' ");
	$result = $statement->execute(array("UPDATE `users` SET `job` = '$job' WHERE name = '$username' "));
	$job = $_POST['job'];
	$datawallet = "$pinfo/$uuid.json";
	$jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	$tdata = floor($namen['timePlayed']/1000);
	$secs = $tdata;
	$anspruchsrechnung = round($secs / 60 / 60 );
	$myfile = fopen("/var/www/html/daten/job/$username/Stunden-bezahlt.txt", "w");
	fwrite ($myfile, $anspruchsrechnung);
	fclose($myfile);
	//Admin Debug
	$myfile = fopen("/var/www/html/daten/log/spieler/$username-log.html", "a");
	fwrite ($myfile, "Spieler: $username Wechselte den Job zu: $job (WEB)</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("/var/www/html/daten/log/spieler/$username-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
	echo" <font color='#01DF01'>Gespeichert.! ";
	}
	else
	{

	}
?>
<br>
<table>
<tr>
<form action="index.php">
<td><input style="width:100;height:32px" type="submit" value="Start Seite"></td>
</form>
<form action="job.php">
<td><input style="width:100;height:32px" type="submit" value="Lohn B&uuml;ro"></td>
</form>
</tr>
</table>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</body> 
</html>
