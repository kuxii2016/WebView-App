<?php
session_start();
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
//---Spieler-Emeter Zähler 1
$ZiD = $dbdata['zID'];
//---Spieler-Emeter Zähler 2
$ZiD1 = $dbdata['zID1'];
//---Spieler-Emeter Zähler 3
$ZiD2 = $dbdata['zID2'];
//---Spieler-Emeter Zähler 4
$ZiD3 = $dbdata['zID3'];
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

<?php
$name = $_POST['name'];
$passwort = $_POST['passwort'];
$nummer = $_POST['nummer'];
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
	<title>NG :: Zähler Settings</title> 
</head> 
<body> 
	<h1>Zähler hinzufügen</h1>
<form method="POST">
SpielerName:<br>
<input type="name" size="40" maxlength="250" name="name"><br><br> 
EMETER ID:<br>
<input type="name" size="40"  maxlength="250" name="passwort"><br>
Zähler 0 - 3:<br>
<input type="name" size="40"  maxlength="250" name="nummer"><br>
<br>
<br><br>
<input style="width:200;height:30px" type="submit" value="Save" name="save" />
</form>

<?php
	
	if(isset($_POST['save']))
	{
	$statement = $pdo->prepare("UPDATE `users` SET `ZiD$nummer` = '$passwort' WHERE name = '$name' ");
	$result = $statement->execute(array("UPDATE `users` SET `ZiD$nummer` = '$passwort' WHERE name = '$name' "));
	echo" <font color='#01DF01'>Gespeichert.! ";
	}
	else
	{

	}
?>

<br><br><br>
<table>
<tr>
<form action="../index.php">
<td><input style="width:100;height:32px" type="submit" value="Hauptmenü"></td>
</form>
<form action="AKW.php">
<td><input style="width:100;height:32px" type="submit" value="Zurück"></td>
</form>
</tr>
</table>
</body> 
</html>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>