<?php
//error_reporting(0);
session_start();
date_default_timezone_set('Asia/Karachi');

//Local
if($_SERVER['SERVER_ADDR'] == '::1' || $_SERVER['SERVER_ADDR'] == 'localhost'  || $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
	$db_host = 'localhost';
	$db_user = 'root';
	$db_password = '';
	$db_name = 'clmis';
} else {
	$db_host = 'localhost';
	$db_user = 'testr2clmis';
	$db_password = 'x9zm0X3p4RA9';
	$db_name = 'betaclmis';
}

$e2eHost = "10.10.10.4";
$e2eUser = "e2euserdb";
$e2ePass = "Jb#5A3EA19Ld";
$e2eDB = "e2edblmis";

define("DB_HOST",$db_host);
define("DB_USER",$db_user);
define("DB_PASS",$db_password);
define("DB_NAME",$db_name);

//hf array
$hfArr = array(5, 2, 3, 9, 6, 7, 8, 12, 10, 11);

function Login() {
    
	if(!isset($_SESSION['user_id'])) {
		$location = SITE_URL.'index.php';
		?>
		<script type="text/javascript">
			window.location = "<?php echo $location;?>";
		</script>
		<?php
	}
}

if($_SERVER['SERVER_ADDR'] == '::1' || $_SERVER['SERVER_ADDR'] == 'localhost'  || $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
	define('SITE_URL', 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/clmis/');
	define('SITE_PATH', $_SERVER['DOCUMENT_ROOT'].'/clmis/');
} else {
	define('SITE_URL', 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/clmisapp/');
	define('SITE_PATH', $_SERVER['DOCUMENT_ROOT'].'/clmisapp/');
}
define('PUBLIC_URL', SITE_URL.'public/');
define('PUBLIC_PATH', SITE_PATH.'public/');
define('APP_URL', SITE_URL.'application/');
define('APP_PATH', SITE_PATH.'application/');