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
<?php //User Ordner Create
$directoryPath = "/var/www/html/daten/bank/$username"; 
if (!file_exists($directoryPath)) {
    mkdir($directoryPath, 0777);
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
<?php	//Geld Lesen und Updaten
	$datawallet = "$wallet/$uuid.json";
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	$geld = $namen['amount'];
?>
<!doctype html> <center>
<html> 
	<head>
		<meta charset="utf-8"> 
		<meta name="description" content="Nuclear Gaming Panel">
		<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Nuclear Gaming, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
		<meta name="author" content="Michael Kux">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>NG :: Kontomanager</title> 
	</head> 
<body> 
	<h1>Mine Bank</h1> 
<table border=0 bgcolor=#04B45F style="width:99%">	
	<table border=0 bgcolor=#04B45F style="width:99%">
<tr>
	<th bgcolor=#04B45F style="width:8%"> Datum  </th>
	<th bgcolor=#04B45F style="width:75%"> Verwendungszweck  </th>
	<th bgcolor=#04B45F style="width:9%"> Ausgaben  </th>
	<th bgcolor=#04B45F style="width:20%"> Einnahmen  </th>
</tr>
</br></br>
<tr>
	<td bgcolor=#BDBDBD><center><?php include("/var/www/html/daten/bank/$username/$username-$uuid-date.html");?></td>
	<td bgcolor=#A4A4A4><div style='width:300px; overflow:scroll'>		<?php include("/var/www/html/daten/bank/$username/$username-$uuid-vz.html");?></td>
	<td bgcolor=#BDBDBD><center><?php include("/var/www/html/daten/bank/$username/$username-$uuid-out.html");?></td>
	<td bgcolor=#A4A4A4><center><?php include("/var/www/html/daten/bank/$username/$username-$uuid-in.html");?></td>
</tr>
<tr>
	<td bgcolor=#04B45F>&nbsp; </td>
	<td bgcolor=#04B45F>&nbsp; </td>
	<td bgcolor=#04B45F>Guthaben: </td>
	<td bgcolor=#A4A4A4><?php echo"&nbsp;".$dbdata['geld']. " €";?> </td>
</tr>

<tr >
	<th bgcolor=#04B45F>IBAN:</th>
	<th bgcolor=#04B45F><?php echo "$uuid"?></th>
</tr>
</table>
</table>
	<table border=0 >
		<tr>
		<td ><form action='leer.php' method='POST'><input style="width:280px;height:30px" type="submit" value="Konto Auszug Löschen" name="Löschen" /></form></td>
		</tr>
	</table>
<table>
	<tr>
		<form action="../index.php">
			<td>
			<input style="width:90;height:32px" type="submit" value="Hauptmenü"></td>
		</form>
		<form action="ueberweisung.php">
	<td>
		<input style="width:90;height:32px" type="submit" value="Überweisung"></td>
			</form>
		<form action="wechsel.php">
	<td>
		<input style="width:90;height:32px" type="submit" value="Geldwechsel"></td>
		</form>
	</tr>
</table>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
</body> 
</html>