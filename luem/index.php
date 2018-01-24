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
//---Spieler-Box
$rechte = $dbdata['box2'];
//---Spieler-Emeter Zähler 1
$ZiD = $dbdata['zID'];
//---Spieler-Emeter Zähler 2
$ZiD1 = $dbdata['zID1'];
//---Spieler-Emeter Zähler 3
$ZiD2 = $dbdata['zID2'];
//---Spieler-Emeter Zähler 4
$ZiD3 = $dbdata['zID3'];
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
	<meta http-equiv="refresh" content="1" >
	<title>NG :: Luem-Energy</title> 
</head> 
<body>
<?php
$directoryPath = "/var/www/html/daten/emeter/$username"; 
if (!file_exists($directoryPath)) {
    mkdir($directoryPath, 0777);
}
?>
<?php 
if ($ZiD != null){
// Zähler 1 Daten Holen 
//Stromstärke
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD/euamount", "r");
$euamount1 = fgets ($myfile);
//Aktueller Betrag
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD/cost", "r");
$cost1 = fgets ($myfile); 
//Laufzeit
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD/timeSincerun", "r");
$timeSincerunSec1 = fgets ($myfile); 
//Strom Verbrauch
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD/euconsumedkilo", "r");
$euconsumedkilo1 = fgets ($myfile);
//Strom Preis
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD/preis", "r");
$preis1 = fgets ($myfile);

//UMRECHNUNGEN
//Laufzeit
$secs = round($timeSincerunSec1, 2);
$now = date_create('now', new DateTimeZone('GMT'));
$here = clone $now;
$here->modify($secs.' seconds');
$diff1 = $now->diff($here);
//Preis Rundung
$aktuellesumme1 = round($cost1, 2);

//Daten Chache
//schreibt die Stromstärke.
$myfile = fopen("/var/www/html/daten/emeter/$username/1-euamount.html", "w");
fwrite ($myfile, $euamount1. "</br>");
fclose($myfile);
//schreibt die ZählerID.
$myfile = fopen("/var/www/html/daten/emeter/$username/1-ID.html", "w");
fwrite ($myfile, $ZiD. "</br>");
fclose($myfile);
//schreibt die Aktuelle Rechnung.
$myfile = fopen("/var/www/html/daten/emeter/$username/1-cost.html", "w");
fwrite ($myfile, $aktuellesumme1 . " &euro;</br>");
fclose($myfile);	
//schreibt die Laufzeit.
$myfile = fopen("/var/www/html/daten/emeter/$username/1-timeSincerunSec.html", "w");
fwrite ($myfile,$diff1->format('%a Tag(e) %h Stunde(n) %i Minute(n) %s Sekunde(n)'). "</br>");
fclose($myfile);	
//schreibt verbrauchte EU.
$myfile = fopen("/var/www/html/daten/emeter/$username/1-euconsumedkilo.html", "w");
fwrite ($myfile, round($euconsumedkilo1, 3)."KEU</br>");
fclose($myfile);
//schreibt den Preis.
$myfile = fopen("/var/www/html/daten/emeter/$username/1-preis.html", "w");
fwrite ($myfile, $preis1."&euro;</br>");
fclose($myfile);
}
else{

}
?>

<?php 
if ($ZiD2 != null){
// Zähler 2 Daten Holen 
//Stromstärke
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD1/euamount", "r");
$euamount2 = fgets ($myfile);
//Aktueller Betrag
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD1/cost", "r");
$cost2 = fgets ($myfile); 
//Laufzeit
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD1/timeSincerun", "r");
$timeSincerunSec2 = fgets ($myfile); 
//Strom Verbrauch
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD1/euconsumedkilo", "r");
$euconsumedkilo2 = fgets ($myfile);
//Strom Preis
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD1/preis", "r");
$preis2 = fgets ($myfile);

//UMRECHNUNGEN
//Laufzeit
$secs = round($timeSincerunSec2, 2);
$now = date_create('now', new DateTimeZone('GMT'));
$here = clone $now;
$here->modify($secs.' seconds');
$diff2 = $now->diff($here);
//Preis Rundung
$aktuellesumme2 = round($cost2, 2);

//Daten Chache
//schreibt die Stromstärke.
$myfile = fopen("/var/www/html/daten/emeter/$username/2-euamount.html", "w");
fwrite ($myfile, $euamount2. "</br>");
fclose($myfile);
//schreibt die ZählerID.
$myfile = fopen("/var/www/html/daten/emeter/$username/2-ID.html", "w");
fwrite ($myfile, $ZiD1. "</br>");
fclose($myfile);
//schreibt die Aktuelle Rechnung.
$myfile = fopen("/var/www/html/daten/emeter/$username/2-cost.html", "w");
fwrite ($myfile, $aktuellesumme2 . " &euro;</br>");
fclose($myfile);	
//schreibt die Laufzeit.
$myfile = fopen("/var/www/html/daten/emeter/$username/2-timeSincerunSec.html", "w");
fwrite ($myfile,$diff2->format('%a Tag(e) %h Stunde(n) %i Minute(n) %s Sekunde(n)'). "</br>");
fclose($myfile);	
//schreibt verbrauchte EU.
$myfile = fopen("/var/www/html/daten/emeter/$username/2-euconsumedkilo.html", "w");
fwrite ($myfile, round($euconsumedkilo2, 3)."KEU</br>");
fclose($myfile);
//schreibt den Preis.
$myfile = fopen("/var/www/html/daten/emeter/$username/2-preis.html", "w");
fwrite ($myfile, $preis2."&euro;</br>");
fclose($myfile);
}
else{

}
?>

<?php 
if ($ZiD3 != null){
// Zähler 3 Daten Holen 
//Stromstärke
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD3/euamount", "r");
$euamount3 = fgets ($myfile);
//Aktueller Betrag
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD3/cost", "r");
$cost3 = fgets ($myfile); 
//Laufzeit
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD3/timeSincerun", "r");
$timeSincerunSec3 = fgets ($myfile); 
//Strom Verbrauch
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD3/euconsumedkilo", "r");
$euconsumedkilo3 = fgets ($myfile);
//Strom Preis
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD3/preis", "r");
$preis3 = fgets ($myfile);

//UMRECHNUNGEN
//Laufzeit
$secs = round($timeSincerunSec3, 2);
$now = date_create('now', new DateTimeZone('GMT'));
$here = clone $now;
$here->modify($secs.' seconds');
$diff3 = $now->diff($here);
//Preis Rundung
$aktuellesumme3 = round($cost3, 2);

//Daten Chache
//schreibt die Stromstärke.
$myfile = fopen("/var/www/html/daten/emeter/$username/3-euamount.html", "w");
fwrite ($myfile, $euamount3. "</br>");
fclose($myfile);
//schreibt die ZählerID.
$myfile = fopen("/var/www/html/daten/emeter/$username/3-ID.html", "w");
fwrite ($myfile, $ZiD3. "</br>");
fclose($myfile);
//schreibt die Aktuelle Rechnung.
$myfile = fopen("/var/www/html/daten/emeter/$username/3-cost.html", "w");
fwrite ($myfile, $aktuellesumme3 . " &euro;</br>");
fclose($myfile);	
//schreibt die Laufzeit.
$myfile = fopen("/var/www/html/daten/emeter/$username/3-timeSincerunSec.html", "w");
fwrite ($myfile,$diff3->format('%a Tag(e) %h Stunde(n) %i Minute(n) %s Sekunde(n)'). "</br>");
fclose($myfile);	
//schreibt verbrauchte EU.
$myfile = fopen("/var/www/html/daten/emeter/$username/3-euconsumedkilo.html", "w");
fwrite ($myfile, round($euconsumedkilo3, 3)."KEU</br>");
fclose($myfile);
//schreibt den Preis.
$myfile = fopen("/var/www/html/daten/emeter/$username/3-preis.html", "w");
fwrite ($myfile, $preis3."&euro;</br>");
fclose($myfile);
}
else{

}
?>

<?php 
if ($ZiD4 != null){
// Zähler 4 Daten Holen 
//Stromstärke
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD4/euamount", "r");
$euamount4 = fgets ($myfile);
//Aktueller Betrag
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD4/cost", "r");
$cost4 = fgets ($myfile); 
//Laufzeit
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD4/timeSincerun", "r");
$timeSincerunSec4 = fgets ($myfile); 
//Strom Verbrauch
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD4/euconsumedkilo", "r");
$euconsumedkilo4 = fgets ($myfile);
//Strom Preis
$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$ZiD4/preis", "r");
$preis4 = fgets ($myfile);

//UMRECHNUNGEN
//Laufzeit
$secs = round($timeSincerunSec4, 2);
$now = date_create('now', new DateTimeZone('GMT'));
$here = clone $now;
$here->modify($secs.' seconds');
$diff4 = $now->diff($here);
//Preis Rundung
$aktuellesumme4 = round($cost4, 0);

//Daten Chache
//schreibt die Stromstärke.
$myfile = fopen("/var/www/html/daten/emeter/$username/4-euamount.html", "w");
fwrite ($myfile, $euamount4. "</br>");
fclose($myfile);
//schreibt die ZählerID.
$myfile = fopen("/var/www/html/daten/emeter/$username/4-ID.html", "w");
fwrite ($myfile, $ZiD4. "</br>");
fclose($myfile);
//schreibt die Aktuelle Rechnung.
$myfile = fopen("/var/www/html/daten/emeter/$username/4-cost.html", "w");
fwrite ($myfile, $aktuellesumme4 . " &euro;</br>");
fclose($myfile);	
//schreibt die Laufzeit.
$myfile = fopen("/var/www/html/daten/emeter/$username/4-timeSincerunSec.html", "w");
fwrite ($myfile,$diff4->format('%a Tag(e) %h Stunde(n) %i Minute(n) %s Sekunde(n)'). "</br>");
fclose($myfile);	
//schreibt verbrauchte EU.
$myfile = fopen("/var/www/html/daten/emeter/$username/4-euconsumedkilo.html", "w");
fwrite ($myfile, round($euconsumedkilo4, 3)."KEU</br>");
fclose($myfile);
//schreibt den Preis.
$myfile = fopen("/var/www/html/daten/emeter/$username/4-preis.html", "w");
fwrite ($myfile, $preis4."&euro;</br>");
fclose($myfile);
}
else{
}
?>
<?php

//KOSTEN Rechnung
$gesammtkosten = round($aktuellesumme4 + $aktuellesumme3 + $aktuellesumme2 + $aktuellesumme1);
$myfile = fopen("/var/www/html/daten/emeter/$username/$username-cost.html", "w");
fwrite ($myfile, $gesammtkosten);
fclose($myfile);
$gesammtkosten = round($aktuellesumme4 + $aktuellesumme3 + $aktuellesumme2 + $aktuellesumme1);
$myfile = fopen("/var/www/html/daten/emeter/$username/$username-cost.txt", "w");
fwrite ($myfile, $gesammtkosten);
fclose($myfile);
$gesammtKEU = round($euconsumedkilo4, 3) + round($euconsumedkilo3, 3) + round($euconsumedkilo2, 3) + round($euconsumedkilo1, 3);
$myfile = fopen("/var/www/html/daten/emeter/$username/$username-KEU.html", "w");
fwrite ($myfile, $gesammtKEU);
fclose($myfile);
$gesammtKEU = round($euconsumedkilo4, 3) + round($euconsumedkilo3, 3) + round($euconsumedkilo2, 3) + round($euconsumedkilo1, 3);
$myfile = fopen("/var/www/html/daten/emeter/$username/$username-KEU.txt", "w");
fwrite ($myfile, $gesammtKEU);
fclose($myfile);

$myfile = fopen("/var/www/html/daten/emeter/$username/$username-bezahlt.txt", "r");
$bezahltdavon = fgets ($myfile);
$newsumma = $gesammtkosten - $bezahltdavon;

$myfile = fopen("/var/www/html/daten/emeter/$username/$username-neuekosten.html", "w");
fwrite ($myfile, $newsumma);
fclose($myfile);
$myfile = fopen("/var/www/html/daten/emeter/$username/$username-neuekosten.txt", "w");
fwrite ($myfile, $newsumma);
fclose($myfile);

if ($gesammtkosten >= 1500 && $zID >= 1) {
	$status = "<font color='red'>Wird Gesperrt/Ist Gesperrt.!";
	//GS Admin Debug
	$myfile = fopen("/var/www/html/daten/log/spieler/$username-log.html", "a");
	fwrite ($myfile, "Spieler: $username Hat das Limit erreicht, Neuer Auftrag Zählersperre.!</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("/var/www/html/daten/log/spieler/$username-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
}
else {
	$status = "<font color='green'>Zähler ist Frei.!";
}
if ($zID <= 1) {
	$status1 = "";
}
else {
	$status1 = "<font color='orange'>Du hast noch keinen Zugeordneten Zähler Bestelle einen um Strom zu erhalten.!";
}
?><center>
<h1>Luem - Energy</h1> 

<h2>Strom Zähler</h2>
<table style="width:90%" border=0 bgcolor=#2E2E2E>
  <tr>
    <th><font color="white"></th>
    <th><font color="white">ID</th>
    <th><font color="white">EU</th> 
    <th><font color="white">Preis</th>
    <th><font color="white">Laufzeit</th> 
    <th><font color="white">Stand</th>
	<th><font color="white">Kosten</th>
  </tr>
  
  <tr>
	<td style="width:2%"><font color="white"><center>1</td>
    <td style="width:3%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/1-ID.html");?></td>
    <td style="width:4%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/1-euamount.html");?></td> 
    <td style="width:3%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/1-preis.html");?></td>
	<td style="width:50%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/1-timeSincerunSec.html");?></td>
    <td style="width:60%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/1-euconsumedkilo.html");?></td> 
    <td style="width:30%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/1-cost.html");?></td> 
  </tr>
    <tr>
	<td style="width:2%"><font color="white"><center>2</td>
    <td style="width:3%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/2-ID.html");?></td>
    <td style="width:4%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/2-euamount.html");?></td> 
    <td style="width:3%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/2-preis.html");?></td>
	<td style="width:50%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/2-timeSincerunSec.html");?></td>
    <td style="width:60%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/2-euconsumedkilo.html");?></td> 
    <td style="width:30%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/2-cost.html");?></td> 
  </tr>
    <tr>
	<td><font color="white"><center>3</td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/3-ID.html");?></td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/3-euamount.html");?></td> 
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/3-preis.html");?></td>
	<td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/3-timeSincerunSec.html");?></td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/3-euconsumedkilo.html");?></td> 
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/3-cost.html");?></td> 
  </tr>
    <tr>
	<td><font color="white"><center>4</td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/4-ID.html");?></td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/4-euamount.html");?></td> 
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/4-preis.html");?></td>
	<td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/4-timeSincerunSec.html");?></td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/4-euconsumedkilo.html");?></td> 
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$username/4-cost.html");?></td> 
  </tr>
  <tr>
	<td><font color="white"><center></td>
    <td><font color="white"><center></td>
    <td><font color="white"><center></td> 
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center></td>
    <td><font color="white"><center></td> 
  </tr>
  <tr>
	<td><font color="white"><center></td>
    <td><font color="white"><center></td>
    <td><font color="white"><center></td> 
    <td><font color="white"><center></td>
	<td><font color="orange"><center><div style="text-align:right">Gesammt Verbrauch:</td> 
    <td><font color="orange"><center><?php include("/var/www/html/daten/emeter/$username/$username-KEU.html");?> KEU</td>
    <td><font color="red"><center></td> 
  </tr>
  <tr>
	<td><font color="white"><center></td>
    <td><font color="white"><center></td>
    <td><font color="white"><center></td> 
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right">Gesammte Kosten:</td>
    <td><font color="red"><center>- <?php include("/var/www/html/daten/emeter/$username/$username-cost.html");?> &euro;</td> 
  </tr>
  <tr>
	<td><font color="white"><center></td>
    <td><font color="white"><center></td>
    <td><font color="white"><center></td> 
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right">Bezahlt:</td>
    <td><font color="green"><center>+ <?php include("/var/www/html/daten/emeter/$username/$username-bezahlt.html");?> &euro;</td> 
  </tr>
  <tr>
	<td><font color="white"><center></td>
    <td><font color="white"><center></td>
    <td><font color="white"><center></td> 
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center></td>
    <td><font color="white"><center>__________________</td> 
  </tr>
  <tr>
	<td><font color="white"><center></td>
    <td><font color="white"><center></td>
    <td><font color="white"><center></td> 
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right">Aktuell Offen:</td>
    <td><font color="orange"><center> <?php include("/var/www/html/daten/emeter/$username/$username-neuekosten.html");?> &euro;</td> 
  </tr>
</table>
</font>
</br></br>
<?
echo "<font color='white'>Zählerstatus: ".$status;
?>
</br>
<?
echo "".$status1;
?>
<?php
if(isset($_POST['submit'])){
	echo "<font color='white'>Meldung ging raus, Bitte habe Gedult!";
}
?>
<table>
	<tr>
		<form action="../index.php">
			<td>
		<input style="width:130;height:32px" type="submit" value="Hauptmenü"></td>
	</form>
	<form action="order.php">
			<td>
		<input style="width:130;height:32px" type="submit" value="Bestelle Anschluss" name="lol"></td>
	</form></tr><tr>
		<form action="stoerung.php">
			<td>
		<input style="width:130;height:32px" type="submit" value="Melde Störung" name="ok"></td>
	</form>

<?php if ($gesammtkosten >= 1) {
	echo' 
		<form action="pay.php" method="POST">
			<td>
		<input style="width:130;height:32px" type="submit" value="Begleichen" name="Begleichen" />
		</td>
	</form> ';
}
else {
}
?>
</tr>
</table>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
</body> 
</html>