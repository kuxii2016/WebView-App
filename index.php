<?php
session_start();
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
$rechte = $dbdata['box2'];
$job = $dbdata['job'];
$sprache = $dbdata['sprache'];
$currentSprache = $Laguane;
if($sprache == 1){
require 'conversation/1.php';
$Laguane = "DE ";
}
elseif($sprache == 2){
require 'conversation/2.php';
$Laguane = "EN ";
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
<center>
<?php
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
	$jobTitles = $vkLohnVZ;
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
  echo "<td width=28%><font face='Verdana'><font color='#FFFFFF'>",$username,"</td>";
  echo "<td width=30%><font face='Verdana'><font color='#FFFFFF'>".$AktuellerJob."".$jobTitles ."</td>";
  echo "<td width=60%><font face='Verdana'><font color='#FFFFFF'>","".$Guthaben."".$geld."".$GuthabenIcon,"</td>";
  echo "<td width=15%><font face='Verdana'><font color='#FFFFFF'>","".$Laguane."","</td>";
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
		<meta name="description" content="Economy Expansion">
		<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Economy Expansion, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
		<meta name="author" content="Michael Kux">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>EE :: Home</title> 
		<meta http-equiv=“cache-control“ content=“no-cache“>
		<meta http-equiv=“pragma“ content=“no-cache“>
		<meta http-equiv=“expires“ content=“0″>
	</head>
<body> 
	</br> 
		<table>
			<tr>
				<form action="bank/index.php">
			<td>
				<input style="width:167;height:32px" type="submit" value="<?php echo $button1; ?>">
			</td>
		</form>
			<form action="spieler.php">
				<td>
					<input style="width:167;height:32px" type="submit" value="<?php echo $button2; ?>">
				</td>
			</form>
		</tr>
	
	<tr>
		<form action="plots/index.php">
			<td>
				<input style="width:167;height:32px" type="submit" value="<?php echo $button3; ?>"></td>
		</form>
			<form action="jobcenter/index.php">
				<td>
					<input style="width:167;height:32px" type="submit" value="<?php echo $button4; ?>"></td>
			</form>
		</tr>
	
	<tr>
		<form action="energy/index.php">
			<td>
				<input style="width:167;height:32px" type="submit" value="<?php echo $button5; ?>"></td>
			</form>
				<form action="shop/index.php">
			<td>
				<input style="width:167;height:32px" type="submit" value="<?php echo $button6; ?>"></td>
		</form>
	</tr>
	
	<tr>
	<form action="help.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="<?php echo $button7; ?>"></td>
	</form>
	<form action="index.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="<?php echo $button8; ?>"></td>
	</form>
	</tr>

	<tr>
	<form action="settings.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="<?php echo $button9; ?>"></td>
	</form>
	<form action="job/index.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="<?php echo $button10; ?>"></td>
	</form>
	</tr>
	
	<tr>
	<form action="Casino.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="<?php echo $button11; ?>"></td>
	</form>
	<form action="logout.php">
		<td>
			<input style="width:167;height:32px" type="submit" value="<?php echo $button12; ?>"></td>
	</form>
	</tr>
	
<?php if ($dbdata['rechte'] == 1) {
	echo' 
	<tr>
	<form action="index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="">
	</td>
	</form>
	<form action="buergermeister/index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Bürgermeister Menü">
	</td>
	</form> ';
}
else if ($dbdata['rechte'] == 2) {
	echo' 
	<tr>
	<form action="admin/akw.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Versorger Menü">
	</td>
	</form>
	<form action="index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="">
	</td>
	</form> ';
}
else if ($dbdata['rechte'] == 3) {
	echo' 
	<tr>
	<form action="admin/index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value= "Admin Menü">
	</td>
	</form>
	<form action="buergermeister/index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="Bürgermeister Menü">
	</td>
	</form>
	<tr>
	<form action="index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="">
	</td>
	</form>
	<form action="index.php">
	<td>
	<input style="width:167;height:32px" type="submit" value="">
	</td>
	</form> ';
}
else {
}
?>
	</table>
<h6>
<?php
echo "<center>Panel version: 3.0 | &copy; by Kuxii .</center></br></br>";
?>
<?php
	$myfile = fopen("daten/log/anews.txt", "r");
	$Text = fgets($myfile);
?></br></br></br></br>
<?php 
if ( $dbdata['box2'] == 1) {
$userid = $_SESSION['userid'];
echo "<table border=0 bgcolor='green' >";
{	
  echo "<tr>";
  echo "<td width=20%><font face='Verdana'><font color='#FFFFFF'>News:</td>";
  echo "<td width=120%><font face='Verdana'><font color='#FFFFFF'><marquee>$Text</marquee></td>";
  echo "</tr>";
}echo "</table>";
}
else
{	
echo "";
echo "<h1></h1>";
}
?>
</body> 
</html>