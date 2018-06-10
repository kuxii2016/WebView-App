<?php
session_start();
require '../config/config.php';
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
//---Sprechfile
if($dbdata['sprache'] == 1){
require '../conversation/1.php';
}
elseif($dbdata['sprache'] == 2){
require '../conversation/2.php';
}
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
<?php //User Ordner Createcache/$username"
$directoryPath = "cache/$username/bank"; 
if (!file_exists("cache/$username/bank")) {
    mkdir("cache/$username/bank", 0777);
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
		<meta name="description" content="Economy Expansion">
		<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Economy Expansion, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
		<meta name="author" content="Michael Kux">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>EE :: <?php echo $button1; ?></title> 
	</head> 
<body> 
	<h1><?php echo $button1; ?></h1> 
		<table border=0 bgcolor=#04B45F style="width:99%">	
			<table border=0 bgcolor=#04B45F style="width:99%">
		<tr>
			<th bgcolor=#04B45F style="width:8%"> <?php echo $date; ?>  </th>
			<th bgcolor=#04B45F style="width:75%"> <?php echo $commit; ?>  </th>
			<th bgcolor=#04B45F style="width:9%"> <?php echo $moneyout; ?>  </th>
			<th bgcolor=#04B45F style="width:20%"> <?php echo $moneyin; ?>  </th>
		</tr>
	</br>
</br>
	<tr>
		<td bgcolor=#BDBDBD><center><?php include("../cache/$username/bank/$username-$uuid-date.html");?></td>
		<td bgcolor=#A4A4A4><center><?php include("../cache/$username/bank/$username-$uuid-vz.html");?></td>
		<td bgcolor=#BDBDBD><center><?php include("../cache/$username/bank/$username-$uuid-out.html");?></td>
		<td bgcolor=#A4A4A4><center><?php include("../cache/$username/bank/$username-$uuid-in.html");?></td>
	</tr>
		<tr>
			<td bgcolor=#04B45F>&nbsp; </td>
			<td bgcolor=#04B45F>&nbsp; </td>
			<td bgcolor=#04B45F><?php echo $moneyhave; ?>: </td>
			<td bgcolor=#A4A4A4><?php echo"&nbsp;".$dbdata['geld']. "";?><?php echo $GuthabenIcon; ?> </td>
		</tr>
		<tr >
			<th bgcolor=#04B45F>IBAN:</th>
			<th bgcolor=#04B45F><?php echo "$uuid"?></th>
		</tr>
	</table>
</table>
	<table border=0 >
		<tr>
		<td ><form action='leer.php' method='POST'><input style="width:340px;height:30px" type="submit" value="<?php echo $deleteview; ?>" name="Löschen" /></form></td>
		</tr>
	</table>
<table>
	<tr>
		<form action="../index.php">
			<td>
			<input style="width:110;height:32px" type="submit" value="<?php echo $PageIndex; ?>"></td>
		</form>
		<form action="ueberweisung.php">
	<td>
		<input style="width:110;height:32px" type="submit" value="<?php echo $transfer; ?>"></td>
			</form>
		<form action="wechsel.php">
	<td>
		<input style="width:110;height:32px" type="submit" value="<?php echo $moneyswitch; ?>"></td>
		</form>
	</tr>
</table>
</body> 
</html>