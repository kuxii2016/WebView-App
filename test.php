<?php
require_once('data/rcon.php');
require 'data/uuid.php';
$username = Kuxii;


$json = file_get_contents('https://api.mojang.com/users/profiles/minecraft/'.$username);
$data = json_decode($json);
$uuid = $data->{'id'};

echo "$uuid";

?>