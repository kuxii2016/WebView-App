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
?><center>
<?php //Rcon Connect
use Thedudeguy\Rcon;
$rcon = new Rcon($host, $port, $password, $timeout);
?>
<?php //Menü Theme
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
	<title>EE :: Staats Shop</title> 
</head> 
<body> 
	<h1>Normale Blöcke</h1></br>

	<table style="width:90%" border=0 bgcolor=#2E2E2E>
  <tr>
    <th><font color="white">Nummer</th>
    <th><font color="white">Item</th> 
    <th><font color="white">Preis</th>
  </tr>
  
  <tr>
    <td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;1 | 109 Treppe</td>
    <td><?php echo "<img src=stonebrick.png ALT=40&euro;, title=40&euro;>";?></td> 
    <td><font color="white">4 €</td>
  </tr>
  <tr>
    <td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;2 | 13 Kies</td>
    <td><?php echo "<img src=gravel.png ALT=20&euro;, title=20&euro;>";?></td>
    <td><font color="white">1 €</td>
  </tr>
    <tr>
	<td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;3 | 1 Gebrannter Stein</td>
    <td><?php echo "<img src=stone.png ALT=40&euro;, title=40&euro;>";?></td>
    <td><font color="white">1 €</td>
  </tr>
  
    <tr>
     <td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;4 | 98 Steinziegel</td>
    <td><?php echo "<img src=stonebrick.png ALT=400&euro;, title=400&euro;>";?></td> 
    <td><font color="white">4 €</td>
  </tr>
  <tr>
    <td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;5 | 4 Bruchstein</td>
    <td><?php echo "<img src=cobblestone.png ALT=200&euro;, title=200&euro;>";?></td>
    <td><font color="white">1 €</td>
  </tr>
    <tr>
	<td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;6 | 3 Erde</td>
    <td><?php echo "<img src=dirt.png ALT=400&euro;, title=400&euro;>";?></td>
    <td><font color="white">1 €</td>
  </tr>
  
    <tr>
     <td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;7 | 324 TÜR</td>
    <td><?php echo "<img src=glass_white.png ALT=4000&euro;, title=4000&euro;>";?></td> 
    <td><font color="white">6 €</td>
  </tr>
  <tr>
    <td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;8 | 20 Dickes Glas</td>
    <td><?php echo "<img src=glass_white.png ALT=2000&euro;, title=2000&euro;>";?></td>
    <td><font color="white">3 €</td>
  </tr>
    <tr>
	<td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;9 | 102 Dünnes Glas</td>
    <td><?php echo "<img src=glass.png ALT=4000&euro;, title=4000&euro;>";?></td>
    <td><font color="white">3 €</td>
  </tr>
    <tr>
	<td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;10 | 24 Sanstein</td>
    <td><?php echo "<img src=sandstone_smooth.png ALT=4000&euro;, title=4000&euro;>";?></td>
    <td><font color="white">2 €</td>
  </tr>
   <tr>
	<td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;11 | 800 Sanstein Ziegel</td>
    <td><?php echo "<img src=sandstone_smooth.png ALT=4000&euro;, title=4000&euro;>";?></td>
    <td><font color="white">4 €</td>
  </tr>
   <tr>
	<td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;12 | 159 Weißer Ton</td>
    <td><?php echo "<img src=glass_white.png ALT=4000&euro;, title=4000&euro;>";?></td>
    <td><font color="white">1 €</td>
  </tr>
</table>


<br><br> 

<?php
	$menge = $_POST['menge'][0];
	$idp = $_POST['Zahlmethode'];
	if ($idp == 109)
	{
		$gws = 4;
	}
	else 
	{
		
	}
	
	if ($idp == 13)
	{
		$gws = 1;
	}
	else 
	{
		
	}
	if ($idp == 1)
	{
		$gws = 1;
	}
	else 
	{
		
	}
	if ($idp == 98)
	{
		$gws = 4;
	}
	else 
	{
		
	}
	if ($idp == 4)
	{
		$gws = 1;
	}
	else 
	{
		
	}
	if ($idp == 3)
	{
		$gws = 1;
	}
	else 
	{
		
	}
	if ($idp == 324)
	{
		$gws = 6;
	}
	else 
	{
		
	}
	if ($idp == 20)
	{
		$gws = 3;
	}
	else 
	{
		
	}
	if ($idp == 102)
	{
		$gws = 3;
	}
	else 
	{
		
	}
	if ($idp == 5191)
	{
		$gws = 1000;
	}
	else 
	{
		
	}	
	if ($idp == 24)
	{
		$gws = 2;
	}
	if ($idp == 800)
	{
		$gws = 4;
	}
	if ($idp == 159)
	{
		$gws = 1;
	}
	$ppreis = $gws * $menge;
	if ($rcon->connect())
	{
	$rcon->sendCommand("give $username $idp $menge");
	if(isset($_POST['kaufen']))
{
    //Aktuelle Zeiterfassung
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);

	//----------------------StaatsKasse Konto Option
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-in.html", "a");
	fwrite ($myfile, "&nbsp;</br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-vz.html", "a");
	fwrite ($myfile,"Staats Einkauf - $idp: ID - $menge: St</br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-out.html", "a");
	fwrite ($myfile, $ppreis. "€</br>");
	fclose($myfile);
	
	//Staats Guthaben
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "r");
	$sbetrag = fgets($myfile);
	//Zahlung an Staatskasse
	$nsbetrag = $sbetrag - $ppreis;
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "w");
	fwrite ($myfile, $nsbetrag);
	fclose($myfile);
	//GS Admin Debug
	$myfile = fopen("../cache/log/player/$username-log.html", "a");
	fwrite ($myfile, "Spieler: $username Kaufte im Staatsshop ID: $idp ST: $menge für $ppreis € ein (WEB)</br>");
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
	<input type="radio" 	id="1" name="Zahlmethode" 	value="109">  	<label for="1"> 1</label>
	<input type="radio" 	id="2" name="Zahlmethode" 	value="13">  	<label for="2"> 2</label> 
	<input type="radio" 	id="3" name="Zahlmethode" 	value="1">  	<label for="3"> 3</label>
	
	<input type="radio" 	id="4" name="Zahlmethode" 	value="98">  	<label for="4"> 4</label> 
	<input type="radio" 	id="5" name="Zahlmethode" 	value="4">  	<label for="5"> 5</label>
	<input type="radio" 	id="6" name="Zahlmethode" 	value="3">  	<label for="6"> 6</label>
	
	<input type="radio" 	id="7" name="Zahlmethode" 	value="324">  	<label for="7"> 7</label>
	<input type="radio" 	id="8" name="Zahlmethode" 	value="20">  	<label for="8"> 8</label> 
	<input type="radio" 	id="9" name="Zahlmethode" 	value="102">  	<label for="9"> 9</label>
	
	<input type="radio" 	id="10" name="Zahlmethode" 	value="24">  	<label for="10"> 10</label>
	<input type="radio" 	id="11" name="Zahlmethode" 	value="800">  	<label for="11"> 11</label>
	<input type="radio" 	id="12" name="Zahlmethode" 	value="159">  	<label for="12"> 12</label>
	<br>
	<br><br>
	St&uuml;ckzahl: <br><input style="width:160;height:32px" type="text" name="menge[]" value="max: 64" /><br></br>
	<input style="width:160;height:32px" type="submit" value="Kaufen" name="kaufen" />  
</form>
Summe: <?php echo " $ppreis". " &euro;"; ?><br>
</body> 
</html>
</br></br>
<table>
	<tr>
		<form action="../buergermeister/index.php">
	<td>
		<input style="width:160;height:32px" type="submit" value="zur&uuml;ck"></td>
	</form>
		<form action="../index.php">
	<td>
		<input style="width:160;height:32px" type="submit" value="Hauptmenü"></td>
	</form>
</tr>
</table>
</body> 
</html>