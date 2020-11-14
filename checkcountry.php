<?php
// The databases are in others php files
include 'ipdb.php';  // check by ip list
include 'hostdb.php';   // check by host name
include 'countrydb.php';   // check by country
$allow=0;
$ip=$_SERVER["REMOTE_ADDR"];
$country = geoip_country_code_by_name($ip);
$hostname = gethostbyaddr($ip);
$now=date('d-m-Y H:i:s'); 

foreach ($countrycode as $value) {
    if ($country==$value) {$allow=1;}
}

foreach ($allowedbotip as $value) {
    if ($ip==$value) {$allow=1;}
}

foreach ($notallowedbotip as $value) {
    if ($ip==$value) {$allow=0;}
}

foreach ($allowedbotrange as $value) {
    if (substr($ip, 0, strlen($value))==$value) {$allow=1;}
}

foreach ($allowhost as $value) {
if (strpos($hostname, $value) !== false) {
    $allow=1;
}
}

// Get a db connection.
$serveur = "";  // enter your mysql or mysqli server address
$base = "";     // enter the name of your DB
$user = "";     // enter the username of your db
$pass = "";     // enter the password of your db

$mysqli = new mysqli($serveur, $user, $pass, $base);

if ($mysqli->connect_error) {
    die('Erreur de connexion ('.$mysqli->connect_errno.')'. $mysqli->connect_error);
}

$requete = "INSERT INTO Log_ip (Date,ip,country, host,auth) VALUES ('$now','$ip', '$country','$hostname', '$allow')"; // You can create a table like that in your database or modify the sql request

$resultat = $mysqli->query($requete) or die ('Erreur '.$requete.' '.$mysqli->error());

$mysqli->close();

if ($allow==0) {
    header('Location: notallowed.html'); // You must create an error page to redirect the user if not allowed
}

?>
