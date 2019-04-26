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
$prov_sel = 'all';

if ($prov_sel != 'all') {
    $where = "AND locations.province_id = $prov_sel";
} else {

    $where = "";
}


$queryprov = "SELECT
locations.location_name,
locations.pk_id
FROM
locations
WHERE
locations.geo_level_id = 4 

                                 $where";

//result
$rsprov = mysqli_query($conn,$queryprov) or die();
//fetch data from rsprov
while ($rowprov = mysqli_fetch_array($rsprov)) {
    ?>
    <tr>
        <td><?php echo $rowprov['location_name']; ?></td>

        <?php
        $dist_id = $rowprov['pk_id'];
        ?>        <td><input class="form-control input-sm" class="dist_d" type="text" name="dist[]" id="<?php echo $dist_id; ?>-dist" value=""></td>
    </tr>
    <?php
}
?>
<tr>
    <td class="col-md-1"><input type="submit" name="go" id="submit" value="GO" onclick="getData1()" class="btn btn-primary input-sm" style="margin-top:28px;" /></td></tr>