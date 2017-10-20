<?php
session_start();
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
	<title>NG :: Grundhilfe</title> 
</head> 
<body>
<h1>Grundhilfe</h1> 


<?php
if ($job <= 1){
	//Aktuelle Spielzeit Holen
	$datawallet = "$pinfo/$uuid.json";
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	$tdata = floor($namen['timePlayed']/1000);
	$secs = $tdata;
	//Spielzeit seid der letzten Auszahlung
	$myfile = fopen("/var/www/html/daten/leistungen/$username-$uuid.Time.txt", "r");
	$alttime = fgets ($myfile);
	$newtime = $tdata-$alttime;
	
	//berechnung des Satzes
	$betrag1 = round($newtime*$sozigeld, 0);
	$sbetrag = round($betrag1/100*$SoSt);
	$betrag = $betrag1 - $sbetrag;
	//Übersicht
	echo "Letzter Stand : " .$alttime. " s.</br>";
	echo " Neuer Stand  :  " .$tdata. " s. </br></br>";
	echo " Berechnung  : </br> </br>";
	echo $tdata. "s. - ".$alttime."s. = ".$newtime."s.</br>";
	echo $newtime."s. * ".$sozigeld."%. = ".$betrag1."€</br>";
	echo " Steuern  :  " .round($betrag1). "€ :100 * ".$SoSt." % = ".$sbetrag." €</br>";
	echo "_____________________________</br>";
	echo "Aktueller Betrag :  + " .$betrag1. "€</br>";
	echo "$SoSt % Steuern :  - " .$sbetrag. "€</br>";
	echo "_____________________________</br>";
	echo " Anspruch   :  + " .$betrag. "€</br>";
	if ($betrag <= 1499){
		$betragH = $betrag;
		$betragS = $sbetrag;
		echo" Auszahlung   :  + <font color='green'> ".$betrag." €";
	}
	else if ($betrag >= 1500){
		$betragHg = $betrag / 100 * 10 ;
		$betragH = round($betrag - $betragHg);
		$betragS = $sbetrag + $betragHg;
		echo" Auszahlung   :  + <font color='red'>&nbsp;&nbsp;".$betragH." €</br>";
		echo "<font color='red'>Meldeversäumniss :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I</font></br>";
		$myfile = fopen("/var/www/html/daten/log/log.html", "a");
		fwrite ($myfile, "Spieler: $username Hat eine Sperre von 10% und bekommt nur $betragH €.!</br>");
		fclose($myfile);
		$timestamp = time();
		$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
		$myfile = fopen("/var/www/html/daten/log/date.html", "a");
		fwrite ($myfile, $datum. "&nbsp;</br>");
		fclose($myfile);
	}
	else if ($betrag >= 2500){
		$betragHg = $betrag / 100 * 30 ;
		$betragH = round($betrag - $betragHg);
		$betragS = $sbetrag + $betragHg;
		echo" Auszahlung   :  + <font color='red'>&nbsp;&nbsp;".$betragH." €</br>";
		echo "<font color='red'>Meldeversäumniss :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;II</font></br>";
		$myfile = fopen("/var/www/html/daten/log/log.html", "a");
		fwrite ($myfile, "Spieler: $username Hat eine Sperre von 30% und bekommt nur $betragH €.!</br>");
		fclose($myfile);
		$timestamp = time();
		$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
		$myfile = fopen("/var/www/html/daten/log/date.html", "a");
		fwrite ($myfile, $datum. "&nbsp;</br>");
		fclose($myfile);
	}
	else if ($betrag >= 3500){
		$betragHg = $betrag / 100 * 60;
		$betragH = round($betrag - $betragHg);
		$betragS = $sbetrag + $betragHg;
		echo" Auszahlung   :  + <font color='red'>&nbsp;&nbsp;".$betragH." €</br>";
		echo "<font color='red'>Meldeversäumniss :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;III</font></br>";
		$myfile = fopen("/var/www/html/daten/log/log.html", "a");
		fwrite ($myfile, "Spieler: $username Hat eine Sperre von 50% und bekommt nur $betragH €.!</br>");
		fclose($myfile);
		$timestamp = time();
		$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
		$myfile = fopen("/var/www/html/daten/log/date.html", "a");
		fwrite ($myfile, $datum. "&nbsp;</br>");
		fclose($myfile);
	}
	else if ($betrag >= 4500){
		$betragHg = $betrag / 100 * 100;
		$betragH = round($betrag - $betragHg);
		$betragS = $sbetrag + $betragHg;
		echo" Auszahlung   :  + <font color='red'>&nbsp;&nbsp;".$betragH." €</br>";
		echo "<font color='red'>Meldeversäumniss :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IIII</font></br>";
		$myfile = fopen("/var/www/html/daten/log/log.html", "a");
		fwrite ($myfile, "Spieler: $username Hat eine Sperre von 100% und bekommt nur $betragH €.!</br>");
		fclose($myfile);
		$timestamp = time();
		$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
		$myfile = fopen("/var/www/html/daten/log/date.html", "a");
		fwrite ($myfile, $datum. "&nbsp;</br>");
		fclose($myfile);
	}
	//Spieler Auszahlung
	$myfile = fopen("/var/www/html/daten/leistungen/$username-$uuid.Betrag.txt", "w");
	$txt = "$tdata";
	fwrite ($myfile, $betragH);
	fclose($myfile);
	//Steuern Abgabe
	$myfile = fopen("/var/www/html/daten/leistungen/$username-$uuid.Steuern.txt", "w");
	fwrite ($myfile, $betragS);
	fclose($myfile);
	}
	else{
			echo"<font color='red'>Wir konnten Keine Unterlagen finden $username, Ich glaube du bist hier Falsch.!";
	}
?>

</br></br></br></br></br></br></br></br></br></br>
</br>
<?php if ($betrag >= 20) {
	echo'
<font color="red">
ACHTUNG:<br>
Für die Auszahlung,
muss der Spieler auf
dem Server Online Sein.!
Nie nach Auszahlung in der APP User-Infos ansehen ohne Relog (setzt Kontostand zurück!Wegen dem neuen Live kontostand)
</font>
';
}
else {
}
?>
</br></br></br>
<table>
	<tr>
		<form action="index.php">
			<td>
				<input style="width:160;height:32px" type="submit" value="Start Seite"></td>
			</form>
<?php if ($betrag >= 20) {
	echo'
		<form action="pay.php" method="POST">
	<td>
		<input style="width:160;height:32px" type="submit" value="Auszahlen" name="Auszahlen" />
		</td>
	</form> ';
}
else {
}
?>
		</tr>
	</table>
</br>
<font color="orange">
    Meldeversäumniss Stufen:</br>
    | Stufe: I = 10% | Stufe: II = 30% | Stufe: III = 60% | Stufe: IIII = 100%  </br>
</font>
</br></br></br></br></br></br></br></br></br></br></br></br>
</body> 
</html>