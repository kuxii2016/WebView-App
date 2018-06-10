<?php
session_start();
require '../config/config.php';
$pdo = new PDO($mysql, $dbuser, $pass);
$statement = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$result = $statement->execute(array('id' => $_SESSION['userid']));
$dbdata = $statement->fetch();
$userID = $dbdata['id'];
$username = $dbdata['name'];
$uuid = $dbdata['uuid'];
$geld = $dbdata['geld'];
$theme = $dbdata['theme'];
$rechte = $dbdata['rechte'];
$rechte = $dbdata['box1'];
$ZiD = $dbdata['zID'];
$ZiD1 = $dbdata['zID1'];
$ZiD2 = $dbdata['zID2'];
$ZiD3 = $dbdata['zID3'];
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
 $str = md5(uniqid('$mcrypt_salt', true));
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
	<title>NG :: Userdatenbank</title> 
</head> 
<body> 
	<h1>Spieler Datenbank</h1> 
<?php
require_once ('../config/config.php');
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
	echo"<td bgcolor=#F4FA58> Job &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Z.ID0 &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Z.ID1 &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Z.ID2 &nbsp; </td>";
	echo"<td bgcolor=#F4FA58> Z.ID3 &nbsp; </td>";
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
    echo "<td>". $zeile['job'] ."</td>";
    echo "<td>". $zeile['zID'] . "</td>";
	echo "<td>". $zeile['zID1'] ."</td>";
    echo "<td>". $zeile['zID2'] . "</td>";
	echo "<td>". $zeile['zID3'] . "</td>";
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
<form action="index.php">
<input style="width:160;height:32px" type="submit" value="Zur&uuml;ck">
</form>
</tr>
</body> 
</html>