<?php
session_start();
require '../config/config.php';
require_once('../config/rcon.php');
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
$rechte = $dbdata['box2'];
$ZiD = $dbdata['zID'];
$ZiD1 = $dbdata['zID1'];
$ZiD2 = $dbdata['zID2'];
$ZiD3 = $dbdata['zID3'];
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
<?php 
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
$myfile = fopen("../cache/akw/auftrag/aktuell-log.html", "a");
fwrite ($myfile, "Spieler: $username Hat eine Störung.</br>");
fclose($myfile);
$timestamp = time();
$datum = date("d.m.y-H:i", $timestamp);
$myfile = fopen("../cache/akw/auftrag/aktuell-date.html", "a");
fwrite ($myfile, $datum. "</br>");
fclose($myfile);
	$myfile = fopen("../cache/log/player/$username-log.html", "a");
	fwrite ($myfile, "Spieler: $username Hat eine Störung(WEB)</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	$myfile = fopen("../cache/log/player/$username-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
	use Thedudeguy\Rcon;
	$rcon = new Rcon($host, $port, $password, $timeout);
	if ($rcon->connect())
	{
	$rcon->sendCommand("tell $username Störung wurde Gemeldet, bitte habe Gedult.");
	$rcon->sendCommand("tell $energyManager $username Hat eine Störung -> neuer Auftrag.");
	}
?>

<?php 
sleep(0.1); header('Location: .//index.php'); 
?>
</tr>
</table>
</body> 
</html>