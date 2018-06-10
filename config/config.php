<?php
// Mysql Panel Database
$mysql = "mysql:host=localhost;dbname=minecraftpanel";
$dbuser = "root";
$pass = "";

// Admin User view 
define ( 'MYSQL_HOST','localhost' );
define ( 'MYSQL_BENUTZER','root' );
define ( 'MYSQL_KENNWORT', '' );
define ( 'MYSQL_DATENBANK','minecraftpanel' );

//Server Rcon verbindung
$host = "localhost";												
$port = 25575;														
$password = "hqmpibkv";										
$timeout = 3;

//Login - Passwort Teil verschlüsselung
$mcrypt_salt = "!kQm*fF3pXe1Kbm%9";

//Server pfade
$whitelist = "C:\Minecraft/whitelist.json";						
$wallet = "C:\Minecraft/IWillPlay/FEData/json/PlayerWallet";		
$pinfo = "C:\Minecraft/IWillPlay/FEData/json/PlayerInfo";			
$ginfo = "C:\Minecraft/IWillPlay/FEData/";							
$pstats = "C:\Minecraft/IWillPlay/stats";							
$pc = "C:\Minecraft/IWillPlay/computer";

//Sonstiges
$MapCreateDate = "2017-09-07";
$serverdomain = "localhost";
$minPlayTimeForTheJob = "864000"; //10 Tage min. Spielzeit bis mann Job gehalt bekommt
$energyManager = "Kuxii";
$AkWName = "Buchenwald";

//lohnstufen %e zählt in Tage
$stufe1 = "15";
$stufe2 = "30";
$stufe3 = "60";
$stufe4 = "90";
$stufe5 = "120";
$stufe6 = "150";
?>