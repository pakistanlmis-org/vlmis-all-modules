<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Define path to application directory
defined('APPLICATION_PATH')
   || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
define('APPLICATION_ENV', 'unittest');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
   realpath(APPLICATION_PATH . '/../library'),
   get_include_path()
)));


/** Zend_Application */
require_once 'Zend/Application.php';
// Create application, bootstrap, and run
$application = new Zend_Application(
   APPLICATION_ENV,
   APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap();
clearstatcache();