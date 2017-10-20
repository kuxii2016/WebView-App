<?php
session_start();
require_once('data/rcon.php');
require 'data/Pconfig.php';
require 'data/MySqlconfig.php';
require 'data/Multiplikator.php';
require '/var/www/html/data/job.php';
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
//---Spieler-job
$job = $dbdata['job'];

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
	if ($job>= 1){
	$jobTitle = $hLohn;
	$jobTitles = $hLohnVZ;
	}
	if ($job >= 2){
	$jobTitle = $fLohn;
	$jobTitles = $fLohnVZ;
	}
	if ($job >= 3){
	$jobTitle = $vkLohn;
	$jobTitles = $vkLohnVZ;
	}
	if ($job >= 4){
	$jobTitle = $gLohn;
	$jobTitles = $gLohnVZ;
	}
	if ($job >= 5){
	$jobTitle = $VLohn;
	$jobTitles = $VLohnVZ;
	}
	if ($job >= 6){
	$jobTitle = $bLohn;
	$jobTitles = $bLohnVZ;
	}
	if ($job >= 7){
	$jobTitle = $jLohn;
	$jobTitles = $jLohnVZ;
	}
	if ($job >= 8){
	$jobTitle = $sLohn;
	$jobTitles = $sLohnVZ;
	}
	if ($job >= 9){
	$jobTitle = $BLohn;
	$jobTitles = $BLohnVZ;
	}
	if ($job >= 10){
	$jobTitle = $iLohn;
	$jobTitles = $iLohnVZ;
	}	
	if ($job >= 11){
	$jobTitle = $pLohn;
	$jobTitles = $pLohnVZ;
	}
	if ($job >= 12){
	$jobTitle = $PLohn;
	$jobTitles = $PLohnVZ;
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
	//Aktuelle Zeiterfassung
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	$datum1 = date("d.m.Y/H:i", $timestamp);
	//Altes Datum Stunden
	$myfile = fopen("/var/www/html/daten/job/$username/Lohnstufe.txt", "r");
	$datetime = fgets ($myfile);	
	//Bereitsgezahlte Stunden
	$myfile = fopen("/var/www/html/daten/job/$username/Auszahlung.txt", "r");
	$Auszahlung = fgets ($myfile);
	//Bereitsgezahlte Stunden
	$myfile = fopen("/var/www/html/daten/job/$username/Stunden-bezahlt.txt", "r");
	$bezahltdavon = fgets ($myfile);
	//Lohnstufe Stunden
	$myfile = fopen("/var/www/html/daten/job/$username/Lohnstufe.txt", "r");
	$lohnstufe = fgets ($myfile);
	//Neue Stunden
	$myfile = fopen("/var/www/html/daten/job/$username/Stunden.txt", "r");
	$stundenAktuell = fgets ($myfile);
	//Alte Stunden
	$myfile = fopen("/var/www/html/daten/job/$username/Stunden-bezahlt.txt", "r");
	$stundenAlt = fgets ($myfile);
	fclose($myfile);
	$stundenneu = $stundenAlt + $stundenAktuell;
	$myfile = fopen("/var/www/html/daten/job/$username/Stunden-bezahlt.txt", "w");
	fwrite ($myfile, $stundenneu);
	fclose($myfile);
	$newState = $dbdata['geld'] + $Auszahlung;
	//server Verbindung
	use Thedudeguy\Rcon;
	$rcon = new Rcon($host, $port, $password, $timeout);
	if ($rcon->connect())
	{
	$rcon->sendCommand("wallet $username add $Auszahlung");
	$rcon->sendCommand("tell $username Lohn in höhe von $Auszahlung € wurde Eingezahlt.Ihre Haze Maze Bank.!");
	}
	//schreibt die Zeit ins Doc.
	$myfile = fopen("/var/www/html/daten/bank/$username-$uuid-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("/var/www/html/daten/bank/$username-$uuid-in.html", "a");
	fwrite ($myfile, $Auszahlung. " €  </br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("/var/www/html/daten/bank/$username-$uuid-vz.html", "a");
	fwrite ($myfile, $jobTitles." - Lohn mit: $lohnstufe vom:".$datum1."</br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("/var/www/html/daten/bank/$username-$uuid-out.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	//holt den Steuer Betrag
	$myfile = fopen("/var/www/html/daten/job/$username/Steuern.txt", "r");
	$Steuerbetrag = fgets($myfile);
	//Altes Staatsguthaben
	$myfile = fopen("/var/www/html/daten/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "r");
	$sbetrag = fgets($myfile);
	//Zahlung an Staatskasse
	$nsbetrag = $hbetrag + $sbetrag;
	$myfile = fopen("/var/www/html/daten/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "w");
	fwrite ($myfile, $nsbetrag);
	fclose($myfile);	
	//----------------------StaatsKasse Konto Option
	//schreibt die Zeit ins Doc.
	$myfile = fopen("/var/www/html/daten/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("/var/www/html/daten/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-in.html", "a");
	fwrite ($myfile, $Steuerbetrag. " €  </br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("/var/www/html/daten/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-vz.html", "a");
	fwrite ($myfile,"Steuern: $jobTitles - $username </br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("/var/www/html/daten/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-out.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	//Kontostand Update
	$statement = $pdo->prepare("UPDATE `users` SET `geld` = '$newState' WHERE name = '$username' ");
	$result = $statement->execute(array("UPDATE `users` SET `geld` = '$newState' WHERE name = '$username' "));
	//Umleitung und Löschem des CacheFile
	// Admin Debug
	$myfile = fopen("/var/www/html/daten/log/spieler/$username-log.html", "a");
	fwrite ($myfile, "Spieler: $username $jobTitles - Lohn mit: $lohnstufe(APP)</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("/var/www/html/daten/log/spieler/$username-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
	sleep(0.1);
	$myfile = fopen("/var/www/html/daten/job/$username/Datum.txt", "w");
	fwrite ($myfile, $datum1);
	fclose($myfile);
	//Boni
	$bonigeld = 0;
	$myfile = fopen("/var/www/html/daten/job/$username/Bonus.txt", "w");
	fwrite ($myfile, $bonigeld);
	fclose($myfile);
	sleep(0.1); 

	header('Location: job.php'); 

?>