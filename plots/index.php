<?php
function debug($input)
{
    echo "<pre>";
    var_dump($input);
    echo "</pre>";
}
function search($array, $key, $value)
{
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }
        foreach ($array as $subarray) {
            $results = array_merge($results, search($subarray, $key, $value));
        }
    }
    return $results;
}
session_start();
require_once('/var/www/html/data/rcon.php');
require '/var/www/html/data/Pconfig.php';
require '/var/www/html/data/MySqlconfig.php';
require '/var/www/html/data/Multiplikator.php';
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
function random_string()
{
    if (function_exists('random_bytes')) {
        $bytes = random_bytes(16);
        $str = bin2hex($bytes);
    } else if (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(16);
        $str = bin2hex($bytes);
    } else if (function_exists('mcrypt_create_iv')) {
        $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
        $str = bin2hex($bytes);
    } else {
        $str = md5(uniqid('zuj54w9f65w9<-----EDIT xD', true));
    }
    return $str;
}
if (!isset($_SESSION['userid']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
    $identifier = $_COOKIE['identifier'];
    $securitytoken = $_COOKIE['securitytoken'];
    $statement = $pdo->prepare("SELECT * FROM securitytokens WHERE identifier = ?");
    $result = $statement->execute(array($identifier));
    $securitytoken_row = $statement->fetch();
    if (sha1($securitytoken) !== $securitytoken_row['securitytoken']) {
        die('Upps da lief was schief <a href="login.php">Bitte neu Einloggen</a>');
    } else {
        $neuer_securitytoken = random_string();
        $insert = $pdo->prepare("UPDATE securitytokens SET securitytoken = :securitytoken WHERE identifier = :identifier");
        $insert->execute(array('securitytoken' => sha1($neuer_securitytoken), 'identifier' => $identifier));
        setcookie("identifier", $identifier, time() + (3600 * 24 * 365));
        setcookie("securitytoken", $neuer_securitytoken, time() + (3600 * 24 * 365));
        $_SESSION['userid'] = $securitytoken_row['user_id'];
    }
}
if (!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="login.php">Einloggen</a>');
}
?>
<?php
$directoryPath = "/var/www/html/daten/plots/$username"; 
if (!file_exists($directoryPath)) {
    mkdir($directoryPath, 0777);
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Nuclear Gaming Panel">
    <meta name="keywords"
          content="Gaming, Minecraft, Mods, Multiplayer, Nuclear Gaming, Kuxii, Ic2, Buildcraft, Railcraft, Computercraft, Citybuild, Economy System, German, Englisch, no Lagg, Infinity Silence Gaming, Tekkit">
    <meta name="author" content="Michael Kux">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NG :: Grundstücke</title>
</head>
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
?><center>
<h1>Grundstücke</h1>
<?php // Array Such Funktion
class arraylib
{
    static public function array_search($array, $search)
    {
        if (!is_array($array)) {
            $search = addcslashes($search, "\\#\"{([])}$.^");
            $pattern = "#" . $search . "#si";
            if (preg_match($pattern, $array)) {
                return $array;
            } else {
                return false;
            }
        } else {
            $newArray = array();
            foreach ($array AS $key => $value) {
                if (self::array_search($value, $search) != false) {
                    $newArray[$key] = self::array_search($value, $search);
                }
            }
            return $newArray;
        }
    }
}
$datawallet = "$ginfo/permissions.json";
$jsondata = file_get_contents($datawallet);
$data = json_decode($jsondata, true);
$id_count = count(search($data, "fe.internal.plot.owner", "$uuid"));
?>
<?php
	$TimeStand = 3600;
	$myfile = fopen("/var/www/html/daten/plots/admin/$username.txt", "w");
	fwrite ($myfile, "Im Besitz: ".$id_count. " / 25");
	fclose($myfile);
	//Aktuelle Spielzeit Holen
	$datawallet = "$pinfo/$uuid.json";
    $jsondata = file_get_contents($datawallet);
	$data = json_decode($jsondata,true);
	$namen = $data;
	$tdata = floor($namen['timePlayed']/1000);
	$secs = $tdata;	
	//KostenPunkt pro Min
	$NewData = $id_count / 100 * $GSt;
	$NewData2 = round($NewData * $TimeStand /60);	
	//Spielzeit seid der letzten Auszahlung
	$myfile = fopen("/var/www/html/daten/plots/$username/$username-$uuid.Time.txt", "r");
	$alttime = fgets ($myfile);
	$newtime = $tdata-$alttime;
	$newtimeM = round($newtime/60);
	//berechnung des Satzes
	$betrag1 = round($newtime*$sozigeld, 0);
	$sbetrag = round($betrag1/100*$SoSt);
	$betrag = $betrag1 - $sbetrag;

	//Grundstückspreis
	$GrundstWert = $id_count / 100 * $GSt;
	$GrundstWert2 = round($GrundstWert * $newtime / 60);
	$vZ = $GrundstWert2 * $vZsatz;
	$vA = $vZ - $GrundstWert2;
	$GnPreis = $id_count * $gssteuer ;
	$gspreis = $newtime * $GnPreis ;
	$gssteuer = $gspreis; // Zahlbetrag!!!
	if ($GrundstWert2 <= 1490){
		$GrundstWert3 = $GrundstWert2;
	}
	else if ($GrundstWert2 >= 1500){
		$GrundstWert3 = $vZ;
	}
	//GS Admin Debug
	//schreibt den Sünder in den Log.
	$myfile = fopen("/var/www/html/daten/log/spieler/$username-log.html", "a");
	fwrite ($myfile, "Spieler: $username Hat Plot Kosten von: $NewData2 € für $id_count / 25 G</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("/var/www/html/daten/log/spieler/$username-date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);	
	if ($GrundstWert2 <= 1490){
	}
	else if ($GrundstWert2 >= 1500){
		echo"Zu Bezahlen :<font color='red'>&nbsp;&nbsp;".$GrundstWert2." €</br>";
		echo "<font color='red'>Strafe :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+ ".$vA." €</font></br>";
		echo"Zu Bezahlen :<font color='red'> ".$GrundstWert3." €</br>";
		echo"<h2><font color='red'>Wenn du schon Länger Spielst,</br> Zahle Öfters oder deine Plots werden Entfert.!</h2>";
	//schreibt den Sünder in den Log.
	$myfile = fopen("/var/www/html/daten/log/log.html", "a");
	fwrite ($myfile, "Spieler: $username überschreitet die Plot Summe mit: $GrundstWert3 € inkl. $vA € Strafe&nbsp;</br>");
	fclose($myfile);
	$timestamp = time();
	$datum = date("d.m/H:i", $timestamp);
	//schreibt die Zeit ins Doc.
	$myfile = fopen("/var/www/html/daten/log/date.html", "a");
	fwrite ($myfile, $datum. "&nbsp;</br>");
	fclose($myfile);
	}
	//Spieler Auszahlung
	$myfile = fopen("/var/www/html/daten/plots/$username/$username-$uuid.Betrag.txt", "w");
	$txt = "$tdata";
	fwrite ($myfile, $GrundstWert3);
	fclose($myfile);

?>
<center>
<table style="width:45%" border=0 bgcolor=#2E2E2E  cellspacing=10 cellpadding=>
  <tr>
    <th><font color="MediumSpringGreen "><div style="text-align:right">Grundstücke</th>
    <th><font color="MediumSpringGreen "><div style="text-align:left">für <?php echo $username?> </th> 
    <th><font color="white"><div style="text-align:right">Stand vom:</th>
	<th><font color="white"><div style="text-align:left"><?echo $datum;?></th>
  </tr>
  <tr>
    <th><font color="white">Std</th>
    <th><font color="white">Plots</th> 
    <th><font color="white">Steuern</th>
	<th><font color="white"></th>
  </tr>
  
  <tr>
    <td style="width:3%"><font color="white"><center><?php echo round($newtime / 60 / 60, 2)?></td>
	<td style="width:50%"><font color="white"><center><?php echo $id_count?></td>
    <td style="width:60%"><font color="white"><center><?php echo $GSt?> %</td> 
    <td style="width:30%"><font color="white"><center><?php echo $GrundstWert2?> €</td> 
  </tr>
  </tr>
    <td><font color="white"><center></td>
	<td><font color="orange"><center></td> 
    <td><font color="orange"><center></td>
    <td><font color="red"><center></td> 
  </tr>
  <tr> 
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right"></td>
    <td><font color="red"><center></td> 
  </tr>
  <tr>
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right">inkl. <?php echo $GSt?> % Steuern:</td>
    <td><font color="orange"><center><?php echo $GrundstWert2 / 100 * $GSt?> €</td> 
  </tr>
  <tr>
    <td><font color="white"><center></td>
	<td><font color="white"><center><?php echo "Im Besitz: ".$id_count. " / 25"?></td> 
    <td><font color="white"><center></td>
    <td><font color="white"><center>__________________</td> 
  </tr>
  <tr>
    <td><font color="white"><center></td>
	<td><font color="white"><center></td> 
    <td><font color="white"><center><div style="text-align:right">Zu Bezahlen:</td>
    <td><font color="green"><center> <?php echo $GrundstWert2?> €</td> 
  </tr>
</table>
</font>

<font color="#01DFD7">
    Grundstück Kaufen:</br>
    | [1] = //chunk | [2] = /plot claim | [3] = /yes  </br>Alles im Chat nach den Nummern eingeben und er Gehört dir.!
</font>

<table>
    <tr>
        <form action="../index.php">
            <td><input style="width:180;height:32px" type="submit" value="Hauptmenü"></td>
        </form></tr></table>
<?php
$nowTime = round($newtime / 60 / 60, 2);
?>
<tr><?php if ($nowTime > 1 && $GrundstWert2 > 9) {
	echo' <table> <tr>
		<form action="pay.php" method="POST">
        <td><input style="width:180;height:32px" type="submit" value="Begleichen" name="Begleichen" /></td>
        </form>   
</table></tr> ';
}
else {
}
?>
<?php if ($id_count < 1) {
	echo' <table><tr>  
		<form action="pay.php" method="POST">
        <td><input style="width:180;height:32px" type="submit" value="Jetzt" name="Jetzt" /></td>
        </form>
    </tr>
</table> ';
}
else {
}
?>
</body>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
</html>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>