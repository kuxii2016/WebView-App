<?php
session_start();
require 'data/MySqlconfig.php';
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

<?php
$name = $_POST['name'];
?>
<!doctype html> 
<html> 
<head>
	<meta charset="utf-8"> 
	<meta name="description" content="Nuclear Gaming Panel">
	<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Nuclear Gaming, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
	<meta name="author" content="Michael Kux">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NG :: Zähler Settings</title> 
</head> 
<body> 
	<h1>Zähler hinzufügen</h1>
<form method="POST">
SpielerName:<br>
<input type="name" size="40" maxlength="250" name="name"><br><br> 


<h1>Strom Zähler von <?php echo $name;?> </h1>
<table style="width:90%" border=0 bgcolor=#2E2E2E>
  <tr>
    <th><font color="white"></th>
    <th><font color="white">ID</th>
    <th><font color="white">EU</th> 
    <th><font color="white">Preis</th>
    <th><font color="white">Laufzeit</th> 
    <th><font color="white">Stand</th>
	<th><font color="white">Offen</th>
  </tr>
  
  <tr>
	<td style="width:2%"><font color="white"><center>1</td>
    <td style="width:3%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD-ID.html");?></td>
    <td style="width:4%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD-euamount.html");?></td> 
    <td style="width:3%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD-preis.html");?></td>
	<td style="width:50%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD-timeSincerunSec.html");?></td>
    <td style="width:60%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD-euconsumedkilo.html");?></td> 
    <td style="width:30%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD-cost.html");?></td> 
  </tr>
    <tr>
	<td style="width:2%"><font color="white"><center>2</td>
    <td style="width:3%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD1-ID.html");?></td>
    <td style="width:4%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD1-euamount.html");?></td> 
    <td style="width:3%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD1-preis.html");?></td>
	<td style="width:50%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD1-timeSincerunSec.html");?></td>
    <td style="width:60%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD1-euconsumedkilo.html");?></td> 
    <td style="width:30%"><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD1-cost.html");?></td> 
  </tr>
    <tr>
	<td><font color="white"><center>3</td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD2-ID.html");?></td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD2-euamount.html");?></td> 
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD2-preis.html");?></td>
	<td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD2-timeSincerunSec.html");?></td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD2-euconsumedkilo.html");?></td> 
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD2-cost.html");?></td> 
  </tr>
    <tr>
	<td><font color="white"><center>4</td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD3-ID.html");?></td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD3-euamount.html");?></td> 
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD3-preis.html");?></td>
	<td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD3-timeSincerunSec.html");?></td>
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD3-euconsumedkilo.html");?></td> 
    <td><font color="white"><center><?php include("/var/www/html/daten/emeter/$name/$ZiD3-cost.html");?></td> 
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
    <td><font color="orange"><center><?php include("/var/www/html/daten/emeter/$name/$name-KEU.html");?> KEU</td>
    <td><font color="red"><center></td> 
  </tr>
  <tr>
	<td><font color="white"><center></td>
    <td><font color="white"><center></td>
    <td><font color="white"><center></td> 
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right">Gesammte Kosten:</td>
    <td><font color="red"><center>- <?php include("/var/www/html/daten/emeter/$name/$name-cost.html");?> &euro;</td> 
  </tr>
  <tr>
	<td><font color="white"><center></td>
    <td><font color="white"><center></td>
    <td><font color="white"><center></td> 
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right">Bezahlt:</td>
    <td><font color="green"><center>+ <?php include("/var/www/html/daten/emeter/$name/$name-bezahlt.html");?> &euro;</td> 
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
    <td><font color="orange"><center> <?php include("/var/www/html/daten/emeter/$name/$name-neuekosten.html");?> &euro;</td> 
  </tr>
</table>
</font>

<?php //RAW DATA
	$raw = $_POST['raw'];
		//Stromstärke
	$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$raw/euamount", "r");
	$euamount11 = fgets ($myfile);
	//Aktueller Betrag
	$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$raw/cost", "r");
	$cost11 = fgets ($myfile); 
	//Laufzeit
	$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$raw/timeSincerun", "r");
	$timeSincerunSec11 = fgets ($myfile); 
	//Strom Verbrauch
	$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$raw/euconsumedkilo", "r");
	$euconsumedkilo11 = fgets ($myfile);
	//Strom Preis
	$myfile = fopen("/home/ogpbot/OGP_User_Files/2/Citybuild/computer/$raw/preis", "r");
	$preis11 = fgets ($myfile);	
	if(isset($_POST['raw']))
	{
		$rawdata = $euamount11."EU/t|".round($cost11,2)."€|".$euconsumedkilo11."kEU";
	}

	else
	{
	}
?></br></br>
<form method="POST">
<font color="white">ID: <?php echo $rawdata;?><br>
<input type="name" size="40" maxlength="250" name="raw"><br>
<br><br><br>
<table>
<tr>
<form action="zkontolle.php">
<td><input style="width:100;height:32px" type="submit" value="Raw"></td>
</form>
<form action="aAdmin.php">
<td><input style="width:100;height:32px" type="submit" value="Zurück"></td>
</form>
<td><input style="width:100;height:30px" type="submit" value="Zeige Daten" name="Zeige Daten" /></td>
</form>

</tr>
</table>
</body> 
</html>
