<?php
/**
 * get-district-chart
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
include("../includes/classes/config.php");
//get district id
$district_id = $_REQUEST["district_id"];

//select query
//gets
//label
//value
$url = "http://c.lmis.gov.pk/application/web_services/reporting_rate_service.php?month=2018-11-01&district=$district_id";

$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL, $url);
// Execute
$result = curl_exec($ch);
// Closing
curl_close($ch);


$decoded = json_decode($result, true);
$temp['d_pwd'] = $decoded['reporting_rate_district']['PWD'];
$temp['d_lhw'] = $decoded['reporting_rate_district']['DOH (LHW)'];
$temp['d_static_hf'] = $decoded['reporting_rate_district']['DOH (Static HF)'];
$temp['d_mnch'] = $decoded['reporting_rate_district']['DOH (MNCH)'];

$temp['hf_pwd'] = $decoded['reporting_rate_sdp']['PWD'];
$temp['hf_lhw'] = $decoded['reporting_rate_sdp']['DOH (LHW)'];
$temp['hf_static_hf'] = $decoded['reporting_rate_sdp']['DOH (Static HF)'];
$temp['hf_mnch'] = $decoded['reporting_rate_sdp']['DOH (MNCH)'];

$temp['prov_id'] = $decoded['province_id'];
$temp['dist_id'] = $decoded['district_id'];
//query result
$second_arr[] = $temp;
echo json_encode($second_arr);
?>
