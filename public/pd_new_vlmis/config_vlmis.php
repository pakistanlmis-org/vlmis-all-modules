<?php
$hostname = "10.10.10.4";
$username = "vlmisr2user";
$password = "{5bhXduIBQ*&";
$db = 'vlmisr2';
$conn = mysqli_connect($hostname,$username,$password,$db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>