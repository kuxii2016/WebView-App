<?php
session_start();
require_once('../config/rcon.php');
require '../config/config.php';
require '../config/Multiplikator.php';
require '../config/IDs.php';
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
	use Thedudeguy\Rcon;
	$rcon = new Rcon($host, $port, $password, $timeout);
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
	<title>EE :: Lohn Zahlungen</title> 
</head> 
<body> 
	<h1>Lohn Zahlungen</h1>
<?php


	//Berechnung
	$fac = $_POST['Zahlmethode'];
	$uid = $_POST['uid'][0];
	$std = $_POST['menge'][0];
	$suser = $_POST['spieler'][0];
	$stdlohn = $std*$fac*$sgehalt;

	//Aktuelle Zeiterfassung
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	if ($rcon->connect())
	{
	$rcon->sendCommand("wallet $suser add $stdlohn");
	$rcon->sendCommand("tell $suser Lohn vom Staat: $stdlohn €.Ihre Haze Maze Bank.!");
	if(isset($_POST['kaufen']))
	{
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-out.html", "a");
	fwrite ($myfile,  $stdlohn. " € - </br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-vz.html", "a");
	fwrite ($myfile,"Lohn Zahlung: $suser </br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-in.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	//Staats Guthaben
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "r");
	$sbetrag = fgets($myfile);
	//Zahlung an Staatskasse
	$nsbetrag = $sbetrag - $stdlohn;
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "w");
	fwrite ($myfile, $nsbetrag);
	fclose($myfile);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/$suser/bank/$suser-$uid-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("../cache/$suser/bank/$suser-$uid-in.html", "a");
	fwrite ($myfile, $stdlohn. " € + </br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/$suser/bank/$suser-$uid-vz.html", "a");
	fwrite ($myfile, "Lohn vom Staat</br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/$suser/bank/$suser-$uid-out.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	//GS Admin Debug
	$myfile = fopen("../cache/log/player/$suser-log.html", "a");
	fwrite ($myfile, "Spieler: $suser Bekam Lohn $stdlohn €(WEB)</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/log/player/$suser-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
	}
else
{
}
}
	
?>
<form method="POST">
 <input type="radio" id="1" name="Zahlmethode" value="10"> <label for="1">Gebäudebau</label>
 <input type="radio" id="2" name="Zahlmethode" value="14"> <label for="2">Strassenbau</label> </br></br>
 <input type="radio" id="3" name="Zahlmethode" value="8"> <label for="3">Landschaftsgestaltung</label></br></br>
 <input type="radio" id="4" name="Zahlmethode" value="14"> <label for="4">Stromnetz</label>
 <input type="radio" id="5" name="Zahlmethode" value="12"> <label for="5">Schienen</label></br> </br>
 <input type="radio" id="5" name="Zahlmethode" value="1"> <label for="5">Spenden</label></br> </br>
 H  :<input type="text" name="menge[]" value="" />Gesammt std.<br> 
 </br>
 An :<input type="text" name="spieler[]" value="" />(Spieler)<br> 
 </br> 
 IBAN:<input type="text" name="uid[]" value="" />(Spieler)<br>
 </br> </br>
 Es wurden <?php echo " $lklase". " €"; ?> an <?php echo " $suser". " ausbezahlt"; ?>
 </br></br>
 Davon <?php echo "$gst". " € ";?>Steuern
  </br> </br>
<table>
	<tr>
		<td>
			<input style="width:150;height:32px" type="submit" value="Bezahlen" name="kaufen" /></td>
			</form>
		<form action='../buergermeister/index.php'>
	<td>
		<input style="width:150;height:32px" type="submit" value="Zurück" ></td>
		</form>
	</tr>
</table>
</body> 
</html>