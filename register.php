<?php 
require_once('config/rcon.php');
require 'config/config.php';
$pdo = new PDO($mysql, $dbuser, $pass);
session_start();
  use Thedudeguy\Rcon;
 $rcon = new Rcon($host, $port, $password, $timeout);
?>
<!DOCTYPE html> 
<html> 
	<head>
  		<meta charset="utf-8"> 
		<meta name="description" content="Economy Expansion">
		<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Economy Expansion, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
		<meta name="author" content="Michael Kux">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>EE :: Register</title> 
	</head> 
<body style='background-color:#151515'><font color='#01DF01'>
	<center>
		<img src="images/register.png"><br><br>
<?php
$showFormular = true;
 
if(isset($_GET['register'])) {
 $error = false;
 $email = $_POST['email'];
 $username = $_POST['username'];
 $passwort = $_POST['passwort'];
 $passwort2 = $_POST['passwort2'];
  
 if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
 echo '<title>EE :: Wrong Data.!</title>Bitte eine gültige E-Mail-Adresse eingeben<br>';
 $error = true;
 } 
 if(strlen($passwort) == 0) {
 echo '<title>EE :: No Passwort</title>Bitte ein Passwort angeben<br>';
 $error = true;
 }
 if($passwort != $passwort2) {
 echo '<title>EE :: Passwort Error</title>Die Passwörter müssen übereinstimmen<br>';
 $error = true;
 }
 
 //Register Fehler
 if(!$error) { 
 $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
 $result = $statement->execute(array('email' => $email));
 $user = $statement->fetch();
 
 if($user !== false) {
 echo '<title>EE :: Email already Exist</title>Diese E-Mail-Adresse ist bereits vergeben<br>';
 $error = true;
 } 
 }
 
 //Register Ausführen
 if(!$error) { 
 //Minecraft UUID Holen
 $json = file_get_contents('https://api.mojang.com/users/profiles/minecraft/'.$username);
 $data = json_decode($json);
 $rdata = $data->{'id'};
 $uid1 .= substr($rdata, 0, 8)."-";
 $uid2 .= substr($rdata, 8, 4)."-";
 $uid3 .= substr($rdata, 12, 4)."-";
 $uid4 .= substr($rdata, 16, 4)."-";
 $uid5 .= substr($rdata, 20);
 $uuid = "$uid1$uid2$uid3$uid4$uid5";

 //Passwort Verschlüsselung
 $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
 $statement = $pdo->prepare("INSERT INTO users (email, passwort, uuid, name) VALUES (:email, :passwort, :uuid, :name)");
 $result = $statement->execute(array('email' => $email, 'passwort' => $passwort_hash, 'uuid' => $uuid, 'name' => $username));
 //whitelist hinzufügen

 if ($rcon->connect())
 {
	 $rcon->sendCommand("whitelist add $username");
	 $rcon->sendCommand("say Spieler $username hat sich gerade Regestriert :)");
	 //schreibt den Sünder in den Log.
	$myfile = fopen("cache/log/system/system-log.html", "a");
	fwrite ($myfile, "Spieler: $username hat sich so eben Regestriert</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("cache/log/system/system-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
 }
 if($result) 
 { 
 echo '<title>EE :: Register Complete</title><br>Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
 $showFormular = false;
 mkdir("cache/$username", 0777);
 mkdir("cache/$username/bank", 0777);
 mkdir("cache/$username/plot", 0777);
 mkdir("cache/$username/sozial", 0777);
 mkdir("cache/$username/job", 0777);
 mkdir("cache/$username/energy", 0777);
 } 
 else 
 {
 echo '<title>EE :: Error by save Data</title>Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
 }
 } 
}
 
if($showFormular) {
?>
 
		<form action="?register=1" method="post">
			E-Mail:
		<br>
			<input type="email" size="40" maxlength="250" name="email">
				<br>
					<br>
						Minecraft Player Name:
					<br>
				<input type="text" size="40" maxlength="250" name="username">
			<br>
		Your Passwort:
	<br>
		<input type="password" size="40"  maxlength="250" name="passwort">
			<br>
				Repeat your wiederholen:
			<br>
				<input type="password" size="40" maxlength="250" name="passwort2">
			<br>
		<br>
			<input type="submit" value="   Register   ">
		</form> 
<?php
} //Ende von if($showFormular)
?>
	</body>
</html>