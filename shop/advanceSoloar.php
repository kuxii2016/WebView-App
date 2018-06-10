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
	<title>EE :: Advance Solar</title> 
</head> 
<body> 
	<h1>Advance Solar</h1></br>

	<table style="width:90%" border=0 bgcolor=#2E2E2E>
  <tr>
    <th><font color="white">Nummer</th>
    <th><font color="white">Item</th> 
    <th><font color="white">Preis</th>
  </tr>
  
  <tr>
    <td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;1 AdvancedSolarHelm</td>
    <td><?php echo "<img src=UltimateSolarHelmet.png ALT=40&euro;, title=40&euro;>";?></td> 
    <td><font color="white">1000 €</td>
  </tr>
  <tr>
    <td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;2 Hybrid Solar Helm</td>
    <td><?php echo "<img src=AdvSolarHelmet.png ALT=20&euro;, title=20&euro;>";?></td>
    <td><font color="white">2000 €</td>
  </tr>
    <tr>
	<td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;3 Ultimative Solar Helm</td>
    <td><?php echo "<img src=HybridSolarHelmet.png ALT=40&euro;, title=40&euro;>";?></td>
    <td><font color="white">3000 €</td>
  </tr>  
    <tr>
     <td><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;4 Molecular Transformator</td>
    <td><?php echo "<img src=MTCore.png ALT=400&euro;, title=400&euro;>";?></td> 
    <td><font color="white">4000 €</td>
  </tr>
</table>

<br><br> 

<?php
	$menge = $_POST['menge'][0];
	$idp = $_POST['Zahlmethode'];
	if ($idp == 1)//KAROTTE
	{
		$gws = 1000;
		$tool = "advanced_solar_helmet";
	}
	else 
	{
		
	}
	
	if ($idp == 2)//KARTOFFEL
	{
		$gws = 2000;
		$tool = "hybrid_solar_helmet";
	}
	else 
	{
		
	}
	if ($idp == 3)
	{
		$gws = 3000;
		$tool = "ultimate_solar_helmet";
	}
	else 
	{
		
	}
	if ($idp == 4)
	{
		$gws = 4000;
		$tool = "BlockMolecularTransformer";
	}
	else 
	{
		
	}
	
	$ppreis = $gws * $menge;
	$preis = $ppreis + $gMwSt;	
	$gMwSt = round($ppreis/ 100 * $MwSt);

	if(isset($_POST['kaufen']))
	{
	if ($rcon->connect())
	{
	$rcon->sendCommand("give $username AdvancedSolarPanel:$tool $menge");
	$rcon->sendCommand("wallet $username remove $preis");
	$rcon->sendCommand("tell $username Danke für deinen Einkauf.!");
     //Aktuelle Zeiterfassung
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-out.html", "a");
	fwrite ($myfile, $preis. " &euro;</br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-vz.html", "a");
	fwrite ($myfile,"Einkauf - ITEM: $tool - ST: $menge </br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/$username/bank/$username-$uuid-in.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	//----------------------StaatsKasse Konto Option
	//schreibt die Zeit ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-date.html", "a");
	fwrite ($myfile, $datum. "</br>");
	fclose($myfile);
	//schreibt die betrag ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-in.html", "a");
	fwrite ($myfile, $gMwSt. " €</br>");
	fclose($myfile);	
	//schreibt die verwendungs Zweck ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-vz.html", "a");
	fwrite ($myfile,"$username :Einkauf - ITEM: $tool - ST: $menge </br>");
	fclose($myfile);	
	//schreibt die ausgabe Zweck ins Doc.
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934-out.html", "a");
	fwrite ($myfile, "&nbsp;" ."</br>");
	fclose($myfile);
	
	//Staats Guthaben
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "r");
	$sbetrag = fgets($myfile);
	//Zahlung an Staatskasse
	$nsbetrag = $gMwSt + $sbetrag;
	$myfile = fopen("../cache/Staat/bank/Staat-1988abcd-4321-1844-9876-9876aghd8934.txt", "w");
	fwrite ($myfile, $nsbetrag);
	fclose($myfile);

}
else
{
}
}

?>
<form method="POST">
	<input type="radio" 	id="1" name="Zahlmethode" 	value="1">  	<label for="1"> 1</label>
	<input type="radio" 	id="2" name="Zahlmethode" 	value="2">  	<label for="2"> 2</label> 
	<input type="radio" 	id="3" name="Zahlmethode" 	value="3">  	<label for="3"> 3</label>
	<input type="radio" 	id="4" name="Zahlmethode" 	value="4">  	<label for="4"> 4</label>
	<br> <br>
	St&uuml;ckzahl: <br><input style="width:160;height:32px" type="text" name="menge[]" value="max: 64" /><br></br>
	<input style="width:160;height:32px" type="submit" value="Kaufen" name="kaufen" />  
</form>
Summe: <?php echo " $preis". " &euro;"; ?><br>
<?php echo " $MwSt"." % MwSt"; ?>: <?php echo " $gMwSt". " &euro;"; ?>


</body> 
</html>

</br></br>
<table>
	<tr>
		<form action="index.php">
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
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>