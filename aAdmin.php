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
	<title>NG :: AKW Buchwald</title> 
</head> 
<body> <center>
	<h1>AKW Buchwald</h1> 
	Aufträge
	<table style="width:90%" border=0 bgcolor=#2E2E2E>
  <tr>
    <th><font color="white">Datum</th>
    <th><font color="white">Ereigniss</th>
  </tr>
  <tr>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/auftrag/aktuell-date.html");?></td>
    <td><font color="orange"><center><?php include("/var/www/html/daten/emeter/auftrag/aktuell-log.html");?></td> 
  </tr>
</table>
	
	
	
	

<?php
	$myfile = fopen("/var/www/html/daten/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64.txt", "r");
	$sbetrag = fgets($myfile);
?>	
<h2>Konto Übersicht</h2>
<table style="width:90%" border=0 bgcolor=#088A85>
	<tr>
		<th bgcolor=#FAAC58> Datum  </th>
		<th bgcolor=#FAAC58> Verwendungszweck  </th>
		<th bgcolor=#FAAC58> Ausgaben  </th>
		<th bgcolor=#FAAC58> Einnahmen  </th>
	</tr>
</br></br>
	<tr>
		<td bgcolor=#F3F781><center> <?php include("/var/www/html/daten/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-date.html");?></td>
		<td bgcolor=#F3F781><center> <?php include("/var/www/html/daten/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-vz.html");?></td>
		<td bgcolor=#F3F781><center> <?php include("/var/www/html/daten/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-out.html");?></td>
		<td bgcolor=#F3F781><center> <?php include("/var/www/html/daten/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-in.html");?></td>
	</tr>
	<tr>
		<td bgcolor=#088A85>&nbsp; </td>
		<td bgcolor=#088A85>&nbsp; </td>
		<td bgcolor=#F3F781>Guthaben: </td>
		<td bgcolor=#F3F781><?php echo"&nbsp;". $sbetrag. " €";?> </td>
	</tr>
	<tr >
	<th bgcolor=#F7FE2E>IBAN:</th>
	<th bgcolor=#F7FE2E>Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64</th>
</tr>
</table>
</br>
<table>
	<tr>
		<form action="ssetting.php">
			<td>
				<input style="width:110;height:32px" type="submit" value="Zur&uuml;ck"></td>
			</form>
		<form action="index.php">
	<td>
		<input style="width:110;height:32px" type="submit" value="Startseite"></td>
			</form>
				<form action='aleer.php' method='POST'>
			<td>
		<input style="width:110;height:32px" type="submit" value="Löschen" name="Löschen" /></td>
	</form></tr><tr>
	<form action='add.php' method='POST'>
	<td>
		<input style="width:110;height:32px" type="submit" value="Zähler Funktion" name="Zähler Funktion" /></td>
	</form>
		<form action='zkontolle.php' method='POST'>
	<td>
		<input style="width:110;height:32px" type="submit" value="Zähler Kontrolle" name="Zähler Kontrolle" /></td>
	</form>
</tr>
</table>
</br></br></br>
</body> 
</html>