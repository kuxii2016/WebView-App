<?php
session_start();
require '/var/www/html/data/Pconfig.php';
require '/var/www/html/data/MySqlconfig.php';
require '/var/www/html/data/Multiplikator.php';
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
	<title>NG :: Befehle</title> 
</head> 
<body>
<h1>Wichtige Befehle?</h1> 
</br></br>
Spieler Befehle:<br>
<br>
Dieser Befehl Gibt spielern das Volle Recht Bauen/Abbauen zu Dürfen.<br></br>
<font color="red">/p user Spielername group set members</font>

<br><br>
Grundstücke:
Grundstück Kaufen:</br></br>
<font color="orange">[1] = //chunk | [2] = /plot claim | [3] = /yes oder [F8],[F9] </font> </br></br>
Dieser Befehl Erlaubt es anderen Mitspielern das Grundstück mit zu nutzen.<br>
<font color="orange">/plot mods add [user]<br><br> </font>


<font color="orange">/plot perms [DINGE] true|false</font><br>

Dinge = "build", "interact", "use", "chest", "button", "lever", "door", "animal"<br>
true = nutzbar<br>
false = unnutzbar<br>

<br><br>
Konto Befehle:</br><br>
<font color="orange">/wallet</font><br>
<font color="red">/wallet [user]</font><br>
<font color="red">/wallet [user] set [summe]</font><br>
<font color="red">/wallet [user] add [summe]</font><br>
<font color="red">/wallet [user] remove [summe]</font><br><br>
Achtung nur der erste ist für Spieler.!<br><br>
Dynmap:</br><br>
<font color="red">/dynmap radiusrender 100 </font><br></br>
Aktualisiert die karte in einer rechweite von 100Blöcken.<br></br>
<font color="red">/dynmap fullrender</font><br></br>
Startet einen Kompletten Map render.<br></br>
<font color="red">/dynmap cancelrender</font><br></br>
Stopt den aktuell Laufendem Render<br><br>
<?php
echo "<center>Stand vom: 11.10.17 by Kuxii .</center></br></br>";
?>
		<form action="index.php">
			<td>
				<input style="width:160;height:32px" type="submit" value="Start Seite"></td>
			</form>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</body> 
</html>

