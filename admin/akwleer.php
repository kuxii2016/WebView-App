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
	unlink("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-date.html");
	unlink("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-in.html");
	unlink("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-vz.html");
	unlink("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-out.html");
	unlink("../cache/akw/auftrag/aktuell-date.html");
	unlink("../cache/akw/auftrag/aktuell-log.html");
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-date.html", "w");
	$txt = "";
	fwrite ($myfile, $datum);
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-in.html", "w");
	$txt = "";
	fwrite ($myfile, $txt);
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-vz.html", "w");
	$txt = "";
	fwrite ($myfile, $txt);
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-out.html", "w");
	$txt = "";
	fwrite ($myfile, "");
	fclose($myfile);
		//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/akw/auftrag/aktuell-date.html", "w");
	$txt = "";
	fwrite ($myfile, $txt);
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/akw/auftrag/aktuell-log.html", "w");
	$txt = "";
	fwrite ($myfile, "");
	fclose($myfile);
	sleep(1); header('Location: AKW.php'); 
?>