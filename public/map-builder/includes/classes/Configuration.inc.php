<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
//error_reporting(0);
session_start();

//Local
if ($_SERVER['SERVER_ADDR'] == '::1' || $_SERVER['SERVER_ADDR'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'vlmis';
} else {
    $db_host = '10.10.10.4';
    $db_user = 'vlmisr2user';
    $db_password = '{5bhXduIBQ*&';
    $db_name = 'vlmisr2';
}

$e2eHost = "10.10.10.4";
$e2eUser = "e2euserdb";
$e2ePass = "Jb#5A3EA19Ld";
$e2eDB = "e2edblmis";

define("DB_HOST", $db_host);
define("DB_USER", $db_user);
define("DB_PASS", $db_password);
define("DB_NAME", $db_name);

//hf array
$hfArr = array(5, 2, 3, 9, 6, 7, 8, 12, 10, 11);



if ($_SERVER['SERVER_ADDR'] == '::1' || $_SERVER['SERVER_ADDR'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    define('SITE_URL', 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/');
    define('SITE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/map-builder');
} else {
    define('SITE_URL', 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/');
    define('SITE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/map-builder');
}
define('PUBLIC_URL', SITE_URL . '/');
define('PUBLIC_PATH', SITE_PATH . '/');
define('APP_URL', SITE_URL . '/');
define('APP_PATH', SITE_PATH . '/');
