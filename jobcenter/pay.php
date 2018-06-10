<?php
session_start();
require_once('../config/rcon.php');
require '../config/config.php';
require '../config/Multiplikator.php';
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
	//cache geld Read und Löschen.
	$myfile = fopen("../cache/$username/sozial/$username-$uuid.Betrag.txt", "r");
	$betrag = fgets($myfile);
	//Holt Spieler Info
	//server Verbindung
	use Thedudeguy\Rcon;
	$rcon = new Rcon($host, $port, $password, $timeout);
	if ($rcon->connect())
	{
	$rcon->sendCommand("wallet $username add $betrag");
	$rcon->sendCommand("tell $username HarzIV in Höhe von $betrag $GuthabenIcon ging auf ihren Konto ein.");
	}
	$newState = $dbdata['geld'] + $betrag;
	$datawallet = "$pinfo/$uuid.json";
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	$tdata = floor($namen['timePlayed']/1000);
	$secs = $tdata;
	//Schreibt Auszahlzeit
	$myfile = fopen("../cache/$username/sozial/$username-$uuid.Time.txt", "w");
	$txt = "$tdata";
	fwrite ($myfile, $txt);
	fclose($myfile);
	//Aktuelle Zeiterfassung
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-in.html", "a");
	fwrite ($myfile, $betrag. " ".$GuthabenIcon. "  </br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-vz.html", "a");
	fwrite ($myfile, $vz ."</br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-out.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	//holt den Steuer Betrag
	$myfile = fopen("../cache/$username/sozial/$username-$uuid.Steuern.txt", "r");
	$hbetrag = fgets($myfile);
	//Altes Staatsguthaben
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "r");
	$sbetrag = fgets($myfile);
	//Zahlung an Staatskasse
	$nsbetrag = $hbetrag + $sbetrag;
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "w");
	fwrite ($myfile, $nsbetrag);
	fclose($myfile);	
	//----------------------StaatsKasse Konto Option
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-in.html", "a");
	fwrite ($myfile, $hbetrag. " €  </br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-vz.html", "a");
	fwrite ($myfile,"Steuern: $username </br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-out.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	//Kontostand Update
	$statement = $pdo->prepare("UPDATE `users` SET `geld` = '$newState' WHERE name = '$username' ");
	$result = $statement->execute(array("UPDATE `users` SET `geld` = '$newState' WHERE name = '$username' "));
	//Umleitung und Löschem des CacheFile
	//GS Admin Debug
	$myfile = fopen("../cache/log/player/$username-log.html", "a");
	fwrite ($myfile, "Spieler: $username bakam $betrag € für Grundsicherrung (WEB)</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/log/player/$username-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
	sleep(0.5); header('Location: index.php'); 

?>