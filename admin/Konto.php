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
<?php
	$_name1 = $_POST['name1'][0];
	$_uuid1 = $_POST['uuid1'][0];
	//Geld Lesen
	$datawallet = "$wallet/$_uuid1.json"; //WalletPfad/+UserIDfromSession
	//Json Lesen
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
?>

<!doctype html> <center>
<html> 
<head>
	<meta charset="utf-8"> 
	<meta name="description" content="Nuclear Gaming Panel">
	<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Nuclear Gaming, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
	<meta name="author" content="Michael Kux">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NG :: Admin Userkonto</title> 
</head> 
<h1>User Konto Admin Funktion</h1> </br></br>
<?
	if(isset($_POST['kaufen']))
	{
	$_name1 = $_POST['name1'];
	$_uuid1 = $_POST['uuid1'];

	}
	else
	{
	}

?>
Konto von: <?php echo " $_name1 ";?> :: <?php echo " $_uuid1 ";
	$statement = $pdo->prepare("SELECT * FROM users WHERE name = :name");
	$result = $statement->execute(array('name' => $_name1));
	$dbdatai = $statement->fetch();
?>

	<table border=0 bgcolor=#FA5858 style="width:90%">
<tr>
	<th bgcolor=#FA5858 style="width:8%"> Datum  </th>
	<th bgcolor=#FA5858 style="width:75%"> Verwendungszweck  </th>
	<th bgcolor=#FA5858 style="width:9%"> Ausgaben  </th>
	<th bgcolor=#FA5858 style="width:20%"> Einnahmen  </th>
</tr>
</br></br>
<tr>
	<td bgcolor=#BDBDBD><?php include("../cache/$username/bank/$_name1-$_uuid1-date.html");?></td>
	<td bgcolor=#A4A4A4><?php include("../cache/$username/bank/$_name1-$_uuid1-vz.html");?></td>
	<td bgcolor=#BDBDBD><?php include("../cache/$username/bank/$_name1-$_uuid1-out.html");?></td>
	<td bgcolor=#A4A4A4><?php include("../cache/$username/bank/$_name1-$_uuid1-in.html");?></td>
</tr>
<tr>
	<td bgcolor=#FA5858>&nbsp; </td>
	<td bgcolor=#FA5858>&nbsp; </td>
	<td bgcolor=#FA5858>Guthaben: </td>
	<td bgcolor=#A4A4A4><?php echo"&nbsp;". $dbdatai['geld']. " &euro;";?> </td>
</tr>
</table>
<br> <br> 
<form method="POST">
	<input style="width:150;height:32px" type="text" name="name1[]" value="Name:  " />
	<input style="width:150;height:32px" type="text" name="uuid1[]" value="UUID:  " /> <br> <br>
	<input style="width:150;height:32px" type="submit" value="Zeigen" name="Zeigen" />
	</form>
		<form action="index.php">
	<input style="width:150;height:32px" type="submit" value="Zur&uuml;ck"></td>
</form>
</head> 
<body>