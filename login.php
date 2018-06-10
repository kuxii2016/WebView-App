<?php 
require 'config/config.php';
require 'conversation/1.php';
$pdo = new PDO($mysql, $dbuser, $pass);
session_start();
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
if(isset($_GET['login'])) {
 $email = $_POST['email'];
 $passwort = $_POST['passwort'];
 $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
 $result = $statement->execute(array('email' => $email));
 $user = $statement->fetch();
 if ($user !== false && password_verify($passwort, $user['passwort'])) {
 $_SESSION['userid'] = $user['id'];
 if(isset($_POST['angemeldet_bleiben'])) {
 $identifier = random_string();
 $securitytoken = random_string();
 $insert = $pdo->prepare("INSERT INTO securitytokens (user_id, identifier, securitytoken) VALUES (:user_id, :identifier, :securitytoken)");
 $insert->execute(array('user_id' => $user['id'], 'identifier' => $identifier, 'securitytoken' => sha1($securitytoken)));
 setcookie("identifier",$identifier,time()+(3600*24*365)); //1 Jahr Gültigkeit
 setcookie("securitytoken",$securitytoken,time()+(3600*24*365)); //1 Jahr Gültigkeit
 }
 die('<title>EE :: Login OK!</title> <center></br></br><body style="background-color:#151515"><font color="#01DF01">Login erfolgreich. Weiter zum <a href="index.php">internen Bereich</a>');
	header('Location: index.php');
	$myfile = fopen("cache/log/system/system-log.html", "a");
	fwrite ($myfile, "Spieler: $email Loggte sich ein(WEB).</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m.y-H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("cache/log/system/system-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
 } else {
 $errorMessage = "E-Mail oder Passwort war ungültig<br>";
 }
 
}
?>
<!DOCTYPE html> 
<center>
	<html> 
		<head>
			<meta charset="utf-8"> 
			<meta name="description" content="Economy Expansion">
			<meta name="keywords" content="Gaming, Minecraft, Mods, Multiplayer, Economy Expansion, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
			<meta name="author" content="Michael Kux">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>EE :: Login</title> 
		</head> 
	<body style='background-color:#151515'><font color='#01DF01'>
<br>
<?php 
if(isset($errorMessage)) {
 echo $errorMessage;
}
?>
	<br>
		<br>
			<img src="images/login.png">
		<br>
	<br>
		<form action="?login=1" method="post">
			E-Mail:
		<br>
			<input type="email" size="40" maxlength="250" name="email">
		<br>
			<br>
				Passwort:
			<br>
		<input type="password" size="40"  maxlength="250" name="passwort">
	<br>
<br>
	<label>
		<input type="checkbox" name="angemeldet_bleiben" value="1">Auto Login</label>
	<br>
<br>
	<br>
		<input type="submit" value="  Login  ">
	</form>
		<br>
			<br>
				<a href="register.php"> --> Register <-- </a>
			<br>
				<a href="passwortvergessen.php"> --> Passwortvergessen <-- </a>
			<br>
		<br>
	</body>
</html>