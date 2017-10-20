<?php
session_start();
require 'data/Pconfig.php';
require 'data/MySqlconfig.php';
require 'data/Multiplikator.php';
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
	<title>NG :: Regelwerk</title> 
</head> 
<body>
<h1>Wie wird es Gemacht?</h1> 
</br></br>
Hallo <?php echo $username ;?> .!
</br></br>
Diese Seite soll dir den Einstieg in das System erleichtern.
</br></br>
&#10112; Daten Speicherrung & Sicherheit?</br>
&#10113; Was ist alles Erlaubt und was nicht?</br>
&#10114; Sonstige Fragen?</br>
&#10115; Ansprechpartner?</br>
</br></br>

&#10122; </br> Du brauchst keine Angst um Sämliche Daten zu haben, Minecraft Account, Passwort, Nutzung,</br>
Von deinen Account (Minecraft) ist dieses Panel Total unabhängig das Einzigste was Genutzt wird ist dein Ingame Name,
und deine UUID (die Daten bekommt man bei https://minecraft.net/de-de/ als normal Sterblicher auch ) die als "IBAN" für Überweißungen dient.!</br> 
Passwörter werden bei uns sicher Verschlüsselt in einer Daten Bank auf dem Server Gespeichert.</br></br>
&#10123; </br> Nicht gestattet ist es, Anderen Spielern ihr Material zu Plündern.</br>
Die Öffentliche Stromversorgung einfach anzuzapfen ( Dafür ist Kuxii Zuständig ) Wer das Machen Sollte und erwischt wird bekommt die Gelbe Karte.</br>
In der Hauptwelt keine Steinbrüche anzusetzten.! Dafür haben wir extra eine Mining Welt.!!!</br>
Erd Häuser Bauen, Der Baustiel sollte schon in die Gegend passen wo du dich nieder Lässt, Spieler können Selbstständig neue Straßen Errichten solange diese so gebaut werden wie die Anderen.!</br>
Und tut mir und den Server einen Gefallen, Leider Fressen die Kinetik Windräder sehr viel Server Leistung, Schaut bitte das Ihr einen Strom Anschluss von Kuxii Geben lässt oder Solar Panel nutzt, Strom ist nicht Teuer..!!</br>
Spielen mit 2 Account ist unzulässig und wird bei Ertappen hart bestraft, Afk-lern sagen wir jetzt Schon auto Kick nach 45min.</br></br>
&#10124; </br>
Gebühren, Es ist alles Kostenlos bei uns, Du musst für nichts Zahlen jedoch sind Server Spenden gerne gesehen, Server Kostet nun mal auch geld und viel Pflege und Wartung.!</br>
Teamspeak3, Nutzung ist ebenso Kostenlos. Jedoch solltest du schon ein Headset haben da auf dem Chat nicht wirklich Reagiert wird. Die benötigten Rechte bekommt du von Chillerking oder Kuxii.</br>
Werden noch mehr Funktionen in dem Panel kommen? Ja.! Es ist noch eine Ganze Menge geplant verschiedene Dinge auch schon in der Test Phase, Jedoch kann es etwas Dauern da das Ganze eine Person Programiert.!</br></br>
&#10125; </br>
Solltest du Jetzt noch Fragen haben wende dich im Teamspeak ( Server.Nuclear-gaming.de ) an Chillerking (Support) oder an Kuxii (Admin).</br>
Sollte keiner von diesen Personen Anwesend sein, Kannst du in der APP unter ( MineAPP ) Kuxii Anschreiben ( Funktion Kommt erst noch, Stand: 10.10.2017 )</br>
Oder eben auf den Oldschool Weg via Email an: support@nuclear-gaming.de </br></br>
</br></br>
		<form action="index.php">
			<td>
				<input style="width:160;height:32px" type="submit" value="Start Seite"></td>
			</form>

<?php
echo "<center>Stand vom: 10.10.17 by Kuxii .</center></br></br>";
?>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
</body> 
</html>

