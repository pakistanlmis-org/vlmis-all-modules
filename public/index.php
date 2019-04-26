<?php
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../r2.9/application'));

defined('LIBRARY_PATH') || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../r2.9/library'));

defined('UPLOAD_PATH') || define('UPLOAD_PATH', realpath(dirname(__FILE__) . '/images/upload'));

defined('PUBLIC_DIR') || define('PUBLIC_DIR', realpath(dirname(__FILE__)));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production1'));

// Define Closing Balance Unit
defined('PLMIS_CBL_UNIT') || define('PLMIS_CBL_UNIT', 1);

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(LIBRARY_PATH),
    realpath(LIBRARY_PATH . '/Doctrine'),
    realpath(APPLICATION_PATH . '/models/doctrine/models'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';
require_once LIBRARY_PATH . '/App/Util.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
        ->run();
