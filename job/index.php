<?php
session_start();
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
$job = $dbdata['job'];
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
<?php //Erstellt den Spieler Ordner
$directoryPath = "/var/www/html/daten/job/$username"; 
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
<!doctype html> 
<html> 
<head>
	<meta charset="utf-8"> 
	<meta name="description" content="Nuclear Gaming Panel">
	<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Nuclear Gaming, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
	<meta name="author" content="Michael Kux">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>EE :: <?php echo $lBezeichnung?></title> 
</head> 
<body><center>
<h1><?php echo $lBezeichnung?></h1> 


<?php
	//Aktuelle Spielzeit Holen
	$datawallet = "$pinfo/$uuid.json";
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	$tdata = floor($namen['timePlayed']/1000);
	$secs = $tdata / 60 / 60;

	//Spielzeit
	$secs = $tdata;
	$now = date_create('now', new DateTimeZone('GMT'));
	$here = clone $now;
	$here->modify($secs.' seconds');
	$diff1 = $now->diff($here);
	//echo $diff1->format("%a Tag(e) %h Stunde(n) %i Minute(n) %s Sekunde(n)");
	$Tage = $diff1->format("%a");
	if ($job>= 0){
	$jobTitles = "NICHTS";
	}
	if ($job>= 1){
	$jobTitle = $hLohn;
	$jobTitles = $hLohnVZ;
	}
	if ($job == 2){
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

	//Lohnstufen
	
	if ($Tage <= $stufe1){
	$stufe = 0;
	$stufetxt = 0;
	}
	if ($Tage >= $stufe2){
	$stufe = 1;
	$stufetxt = 1;
	}
	if ($Tage >= $stufe3){
	$stufe = 6;
	$stufetxt = 1;
	}
	else if ($Tage >= $stufe4){
	$stufe = 12;
	$stufetxt = 2;
	}
	else if ($Tage >= $stufe5){
	$stufe = 18;
	$stufetxt = 3;
	}
	else if ($Tage >= $stufe6){
	$stufe = 24;
	$stufetxt = 4;
	}
	else{		
	}
	//Bonuszahlung Stunden
	$myfile = fopen("../cache/$username/job/Bonus.txt", "r");
	$Boni = fgets ($myfile);
	//Bereitsgezahlte Stunden
	$myfile = fopen("../cache/$username/job/Stunden-bezahlt.txt", "r");
	$bezahltdavon = fgets ($myfile);
	$anspruchsrechnung = round($secs / 60 / 60 - $bezahltdavon);
	//Stunden Lohn Rechnung
	$StDh = round($jobTitle * 60 * 60);
	//Lohnstufen Rechnung
	$Lstufe = round($StDh / 100 * $stufe);
	//Gehaltsrechnung
	if ($anspruchsrechnung >= 1){
	$gHrechnung = round($anspruchsrechnung * $StDh + $Lstufe);
	}
	else{
	
	}
	//Steuern
	$steuern = round($gHrechnung / 100 * $EinkSt);
	$steuernFinsch = round($gHrechnung - $steuern + $Boni);
	//Stundenlohn Chache
	$myfile = fopen("../cache/$username/job/Stundenlohn.html", "w");
	fwrite ($myfile, $StDh. "</br>");
	fclose($myfile);
	//Stunden Chache
	$myfile = fopen("../cache/$username/job/Stunden.html", "w");
	fwrite ($myfile, $anspruchsrechnung. "</br>");
	fclose($myfile);
	//Lohnstufe Chache
	$myfile = fopen("../cache/$username/job/Lohnstufe.html", "w");
	fwrite ($myfile, $stufetxt ." mit ".$stufe. " % </br>");
	fclose($myfile);
	//Lohnstufe Chache
	$myfile = fopen("../cache/$username/job/Lohnstufe.txt", "w");
	fwrite ($myfile, $stufetxt ." mit ".$stufe. " %");
	fclose($myfile);
	//Lohnrechnung Chache	
	$myfile = fopen("../cache/$username/job/Lohn.html", "w");
	fwrite ($myfile, $gHrechnung. " € ");
	fclose($myfile);
	//Lohnrechnung Chache	
	$myfile = fopen("../cache/$username/job/ZahlLohn.html", "w");
	fwrite ($myfile, $steuernFinsch. "");
	fclose($myfile);
	//Lohnrechnung Chache	
	$myfile = fopen("../cache/$username/job/Steuern.html", "w");
	fwrite ($myfile, $steuern. "");
	fclose($myfile);
	//Lohnrechnung Chache	
	$myfile = fopen("../cache/$username/job/Steuern.txt", "w");
	fwrite ($myfile, $steuern. "");
	fclose($myfile);
	//Auszahlbetrag Chache	
	$myfile = fopen("../cache/$username/job/Auszahlung.txt", "w");
	fwrite ($myfile, $steuernFinsch. "");
	fclose($myfile);
	//Stunden Chache
	$myfile = fopen("../cache/$username/job/Stunden.txt", "w");
	fwrite ($myfile, $anspruchsrechnung. "");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m.Y - H:i", $timestamp);

?>

<table style="width:45%" border=0 bgcolor=#2E2E2E  cellspacing=10 cellpadding=>
  <tr>
    <th><font color="MediumSpringGreen "><div style="text-align:right"><?php echo $lLohnzettel?></th>
    <th><font color="MediumSpringGreen "><div style="text-align:left"><?php echo $lfür?> <?php echo $username?> <?php echo $las?>:  <?php echo $jobTitles?></th> 
    <th><font color="white"><div style="text-align:right">vom:</th>
	<th><font color="white"><div style="text-align:left"><?echo $datum;?></th>
  </tr>
  <tr>
    <th><font color="white"><?php echo $lstd?></th>
    <th><font color="white"><?php echo $lfactor?></th> 
    <th><font color="white"><?php echo $lstufe?></th>
	<th><font color="white"></th>
  </tr>
  
  <tr>
    <td style="width:3%"><font color="white"><center><?php include("../cache/$username/job/Stunden.html");?></td>
	<td style="width:50%"><font color="white"><center><?php include("../cache/$username/job/Stundenlohn.html");?></td>
    <td style="width:60%"><font color="white"><center><?php include("../cache/$username/job/Lohnstufe.html");?> </td> 
    <td style="width:30%"><font color="white"><center><?php include("../cache/$username/job/Lohn.html");?></td> 
  </tr>
  </tr>
    <td><font color="white"><center></td>
	<td><font color="orange"><center></td> 
    <td><font color="orange"><center></td>
    <td><font color="red"><center></td> 
  </tr>
  <tr> 
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right"><?php echo $lbonus?>:</td>
    <td><font color="red"><center> <?php include("../cache/$username/job/Bonus.html");?></td> 
  </tr>
  <tr>
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right"><?php echo $EinkSt?> % <?php echo $Steuernname?>:</td>
    <td><font color="orange"><center>- <?php include("../cache/$username/job/Steuern.html");?> <?php echo $GuthabenIcon?></td> 
  </tr>
  <tr>
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center></td>
    <td><font color="white"><center>__________________</td> 
  </tr>
  <tr>
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right"><?php echo $lauszahlung?>:</td>
    <td><font color="green"><center> <?php include("../cache/$username/job/ZahlLohn.html");?> <?php echo $GuthabenIcon?></td> 
  </tr>
</table>
</font>
</br>
</br></br></br></br></br></br></br>
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
		<form action="../index.php">
			<td>
				<input style="width:160;height:32px" type="submit" value="<?php echo $PageIndex?>"></td>
			</form>
<?php if ($steuernFinsch >= 20 && $job >= 1 && $secs >= $minPlayTimeForTheJob) {
	echo'
		<form action="pay.php" method="POST">
	<td>
		<input style="width:160;height:32px" type="submit" value="Auszahlen" name="<?php echo $pay?>" />
		</td>
	</form> ';
}
else {
}
?>
<?php
if($secs <= $minPlayTimeForTheJob)
	echo "<font color='red'>" .$sorry. "<br><br>"; 
?>
		</tr>
	</table>
</br>
</body> 
</html>