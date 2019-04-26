<?php
//$hostname = "localhost";
//$username = "root";
//$password = "";
//$db = 'vlmis';
$hostname = '10.10.10.4';
$username = 'vlmisr2user';
$password = '{5bhXduIBQ*&';
$db = 'vlmisr2';

$connv = mysqli_connect($hostname, $username, $password, $db);

$hostnamec = "localhost";
$usernamec = "root";
$passwordc = "";
$dbc = 'clmis';
//$hostnamec = '10.10.10.4';
//$usernamec = 'clmisuser';
//$passwordc = '21#GizFfc.Jy';
//$dbc = 'clmis';

$connc = mysqli_connect($hostnamec, $usernamec, $passwordc, $dbc);
