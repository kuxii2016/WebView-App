<?php 
require_once('data/rcon.php');
require 'data/MySqlconfig.php';
require 'data/Pconfig.php';
$pdo = new PDO($mysql, $dbuser, $pass);
session_start();
  use Thedudeguy\Rcon;
 $rcon = new Rcon($host, $port, $password, $timeout);
?>
<!DOCTYPE html> 
<html> 
<head>
  <title>Registrierung</title> 
</head> 
<body>
 
<?php
$showFormular = true;
 
if(isset($_GET['register'])) {
 $error = false;
 $email = $_POST['email'];
 $username = $_POST['username'];
 $passwort = $_POST['passwort'];
 $passwort2 = $_POST['passwort2'];
  
 if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
 echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
 $error = true;
 } 
 if(strlen($passwort) == 0) {
 echo 'Bitte ein Passwort angeben<br>';
 $error = true;
 }
 if($passwort != $passwort2) {
 echo 'Die Passwörter müssen übereinstimmen<br>';
 $error = true;
 }
 
 //Register Fehler
 if(!$error) { 
 $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
 $result = $statement->execute(array('email' => $email));
 $user = $statement->fetch();
 
 if($user !== false) {
 echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
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
	$myfile = fopen("daten/log/log.html", "a");
	fwrite ($myfile, "Spieler: $username hat sich so eben Regestriert</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("daten/log/date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
 }
 if($result) 
 { 
 echo 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
 $showFormular = false;
 } 
 else 
 {
 echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
 }
 } 
}
 
if($showFormular) {
?>
 
<form action="?register=1" method="post">
E-Mail:<br>
<input type="email" size="40" maxlength="250" name="email"><br><br>

Minecraft Name:<br>
<input type="text" size="40" maxlength="250" name="username"><br>

Dein Passwort:<br>
<input type="password" size="40"  maxlength="250" name="passwort"><br>
 
Passwort wiederholen:<br>
<input type="password" size="40" maxlength="250" name="passwort2"><br><br>
 
<input type="submit" value="Abschicken">
</form>
 
<?php
} //Ende von if($showFormular)
?>
 
</body>
</html>