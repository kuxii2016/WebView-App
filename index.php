<?php
session_start();
require 'data/MySqlconfig.php';
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
//---Spieler-Box
$rechte = $dbdata['box2'];
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
 	//schreibt den Sünder in den Log.
	//$myfile = fopen("/var/www/html/daten/log/log.html", "a");
	//fwrite ($myfile, "Spieler: $username Loggte sich ein(APP).</br>");
	//fclose($myfile);
	//$timestamp = time();
	//$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	//$myfile = fopen("/var/www/html/daten/log/date.html", "a");
	//fwrite ($myfile, $datum. "&nbsp;</br>");
	//fclose($myfile);
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

<?php   //USERLOGGED BOX
	if ($job== 0){
	$jobTitles = "HatzIV";
	}
	if ($job>= 1){
	$jobTitles = $hLohnVZ;
	}
	if ($job == 2){
	$jobTitles = $fLohnVZ;
	}
	if ($job >= 3){
	$jobTitles = $fLohnVZ;
	}
	if ($job >= 4){
	$jobTitles = $gLohnVZ;
	}
	if ($job == 5){
	$jobTitles = $VLohnVZ;
	}
	if ($job >= 6){
	$jobTitles = $bLohnVZ;
	}
	if ($job >= 7){
	$jobTitles = $jLohnVZ;
	}
	if ($job >= 8){
	$jobTitles = $sLohnVZ;
	}
	if ($job >= 9){
	$jobTitles = $BLohnVZ;
	}
	if ($job >= 10){
	$jobTitles = $iLohnVZ;
	}	
	if ($job >= 11){
	$jobTitles = $pLohnVZ;
	}
	if ($job >= 12){
	$jobTitles = $PLohnVZ;
	}
if ( $dbdata['box1'] == 1) {
$userid = $_SESSION['userid'];
echo "<table border=0 bgcolor='green' width=100% >";
{
  echo "<tr>";
  echo "<td><img src='http://minotar.net/avatar/$username/10.png' alt='$username' width='30' height='30'></td>";
  echo "<td width=30%><font face='Verdana'><font color='#FFFFFF'>",$username,"</td>";
  echo "<td width=30%><font face='Verdana'><font color='#FFFFFF'>Job: ".$jobTitles ."</td>";
  echo "<td width=60%><font face='Verdana'><font color='#FFFFFF'>","Guthaben: ".$geld." €","</td>";
  echo "</tr>";
}echo "</table>";
}
else
{	
echo "";
echo "<h1>Hauptmenü</h1>";
}
?>
<!doctype html> 
<html> 
<head>
	<meta charset="utf-8"> 
	<meta name="description" content="Nuclear Gaming Panel">
	<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Nuclear Gaming, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
	<meta name="author" content="Michael Kux">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NG :: Kontrollcenter</title> 
	<meta http-equiv=“cache-control“ content=“no-cache“>
	<meta http-equiv=“pragma“ content=“no-cache“>
	<meta http-equiv=“expires“ content=“0″>
</head>

<body> 
</br> 
<table>
	<tr>
		<form action="konto.php">
			<td>
				<input style="width:167;height:32px" type="submit" value="Hazemaze Bank">
			</td>
		</form>
		<form action="info.php">
			<td>
				<input style="width:167;height:32px" type="submit" value="Spieler Info">
			</td>
		</form>
	</tr>
	<tr>
		<form action="plots.php">
			<td>
				<input style="width:167;height:32px" type="submit" value="Grundstücke"></td>
		</form>
		<form action="sozih.php">
			<td>
				<input style="width:167;height:32px" type="submit" value="Sozial Hilfe"></td>
		</form>
	</tr>
	<tr>
		<form action="emeter.php">
			<td>
				<input style="width:167;height:32px" type="submit" value="Luem Energy"></td>
		</form>
		<form action="shop.php">
			<td>
				<input style="width:167;height:32px" type="submit" value="Online-Shop"></td>
		</form>
	</tr>
	<tr>
	<form action="MS-index.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="MineAPP"></td>
	</form>
	<form action="help.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="Regelwerk"></td>
	</form>
	</tr>
	<tr>
	<form action="Dynmap.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="DynMap"></td>
	</form>
	<form action="stats.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="Statistik"></td>
	</form>
</tr>
	<tr>
	<form action="settings.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="Einstellungen"></td>
	</form>
	<form action="job.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="Lohn Büro"></td>
	</form>
</tr>
	<tr>
	<form action="Casino.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="Casino"></td>
	</form>
	<form action="logout.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="Ausloggen"></td>
	</form>
</tr>
	<?php if ($dbdata['rechte'] == 1) {
	echo' 
	<tr>
	<form action="staatskasse.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Staatskasse">
	</td>
	</form>
	<form action="ssetting.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Admin Menü">
	</td>
	</form> ';
}
else {
}
?>
	<?php if ($dbdata['rechte'] == 2) {
	echo' 
	<tr>
	<form action="staatskasse.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Staatskasse">
	</td>
	</form>
	<form action="ssetting.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Admin Menü">
	</td>
	</form> ';
	echo' 
	<tr>
	<form action="/notiz/index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Notiz Block">
	</td>
	</form>
	<form action="index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="           ">
	</td>
	</form> ';
}
else {
}
?>
	<?php if ($dbdata['rechte'] == 3) {
	echo' 
	<tr>
	<form action="staatskasse.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Staatskasse">
	</td>
	</form>
	<form action="ssetting.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Admin Menü">
	</td>
	</form> ';
	echo' 
	<tr>
	<form action="/notiz/index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Notiz Block">
	</td>
	</form>
	<form action="/RCON/index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Console">
	</td>
	</form> ';
}
else {
}
?>
	</table>

<h6>
<?php
echo "<center>Panel version: 2.2 | &copy; by Kuxii .</center></br></br>";
?>
<?php
	$myfile = fopen("daten/log/anews.txt", "r");
	$Text = fgets($myfile);
?></br></br></br></br></br></br></br></br></br></br></br>
<?php   //USERLOGGED BOX
if ( $dbdata['box2'] == 1) {
$userid = $_SESSION['userid'];
echo "<table border=0 bgcolor='green' >";
{
  echo "<tr>";
  echo "<td width=70><font face='Verdana'><font color='#FFFFFF'>News:</td>";
  echo "<td width=600><font face='Verdana'><font color='#FFFFFF'><marquee>$Text</marquee></td>";
  echo "</tr>";
}echo "</table>";
}
else
{	
echo "";
echo "<h1></h1>";
}
?>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
</body> 
</html>