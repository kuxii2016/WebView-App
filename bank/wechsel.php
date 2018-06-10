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
<?php //rcon Load
	use Thedudeguy\Rcon;
	$rcon = new Rcon($host, $port, $password, $timeout);
?>
<!doctype html> 
<html> 
<head>
	<meta charset="utf-8"> 
	<meta name="description" content="Nuclear Gaming Panel">
	<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Nuclear Gaming, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
	<meta name="author" content="Michael Kux">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>EE :: <?php echo $moneyswitch ?></title> 
</head> <center>
<body> 
	<h1><?php echo $moneyswitch ?></h1></br>
<table style="width:90%" border=0 bgcolor=#2E2E2E>
  <tr>
    <th style="width:10%"><font color="white">Nummer</th>
    <th><font color="white">Item</th> 
    <th><font color="white">Preis</th>
  </tr> 
  <tr>
    <td><font color="white"> <center>&nbsp;&nbsp;&nbsp;&nbsp;1 </td>
    <td><?php echo " <center><img src=note_1.png ALT=1&euro;, title=1&euro;>";?></td> 
    <td><font color="white"> <center>1 <?php echo $GuthabenIcon ?></td>
  </tr>
  <tr>
    <td><font color="white"> <center>&nbsp;&nbsp;&nbsp;&nbsp;2 </td>
    <td><?php echo " <center><img src=note_2.png ALT=2&euro;, title=2&euro;>";?></td>
    <td><font color="white"> <center>2 <?php echo $GuthabenIcon ?></td>
  </tr>
    <tr>
	<td><font color="white"> <center>&nbsp;&nbsp;&nbsp;&nbsp;3 </td>
    <td><?php echo " <center><img src=note_5.png ALT=5&euro;, title=5&euro;>";?></td>
    <td><font color="white"> <center>5 <?php echo $GuthabenIcon ?></td>
  </tr>
  
    <tr>
     <td><font color="white"> <center>&nbsp;&nbsp;&nbsp;&nbsp;4 </td>
    <td><?php echo " <center><img src=note_10.png ALT=10&euro;, title=10&euro;>";?></td> 
    <td><font color="white"> <center>10 <?php echo $GuthabenIcon ?></td>
  </tr>
  <tr>
    <td><font color="white"> <center>&nbsp;&nbsp;&nbsp;&nbsp;5 </td>
    <td><?php echo " <center><img src=note_20.png ALT=20&euro;, title=20&euro;>";?></td>
    <td><font color="white"> <center>20 <?php echo $GuthabenIcon ?></td>
  </tr>
    <tr>
	<td><font color="white"> <center>&nbsp;&nbsp;&nbsp;&nbsp;6 </td>
    <td><?php echo " <center><img src=note_50.png ALT=50&euro;, title=50&euro;>";?></td>
    <td><font color="white"> <center>50 <?php echo $GuthabenIcon ?></td>
  </tr>
  
    <tr>
     <td><font color="white"> <center>&nbsp;&nbsp;&nbsp;&nbsp;7 </td>
    <td><?php echo " <center><img src=note_100.png ALT=100&euro;, title=100&euro;>";?></td> 
    <td><font color="white"> <center>100 <?php echo $GuthabenIcon ?></td>
  </tr>
  <tr>
    <td><font color="white"> <center>&nbsp;&nbsp;&nbsp;&nbsp;8 </td>
    <td><?php echo " <center><img src=note_200.png ALT=200&euro;, title=200&euro;>";?></td>
    <td><font color="white"> <center>200 <?php echo $GuthabenIcon ?></td>
  </tr>
    <tr>
	<td><font color="white"> <center>&nbsp;&nbsp;&nbsp;&nbsp;9 </td>
    <td><?php echo " <center><img src=note_500.png ALT=500&euro;, title=500&euro;>";?></td>
    <td><font color="white"> <center>500 <?php echo $GuthabenIcon ?></td>
  </tr>
     <tr>
	<td><font color="white"> <center>&nbsp;&nbsp;&nbsp;&nbsp;10 </td>
    <td><?php echo " <center><img src=note_1000.png ALT=1000&euro;, title=1000&euro;>";?></td>
    <td><font color="white"> <center>1000 <?php echo $GuthabenIcon ?></td>
  </tr>
</table>
<?php
	$menge = $_POST['menge'][0];
	$idp = $_POST['Zahlmethode'];
	if ($idp == $idp1)
	{
		$gws = 1;
	}
	else 
	{
		
	}
	
	if ($idp == $idp2)
	{
		$gws = 2;
	}
	else 
	{
		
	}
	if ($idp == $idp3)
	{
		$gws = 5;
	}
	else 
	{
		
	}
	if ($idp == $idp4)
	{
		$gws = 10;
	}
	else 
	{
		
	}
	if ($idp == $idp5)
	{
		$gws = 20;
	}
	else 
	{
		
	}
	if ($idp == $idp6)
	{
		$gws = 50;
	}
	else 
	{
		
	}
	if ($idp == $idp7)
	{
		$gws = 100;
	}
	else 
	{
		
	}
	if ($idp == $idp8)
	{
		$gws = 200;
	}
	else 
	{
		
	}
	if ($idp == $idp9)
	{
		$gws = 500;
	}
	else 
	{
		
	}
	if ($idp == $idp10)
	{
		$gws = 1000;
	}
	else 
	{
		
	}	
	$preis = $gws * $menge;
    if ($rcon->connect())
	{
	$rcon->sendCommand("give $username $idp $menge");
	$rcon->sendCommand("wallet $username remove $preis");
	if(isset($_POST['kaufen']))
{
    //Aktuelle Zeiterfassung
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-out.html", "a");
	fwrite ($myfile, $preis. " $GuthabenIcon - </br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-vz.html", "a");
	fwrite ($myfile,"$moneyswitch - $idp:ID - $menge:St </br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-in.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	$newState = $dbdata['geld'] - $preis;
	$statement = $pdo->prepare("UPDATE `users` SET `geld` = '$newState' WHERE name = '$username' ");
	$result = $statement->execute(array("UPDATE `users` SET `geld` = '$newState' WHERE name = '$username' "));
	//GS Admin Debug
	$myfile = fopen("../cache/log/player/$username-log.html", "a");
	fwrite ($myfile, "Spieler: $username Geld Gewechselt $idp : $menge für $preis € (WEB)</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/log/player/$username-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
}
else
{
}
}
?>
<form method="POST">
	<input type="radio" 	id="1" name="Zahlmethode" 	value="<?php echo $idp1; ?>">  	<label for="1"> 1</label>
	<input type="radio" 	id="2" name="Zahlmethode" 	value="<?php echo $idp2; ?>">  	<label for="2"> 2</label> 
	<input type="radio" 	id="3" name="Zahlmethode" 	value="<?php echo $idp3; ?>">  	<label for="3"> 3</label>
	<input type="radio" 	id="4" name="Zahlmethode" 	value="<?php echo $idp4; ?>">  	<label for="4"> 4</label> 
	<input type="radio" 	id="5" name="Zahlmethode" 	value="<?php echo $idp5; ?>">  	<label for="5"> 5</label>
 <br>
	<input type="radio" 	id="6" name="Zahlmethode" 	value="<?php echo $idp6; ?>">  	<label for="6"> 6</label>
	<input type="radio" 	id="7" name="Zahlmethode" 	value="<?php echo $idp7; ?>">  	<label for="7"> 7</label>
	<input type="radio" 	id="8" name="Zahlmethode" 	value="<?php echo $idp8; ?>">  	<label for="8"> 8</label> 
	<input type="radio" 	id="9" name="Zahlmethode" 	value="<?php echo $idp9; ?>">  	<label for="9"> 9</label>
	<input type="radio" 	id="10" name="Zahlmethode" 	value="<?php echo $idp10; ?>">  	<label for="10"> 10</label>
 <br><br> 
 St&uuml;ckzahl: <br><input style="width:160;height:32px" type="text" name="menge[]" value="max: 64" /><br></br>
	<input style="width:160;height:32px" type="submit" value="Kaufen" name="kaufen" />  
</form>
Summe: <?php echo " $preis". " $GuthabenIcon"; ?>
</body> 
</html>
</br></br>
<table>
	<tr>
		<form action="index.php">
	<td>
		<input style="width:160;height:32px" type="submit" value="<?php echo $zuruek ?>"></td>
	</form>
		<form action="../index.php">
	<td>
		<input style="width:160;height:32px" type="submit" value="<?php echo $PageIndex ?>"></td>
	</form>
</tr>
</table>
</body> 
</html>
