<?php
session_start();
require_once('config/rcon.php');
require 'config/config.php';
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
if($dbdata['sprache'] == 1){
require 'conversation/1.php';
}
elseif($dbdata['sprache'] == 2){
require 'conversation/2.php';
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
?><center>
<?php
use Thedudeguy\Rcon;
$rcon = new Rcon($host, $port, $password, $timeout);
?>
<?php 
if ($username !== false && $theme == 1) {
    echo "<body style='background-color:#151515'><font color='#01DF01'>";
} elseif ($username !== false && $theme == 2) {
    echo "<body style='background-color:#B40404'>";
} elseif ($username !== false && $theme == 3) {
    echo "<body style='background-color:#08088A'><font color='#FF0000'>";
} elseif ($username !== false && $theme == 4) {
    echo "<body style='background-color:#088A08'>";
} elseif ($username !== false && $theme == 5) {
    echo "<body style='background-color:#FACC2E'>";
} elseif ($username !== false && $theme == 6) {
    echo "<body style='background-color:#FFFFFF'>";
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
	<title>NG :: Lotto</title> 
</head> 
<body> 
	<h1>EE Lotterie</h1></br>
<form action="" method="post">
zahl1: <input type="text" name="Zahl1" value="1 - 49"/><br />
zahl2: <input type="text" name="Zahl2" value="1 - 49"/><br />
zahl3: <input type="text" name="Zahl3" value="1 - 49"/><br />
zahl4: <input type="text" name="Zahl4" value="1 - 49"/><br />
zahl5: <input type="text" name="Zahl5" value="1 - 49"/><br />
zahl6: <input type="text" name="Zahl6" value="1 - 49"/><br />
<input type="Submit" value="Spielen" name="spielen" />
</form>
<?php
$myfile = fopen("cache/lotto/lotto/max-jackpot.txt", "r");
$max = fgets($myfile);
$myfile = fopen("cache/lotto/lotto/jackpot.txt", "r");
$nbetrag = fgets($myfile);
echo "Aktueller Preiss 100 &euro;";
echo "<p style='color: red'>Im Jackpot: $nbetrag &euro;</br><p style='color: blue'> Letzter Gewinn: $max &euro;</p></br></br>";
$spBetrag = 100;
$zahl1 = $_POST["Zahl1"];
$zahl2 = $_POST["Zahl2"];
$zahl3 = $_POST["Zahl3"];
$zahl4 = $_POST["Zahl4"];
$zahl5 = $_POST["Zahl5"];
$zahl6 = $_POST["Zahl6"];
if(isset($_POST['spielen']))
{
	if ($rcon->connect())
	{
	$rcon->sendCommand("wallet $username remove $spBetrag");
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	$myfile = fopen("cache/$username/bank/$username-$uuid-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	$myfile = fopen("cache/$username/bank/$username-$uuid-out.html", "a");
	fwrite ($myfile, $spBetrag. " &euro; - </br>");
	fclose($myfile);
	$myfile = fopen("cache/$username/bank/$username-$uuid-vz.html", "a");
	fwrite ($myfile,"Gl&uuml;cksspiel - Lotto </br>");
	fclose($myfile);
	$myfile = fopen("cache/$username/bank/$username-$uuid-in.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
$myfile = fopen("cache/lotto/lotto/jackpot.txt", "r");
$sbetrag = fgets($myfile);
$nsbetrag = $sbetrag + $spBetrag;
$myfile = fopen("cache/lotto/lotto/jackpot.txt", "w");
fwrite ($myfile, $nsbetrag);
fclose($myfile);
sleep(1);
sleep(1);
srand ((double)microtime()*1000000);
for($i=1; $i<7; $i++){
   $val = rand(1,49);
   (!strpos(" ".$vals, "$val")) ? $vals .= $val." " : $i--;
}
 
$arrayLotto = explode(" ", trim($vals));
sort($arrayLotto);
$zZahl1 = $arrayLotto[0];
$zZahl2 = $arrayLotto[1];
$zZahl3 = $arrayLotto[2];
$zZahl4 = $arrayLotto[3];
$zZahl5 = $arrayLotto[4];
$zZahl6 = $arrayLotto[5];
$Lottozahlen = implode(" ", $arrayLotto);
echo "Deine Zahlen:</br>";
echo "$zahl1 $zahl2 $zahl3 $zahl4 $zahl5 $zahl6</br>";
echo "</br>";
echo "Aktuelle Ziehung:</br>";
echo "$arrayLotto[0] $arrayLotto[1] $arrayLotto[2] $arrayLotto[3] $arrayLotto[4] $arrayLotto[5]";
$gezogene_zahlen = array("$zahl1", "$zahl2", "$zahl3", "$zahl4", "$zahl5", "$zahl6");
$getippteZahlen = array ("$zZahl1", "$zZahl2", "$zZahl3", "$zZahl4", "$zZahl5", "$zZahl6");
$richtige_zahlen = count(array_intersect($getippteZahlen, $gezogene_zahlen));
echo "</br></br>Richtige Zahlen: ".$richtige_zahlen;
echo "</br>"; 
$falsche_zahlen = count(array_diff($getippteZahlen, $gezogene_zahlen));
	if ($richtige_zahlen == 1)
	{
		$aGewinn = 0;
	}
	else 
	{
		
	}
	
	if ($richtige_zahlen == 2)
	{
		$aGewinn = round($nbetrag / 100 * 10);
	}
	else 
	{
		
	}
	if ($richtige_zahlen == 3)
	{
		$aGewinn = round($nbetrag / 100 * 15);
	}
	else 
	{
		
	}
	if ($richtige_zahlen == 4)
	{
		$aGewinn = round($nbetrag / 100 * 40);
	}
	else 
	{
		
	}
	if ($richtige_zahlen == 5)
	{
		$aGewinn = round($nbetrag / 100 * 60);
	}
	else 
	{
		
	}
	if ($richtige_zahlen == 6)
	{
		$aGewinn = round($nbetrag / 100 * 80);
	}
	else 
	{
		
	}
		if ($richtige_zahlen == 0)
	{
		$aGewinn = 0.00;
	}
	else 
	{
		
	}
echo "<p style='color: green'>Du Gewinnst: $aGewinn &euro;";
sleep(1);
$myfile = fopen("cache/lotto/lotto/jackpot.txt", "r");
$xbetrag = fgets($myfile);
$nxsbetrag = $xbetrag - $aGewinn;
$myfile = fopen("cache/lotto/lotto/jackpot.txt", "w");
fwrite ($myfile, $nxsbetrag);
fclose($myfile);
$myfile = fopen("cache/log/player/$username-log.html", "a");
fwrite ($myfile, "Spieler: $username Spielte Lotto und Gewann $aGewinn &euro; (WEB)</br>");
fclose($myfile);
$timestamp = time();
$datum = date("d.m.y-H:i", $timestamp);
$myfile = fopen("cache/log/player/$username-date.html", "a");
fwrite ($myfile, $datum. "&nbsp;</br>");
fclose($myfile);
}
else
{
}
}
	if ($richtige_zahlen >= 2)
	{
			if ($rcon->connect())
	{
	$rcon->sendCommand("say $username Kassiert $aGewinn EUR, Im Lotto.!");
	$rcon->sendCommand("say Hatte $richtige_zahlen Richtige Zahl, Im Lotto.!");
	$rcon->sendCommand("wallet $username add $aGewinn");
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	$myfile = fopen("cache/$username/bank/$username-$uuid-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	$myfile = fopen("cache/$username/bank/$username-$uuid-out.html", "a");
	fwrite ($myfile, "&nbsp; </br>");
	fclose($myfile);
	$myfile = fopen("cache/$username/bank/$username-$uuid-vz.html", "a");
	fwrite ($myfile,"Gl&uuml;cksspiel - Lottogewinn </br>");
	fclose($myfile);
	$myfile = fopen("cache/$username/bank/$username-$uuid-in.html", "a");
	fwrite ($myfile, $aGewinn. " &euro;</br>");
	fclose($myfile);
$myfile = fopen("cache/lotto/lotto/max-jackpot.txt", "w");
fwrite ($myfile, $aGewinn);
fclose($myfile);
		}
	else 
	{
	}
	}
?> 
</br></br>
<table>
	<tr>
		<form action="Casino.php">
	<td>
		<input style="width:160;height:32px" type="submit" value="<?php echo $zuruek; ?>"></td>
	</form>
		<form action="index.php">
	<td>
		<input style="width:160;height:32px" type="submit" value="<?php echo $PageIndex; ?>"></td>
	</form>
</tr>
</table>