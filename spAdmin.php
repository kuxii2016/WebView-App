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
<?php
$pdo = new PDO($mysql, $dbuser, $pass);
//Abfrage der Nutzer ID vom Login
$username = $_SESSION['username'];
$user1 = $_SESSION['useid'];
$user = $_SESSION['useid'];
$_id = $_POST['id'][0];
//Geld Lesen
$myfile = fopen("/var/www/html/cache/plot/$_id.GRUNDSTÜCKE.txt", "r");
$data = fgets ($myfile);
fclose($myfile);	
$user1 = $_SESSION['useid'];
//datenbank auswahl Username = Session Name = 1
$pdo = new PDO($mysql, $dbuser, $pass);
$statement = $pdo->prepare("SELECT * FROM users, fepermissions_info WHERE name = :name");
$result = $statement->execute(array('name' => $username));
$row = $statement->fetch(PDO::FETCH_ASSOC);
$userl = $statement->fetch();
?>


<!doctype html> 
<html> 
<head>
	<meta charset="utf-8"> 
	<meta name="description" content="Nuclear Gaming Panel">
	<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Nuclear Gaming, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
	<meta name="author" content="Michael Kux">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NG :: Usergrundstücke</title> 
</head> 
<body> 
	<h1>Spieler Datenbank</h1> 
<?php
require_once ('data/konfiguration.php');
$db_link = mysqli_connect (
                     MYSQL_HOST, 
                     MYSQL_BENUTZER, 
                     MYSQL_KENNWORT, 
                     MYSQL_DATENBANK
                    );
 
$sql = "SELECT * FROM users";
 
$db_erg = mysqli_query( $db_link, $sql );
if ( ! $db_erg )
{
  die('Ungültige Abfrage: ' . mysqli_error());
}
 
echo '<table border="0" bgcolor=#F4FA58>';
while ($zeile = mysqli_fetch_array( $db_erg, MYSQL_ASSOC))
{
	
  echo "<tr>";
	echo"<td bgcolor=#DF0101> ID &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Spielername &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Account erstellt &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Email &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> IBAN &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Geld &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Menüdesign &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Rechte &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Spieler Box &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> NewsBox &nbsp; </td>";
  echo "</tr>";
  echo "<tr>";
  echo "<td>". $zeile['id'] . "</td>";
  echo "<td>". $zeile['name'] . "</td>";
  echo "<td>". $zeile['created_at'] . "</td>";
  echo "<td>". $zeile['email'] . "</td>";
  echo "<td>". $zeile['uuid'] . "</td>";
  echo "<td>". $zeile['geld'] ."€</td>";
  echo "<td>". $zeile['theme'] . "</td>";
  echo "<td>". $zeile['rechte'] . "</td>";
  echo "<td>". $zeile['box1'] ."</td>";
  echo "<td>". $zeile['box2'] . "</td>";

  echo "</tr>";
}
echo "</table>";
 
mysqli_free_result( $db_erg );
?>
</form>
<form action="ssetting.php">
<input style="width:160;height:32px" type="submit" value="Zur&uuml;ck">
</form>
</tr>
</body> 
</html>