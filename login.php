<?php 
require '/var/www/html/data/MySqlconfig.php';
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

 $str = md5(uniqid('euer_geheimer_string', true));
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
 setcookie("identifier",$identifier,time()+(3600*24*365)); //1 Jahr G�ltigkeit
 setcookie("securitytoken",$securitytoken,time()+(3600*24*365)); //1 Jahr G�ltigkeit
 }
 die('<center></br></br><body style="background-color:#151515"><font color="#01DF01">Login erfolgreich. Weiter zu <a href="index.php">internen Bereich</a>');
	header('Location: index.php');
  	//schreibt den S�nder in den Log.
	$myfile = fopen("/var/www/html/daten/log/log.html", "a");
	fwrite ($myfile, "Spieler: $username Loggte sich ein(WEB).</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("/var/www/html/daten/log/date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
 } else {
 $errorMessage = "E-Mail oder Passwort war ung�ltig<br>";
 }
 
}
?>
<!DOCTYPE html> <center><body style='background-color:#151515'><font color='#01DF01'>
<html> 
<head>
  <title>Login</title> 
</head> 
<body>
 
<?php 
if(isset($errorMessage)) {
 echo $errorMessage;
}
?>
 
<form action="?login=1" method="post">
E-Mail:<br>
<input type="email" size="40" maxlength="250" name="email"><br><br>
 
Dein Passwort:<br>
<input type="password" size="40"  maxlength="250" name="passwort"><br>
 
<label><input type="checkbox" name="angemeldet_bleiben" value="1"> Angemeldet bleiben</label><br>
 
<input type="submit" value="Abschicken">
</form>
<br><br>
<a href="register.php"> -->Register<-- </a><br>
<a href="passwortvergessen.php"> -->Passwortvergessen<-- </a><br><br><br><br><br><br><br><br><br><br>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Login -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-6834229371954973"
     data-ad-slot="6288282503"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</body>
</html>