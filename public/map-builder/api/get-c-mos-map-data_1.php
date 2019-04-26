<?php

/**
 * get-c-mos-map-data
 * @package maps/api
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
//get year
$dist = $_REQUEST["teh"];

$dist_arr = json_decode($dist);
$second_arr=$temp =array();
foreach($dist_arr as $k=>$v)
{
    $temp['mapping_id']=$k;
    $temp['district_id']=$k;
    $temp['district_name']= 'bagh';
    $temp['mos']=$v;
    
    $second_arr[]=$temp;
    
}

 $j1= json_encode($second_arr);
 header('Content-Type: application/json');
 echo $j1;
exit;
