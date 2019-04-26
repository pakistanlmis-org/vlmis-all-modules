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
$prov_sel = $_REQUEST["prov_sel"];
if ($dist_sel != 'all') {
    $where = "AND locations.district_id = $dist_sel";
} else {

    $where = "AND locations.province_id = $prov_sel";
}


 $queryprov = "SELECT
locations.location_name,
locations.pk_id
FROM
locations
WHERE
locations.geo_level_id = 5 

                                 $where";

//result
$rsprov = mysqli_query($conn, $queryprov) or die();
//fetch data from rsprov
while ($rowprov = mysqli_fetch_array($rsprov)) {
    ?>
    <tr>
        <td><?php echo $rowprov['location_name']; ?></td>

        <?php
        $dist_id = $rowprov['pk_id'];
        ?>        <td><input class="form-control input-sm" class="dist_d1" type="text" name="dist1[]" id="<?php echo $dist_id; ?>-dist1" value=""></td>
    </tr>
    <?php
}
?>
<tr>
    <td class="col-md-1"><input type="submit" name="go" id="submit" value="GO" onclick="getDataTeh1()" class="btn btn-primary input-sm" style="margin-top:28px;" /></td></tr>