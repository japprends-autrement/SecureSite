<?php
$allow=0;
$ip=$_SERVER["REMOTE_ADDR"];
$country = geoip_country_code_by_name($ip);
$countrycode = array('BE','FR','BJ','BF','CD','CI','GA','GG','GF','GN','ML','NE','MC','SN','TG','LI','LU','BI','CM','CF','HT','KM','DJ', 'GQ','MG','RW','SC','TD','VU','MR','JE','NL','CA','CH','MA'); // you can choose the countries you want to allow

$notallowedbotip=array(   // You can set some ip to be not allowed
'62.210.189.2',
'185.191.171.12'
);

$allowedbotrange=array(       // you can set some range ip to allow
'157.55.39',  //microsoft
'207.46.13',  //microsoft
'66.220.149', //facebook
'69.171.251', //facebook
'66.249.83',  //google
'66.249.65',  //google
'66.249.64',   //google
'66.249.72',  //google
'66.249.93',  //google
'31.13.103',   //facebook
'173.252.87',   //facebook
'31.13.127'     //facebook

);

$allowedbotip=array(          // you can set some ip to allow
'31.13.127.113', //facebook
'54.236.1.13',   //pinterest
'3.21.76.54',      //amazon
'34.239.175.178',  //amazon
'52.165.158.48', //microsoft
'52.183.60.91',  //microsoft
'173.252.79.3', //facebook
'54.236.1.11',  //pinterest
'13.66.139.2',   //microsoft
'40.94.105.26',   //microsoft
'104.47.9.254',
'173.252.95.22',  //facebook
'173.252.95.24',   //facebook
'3.229.134.194' ,  //amazon
'173.252.83.119', //facebook
'69.171.249.26',  //facebook
'69.171.249.2',  //facebook
'173.252.95.16',  //facebook
'18.224.31.77', //amazon
'3.236.246.121', //amazon
'173.252.107.12',  //facebook
'3.249.231.50', //amazon
'13.78.225.140', //microsoft
'18.222.46.113', //amazon
'34.204.192.214'  //amazon
);

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

// Get a db connection.
$serveur = "";  // enter your mysql or mysqli server address
$base = "";     // enter the name of your DB
$user = "";     // enter the username of your db
$pass = "";     // enter the password of your db

$mysqli = new mysqli($serveur, $user, $pass, $base);

if ($mysqli->connect_error) {
    die('Erreur de connexion ('.$mysqli->connect_errno.')'. $mysqli->connect_error);
}

$requete = "INSERT INTO Log_ip (ip,country, auth) VALUES ('$ip', '$country', '$allow')"; // You can create a table like that in your database or modify the sql request

$resultat = $mysqli->query($requete) or die ('Erreur '.$requete.' '.$mysqli->error());

$mysqli->close();

if ($allow==0) {
    header('Location: notallowed.html'); // You must create an error page to redirect the user if not allowed
}

?>
