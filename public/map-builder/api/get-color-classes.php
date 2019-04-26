<?php

/*
 * get-color-classes
 * @package im
 * 
 * @author     Ajmal Hussain
 * @email <ahussain@ghsc-psm.org>
 * 
 * @version    2.2
 * 
 */
//include Configuration
include("../includes/classes/Configuration.inc.php");
//include db
include(APP_PATH . "includes/classes/db.php");
//get id

$js = '[{"geo_indicator_id":"2","start_value":"0","end_value":"0","interval":"0 - 0.99","description":"No data","color_code":"#D8D8D8"},{"geo_indicator_id":"2","start_value":"0","end_value":"1","interval":"1","description":"1","color_code":"#FF0000"},{"geo_indicator_id":"2","start_value":"2","end_value":"2","interval":"2","description":"2","color_code":"#FFAA00"},{"geo_indicator_id":"2","start_value":"3","end_value":"3","interval":"2 - 3","description":"3","color_code":"#008000"},{"geo_indicator_id":"2","start_value":"4","end_value":"4","interval":"> 3","description":"> 4","color_code":"#6BCEFF"}]';
header('Content-Type: application/json');
echo $js;
exit;

