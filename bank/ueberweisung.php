<?php
session_start();
require_once('../config/rcon.php');
require '../config/config.php';
require '../config/Multiplikator.php';
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
<?php
	//Rcon Connect
	use Thedudeguy\Rcon;
	$rcon = new Rcon($host, $port, $password, $timeout);
	//Abfrage der Nutzer ID vom Login
	$user = 	$_SESSION['useid'];
	$sn = 		$_POST['sn'][0];
	$uuids = 	$_POST['uuid'][0];
	$vz = 		$_POST['vz'][0];
	$summe = 	$_POST['summe'][0];


	if ($rcon->connect())
	{
	$rcon->sendCommand("wallet $username remove $summe");
	$rcon->sendCommand("wallet $sn add $summe");
	$rcon->sendCommand("tell $sn $username Überwieß $summe  $GuthabenIcon.Ihre Haze Maze Bank.!");
	if(isset($_POST['kaufen']))
	{
	//Read Funktion.
	$myfilec = fopen("../cache/bank/$uuid-$username-$sn.txt", "w");
	fwrite ($myfilec,"Von: " .$username. " | VZ: " .$vz ." | Betrag: " .$summe. " - EUR |-| To: ". $sn. " | VZ: " .$vz ." | Betrag: " .$summe. " + EUR");
	fclose($myfilec);
    //Aktuelle Zeiterfassung
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-out.html", "a");
	fwrite ($myfile, $summe. " $GuthabenIcon </br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-vz.html", "a");
	fwrite ($myfile,"=> " .$sn. " | " .$vz ."</br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-in.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	
	//To Player Konto Auszug
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfilea = fopen("../cache/$sn/bank/$sn-$uuids-date.html", "a");
	fwrite ($myfilea, $datum. "</br>");
	fclose($myfilea);
	//schreibt die betrag ins Doc.
	$myfilea = fopen("../cache/$sn/bank/$sn-$uuids-in.html", "a");
	fwrite ($myfilea, $summe. " $GuthabenIcon </br>");
	fclose($myfilea);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfilea = fopen("../cache/$sn/bank/$sn-$uuids-vz.html", "a");
	fwrite ($myfilea, $username. " <= | " .$vz ."</br>");
	fclose($myfilea);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfilea = fopen("../cache/$sn/bank/$sn-$uuids-out.html", "a");
	fwrite ($myfilea, "&nbsp;" ."</br>"); 
	fclose($myfilea);

	$user1 = $_SESSION['useid'];
	//player1 KontoAktualisierung
	$pIakt = $dbdata['geld'] - $summe;
	$statement = $pdo->prepare("UPDATE `users` SET `geld` = '$pIakt' WHERE name = '$username' ");
	$result = $statement->execute(array("UPDATE `users` SET `geld` = '$pIakt' WHERE name = '$username' "));
	//player2 KontoAktualisierung
	
	$statement = $pdo->prepare("SELECT * FROM users WHERE name = :name");
	$result = $statement->execute(array('name' => $sn));
	$dbdatai = $statement->fetch();
	$pIIgeld = $dbdatai['geld'];
	$pIIakt = $dbdatai['geld'] + $summe;
	$statement = $pdo->prepare("UPDATE `users` SET `geld` = '$pIIakt' WHERE name = '$sn' ");
	$result = $statement->execute(array("UPDATE `users` SET `geld` = '$pIIakt' WHERE name = '$sn' "));
	
	//Letzten Namen
	$myfile = fopen("../cache/$username/bank/$username-$uuid-name.html", "a");
	fwrite ($myfile, $sn. "</br>");
	fclose($myfile);
	//Letzten Ibans
	$myfile = fopen("../cache/$username/bank/$username-$uuid-uuid.html", "a");
	fwrite ($myfile, $uuids. "</br>");
	fclose($myfile);
	$myfile = fopen("../cache/$username/bank/$uuid-$username.txt", "w");
	fwrite ($myfile, "$uuid-$username-$sn");
	fclose($myfile);
	
	}	
	else
	{
	}
	}

	
?>

<!doctype html> 
<html> 
<head>
		<meta charset="utf-8"> 
		<meta name="description" content="Economy Expansion">
		<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Economy Expansion, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
		<meta name="author" content="Michael Kux">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>EE :: <?php echo $playertransfer; ?></title>
</head> 
<center>
<body> 
	<h1><?php echo $playertransfer; ?></h1> 
<table>
	<tr>
		<form  method="POST">
			<input type="fiel1" 	name="sn[]"	value="<?php echo $player; ?>">
			<input type="fiel2" 	name="uuid[]"	value="IBAN"><br><br>
			<input type="field3" 	name="vz[]"	value="<?php echo $commit; ?>">
			<input type="field4" 	name="summe[]"	value="Summe"><br><br>
	<td>
</table>			
<?php 
	$myfile = fopen("../cache/$username/bank/$uuid-$username.txt", "r");
	$ticketID = fgets($myfile);
	echo "</br>Letzte Ticket-ID:</br>";
	echo"Ticket-ID: $ticketID</br>";
	?>
</br>
<?php echo $lasttransfer; ?>
<table border=0 bgcolor=#FA5858>
<tr>
	<th bgcolor=#FA5858> <?php echo $player; ?></th>
	<th bgcolor=#FA5858> IBAN</th>

</tr>
</br></br>
<tr>
	<td bgcolor=#BDBDBD><?php include("../cache/$username/bank/$username-$uuid-name.html");?></td>
	<td bgcolor=#A4A4A4><?php include("../cache/$username/bank/$username-$uuid-uuid.html");?></td>
</tr>

</table>
</br>
<?php echo $kassen; ?>
<table border=0 bgcolor=#FA5858>
<tr>
	<th bgcolor=#FA5858> <?php echo $player; ?></th>
	<th bgcolor=#FA5858> IBAN</th>
</tr>
</br></br>
<tr>
	<td bgcolor=#BDBDBD><?php include("../cache/bank/oe-name.html");?></td>
	<td bgcolor=#A4A4A4><?php include("../cache/bank/oe-uuid.html");?></td>
</tr>
</table>
</br></br></br></br></br>
<table>
<tr>
<td>
<input type="submit" value="<?php echo $maketransfer; ?>" name="kaufen" style="color:white; background-color:red; width:90;height:32px"></td>
</form><td>
	<form action="../index.php">
		<td>
			<input style="width:90;height:32px" type="submit" value="<?php echo $PageIndex; ?>"></td>
		</form><td>
	<form action="uleer.php">
		<td>
			<input style="width:90;height:32px" type="submit" value="<?php echo $deleteview; ?>"></td>
		</form>
	</tr>
</table>
</body> 
</html>

 