<?php
function debug($input)
{
    echo "<pre>";
    var_dump($input);
    echo "</pre>";
}

function search($array, $key, $value)
{
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, search($subarray, $key, $value));
        }
    }
    return $results;
}
session_start();
require '/var/www/html/data/Pconfig.php';
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
	<title>NG :: Spieler-Daten</title> 
</head>
<center>
<h1>Spieler Daten</h1> 
<?php 
	//Kontodaten
	echo "IBAN: " .$uuid.  "</br></br>NAME: ".$dbdata['name'];	
?>
</br></br>
<?php //Gespielte Zeit
	$datawallet = "$pinfo/$uuid.json";
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	$tdata = floor($namen['timePlayed']/1000);
	$secs = $tdata;
	$now = date_create('now', new DateTimeZone ('GMT'));
	$here = clone $now;
	$here->modify($secs. 'seconds');
	$diff = $now->diff($here);
	$nowTimeDate = date("Y-m-d");
	$days = floor((strtotime("$nowTimeDate") - strtotime("2017-09-07"))/86400); 	
	echo "Spielzeit : " .$diff->format('%a Tag(e) %h Stunde(n) %i Minute(n) %s sec')."</br></br>";
	echo "Map Laufzeit : ".$days ." Tage.";
?>
</br></br>
<?php //PLAYERINFO FirstLogin
	$datawallet = "$pinfo/$uuid.json";
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	echo "Erster Besuch : " .$namen['firstLogin'];	
?>
</br></br>
<?php //PLAYERINFO LastLogin
	$datawallet = "$pinfo/$uuid.json";
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	echo "Letzter Besuch : " .$namen['lastLogin'];	
?>
</br></br>
<?php //PLAYERINFO LogOut
	$datawallet = "$pinfo/$uuid.json";
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	echo "Zuletzt Ausgeloggt : " .$namen['lastLogout'];	
?>
</br></br>
<?php //GELDINFO
	$datawallet = "$wallet/$uuid.json";
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	$geld = $namen['amount'];
	echo "Geld : " .$geld. " €";
	$statement = $pdo->prepare("UPDATE `users` SET `geld` = '$geld' WHERE name = '$username' ");
	$result = $statement->execute(array("UPDATE `users` SET `geld` = '$geld' WHERE name = '$username' "));
?></br></br>
<?php
echo "<div style='text-align: center;'><img src='http://a.nuclear-gaming.de/data/3d.php?vr=-25&hr=-25&hrh=10&vrla=5&vrra=-2&vrll=-20&vrrl=2&ratio=12&format=png&displayHair=true&headOnly=false&user=$username'  width='100' height='200'></div>";
?>
</br></br></br>
<table>
<tr>
	<form action="index.php">
		<td>
			<input style="width:100;height:32px" type="submit" value="Start Seite"></td>
			</form>

	</table>
</body> 
</html>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>