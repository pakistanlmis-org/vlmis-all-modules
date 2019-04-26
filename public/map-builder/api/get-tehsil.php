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
$dist_sel = $_REQUEST["dist_sel"];

if ($dist_sel != 'all') {
    $where = "AND locations.province_id = $dist_sel";
} else {

    $where = "";
}


$queryprov = "SELECT
            locations.pk_id,
            locations.location_name,
            locations.geo_level_id,
            locations.parent_id,
            locations.location_type_id,
            locations.province_id,
            locations.district_id,
            locations.ccm_location_id,
            locations.sdms_name,
            locations.created_by,
            locations.created_date,
            locations.modified_by,
            locations.modified_date,
            locations.vpd_epid_district_code
FROM
locations
INNER JOIN pilot_districts ON pilot_districts.district_id = locations.pk_id
WHERE
            locations.geo_level_id = 4

                                 $where";

//result
$rsprov = mysqli_query($conn,$queryprov) or die();
//fetch data from rsprov
?>
<option value="">Select</option>
<option value="all">All</option>
<?php
while ($rowprov = mysqli_fetch_array($rsprov)) {
    ?>

    <?php
    ?>        <option value="<?php echo $rowprov['pk_id']; ?>" ><?php echo $rowprov['location_name']; ?></option>



    <?php
}
?>
 