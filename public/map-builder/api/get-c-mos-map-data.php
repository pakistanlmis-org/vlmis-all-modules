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
include("../includes/classes/config.php");
$query = "SELECT 
B.pk_id district_id,
B.location_name District,
A.pop_male,
A.pop_female,
A.Area,
A.DHQ,
A.THQ,
A.RHC,
A.BHU,
A.GD,
A.EPI_Center,
A.LHW_House,
A.`RHS-A`,
A.MSU,
A.FWC,
A.MCH_Centers,
A.CMWs,
A.color,
A.c_district_id
from
(SELECT
map.district_id,
map.District,
map.pop_male,
map.pop_female,
map.Area,
map.DHQ,
map.THQ,
map.RHC,
map.BHU,
map.GD,
map.EPI_Center,
map.LHW_House,
map.`RHS-A`,
map.MSU,
map.FWC,
map.MCH_Centers,
map.CMWs,
map.color,
map.c_district_id
FROM
map ) A
RIGHT JOIN
(SELECT
locations.pk_id,
locations.location_name,
locations.geo_level_id,
locations.province_id
FROM
locations
where geo_level_id = 4) B ON A.district_id = B.pk_id";
//query result
$rsprov = mysqli_query($conn, $query) or die();
//fetch data from rsprov
while ($row = mysqli_fetch_array($rsprov)) {
    $temp['mapping_id'] = $row['district_id'];
    $temp['district_id'] = $row['district_id'];
    $c_district_id = $row['c_district_id'];

 $temp['c_district_id'] = $row['c_district_id'];

    

  
    
    $temp['district_name'] = $row['District'];
    if ($row['pop_male'] != "") {
        $temp['mos'] = 4;
    } else {
        $temp['mos'] = 0;
    }

    $temp['pop_male'] = $row['pop_male'];
    $temp['pop_female'] = $row['pop_female'];
    $temp['area'] = $row['Area'];

    $temp['dhq'] = $row['DHQ'];
    $temp['thq'] = $row['THQ'];
    $temp['rhc'] = $row['RHC'];
    $temp['bhu'] = $row['BHU'];
    $temp['gd'] = $row['GD'];
    $temp['epi_center'] = $row['EPI_Center'];
    $temp['lhw_house'] = $row['LHW_House'];
    $temp['rhs_a'] = $row['RHS-A'];
    $temp['msu'] = $row['MSU'];

    $temp['fwc'] = $row['FWC'];
    $temp['mch_centers'] = $row['MCH_Centers'];
    $temp['cmws'] = $row['CMWs'];
    $temp['color'] = $row['color'];


    $second_arr[] = $temp;
}


$j1 = json_encode($second_arr);
echo $j1;
exit;
