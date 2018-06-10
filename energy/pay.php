<?php
session_start();
require_once('../config/rcon.php');
require '../config/config.php';
require '../config/Multiplikator.php';
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
$rechte = $dbdata['box2'];
$ZiD = $dbdata['zID'];
$ZiD1 = $dbdata['zID1'];
$ZiD2 = $dbdata['zID2'];
$ZiD3 = $dbdata['zID3'];
if($dbdata['sprache'] == 1){
require '../conversation/1.php';
}
elseif($dbdata['sprache'] == 2){
require '../conversation/2.php';
}
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
<?php
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
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64.txt", "r");
	$Cbetrag = fgets($myfile);
	$myfile = fopen("../cache/$username/bank/$username-neuekosten.txt", "r");
	$FGKosten = fgets ($myfile);
	fclose($myfile);
	$myfile = fopen("../cache/$username/bank/$username-cost.txt", "r");
	$GKosten = fgets ($myfile);
	fclose($myfile);
	$myfile = fopen("../cache/$username/bank/$username-bezahlt.txt", "r");
	$BKosten = fgets ($myfile);
	fclose($myfile);
	$myfile = fopen("../cache/$username/bank/$username-KEU.txt", "r");
	$GKEU = fgets ($myfile);
	fclose($myfile);
	$aktuellBezahlt = $FGKosten;
	$akwSteuer = $FGKosten / 100 * $MwSt;
	$akwnewStand = $aktuellBezahlt - $akwSteuer;
	$AKWgutschrift = $Cbetrag + $akwnewStand;
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64.txt", "w");
	fwrite ($myfile, $AKWgutschrift);
	fclose($myfile);
	use Thedudeguy\Rcon;
	$rcon = new Rcon($host, $port, $password, $timeout);
	if ($rcon->connect())
	{
	$rcon->sendCommand("wallet $username remove $aktuellBezahlt");
	$rcon->sendCommand("tell $username $telllineOne $aktuellBezahlt $GuthabenIcon $telllineTwo");
	}
	sleep(0.2);
	$myfile = fopen("../cache/$username/bank/$username-bezahlt.txt", "w");
	fwrite ($myfile, $GKosten );
	fclose($myfile);	
	$myfile = fopen("../cache/$username/bank/$username-bezahlt.html", "w");
	fwrite ($myfile, $GKosten );
	fclose($myfile);	
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	$myfile = fopen("../cache/$username/bank/$username-$uuid-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	$myfile = fopen("../cache/$username/bank/$username-$uuid-in.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	$myfile = fopen("../cache/$username/bank/$username-$uuid-vz.html", "a");
	fwrite ($myfile,"AKW $AkWName Ges. Verbrauch $GKEU kEU/t </br>");
	fclose($myfile);
	$myfile = fopen("../cache/$username/bank/$username-$uuid-out.html", "a");
	fwrite ($myfile, $FGKosten. " $GuthabenIcon</br>"); 
	fclose($myfile);
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-in.html", "a");
	fwrite ($myfile, $FGKosten. " €  </br>");
	fclose($myfile);
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-vz.html", "a");
	fwrite ($myfile,"Stromkosten: $username Ges. Verbrauch $GKEU kEU/t</br>");
	fclose($myfile);
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-out.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-in.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-vz.html", "a");
	fwrite ($myfile,"Steuer abgabe an den Staat</br>");
	fclose($myfile);
	$myfile = fopen("../cache/akw/bank/Akw-9e95b13e-4ab9-40a7-8cfe-9eed07f80d64-out.html", "a");
	fwrite ($myfile, $akwSteuer. " € </br>");
	fclose($myfile);
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-in.html", "a");
	fwrite ($myfile, $akwSteuer. " €</br>");
	fclose($myfile);
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-vz.html", "a");
	fwrite ($myfile,"Steuer für den Staat vom AKW </br>");
	fclose($myfile);
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-out.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "r");
	$sbetrag = fgets($myfile);
	$nsbetrag = $akwSteuer + $sbetrag;
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "w");
	fwrite ($myfile, $nsbetrag);
	fclose($myfile);
	$myfile = fopen("../cache/log/player/$username-log.html", "a");
	fwrite ($myfile, "Spieler: $username Beglich seine StromRechnung von $FGKosten &euro; (WEB)</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	$myfile = fopen("../cache/log/player/$username-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
	sleep(0.5); header('Location: index.php'); 
?>