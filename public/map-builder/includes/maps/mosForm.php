<?php
/**
 * mosForm
 * @package includes/maps
 * 
 * @author     Ajmal Hussain
 * @email <ahussain@ghsc-psm.org>
 * 
 * @version    2.2
 * 
 */
//check date
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (date('d') > 10) {
    $date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
} else {
    $date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m-d'))));
}
//selected month
$sel_month = date('m', strtotime($date));
//selected year
$sel_year = date('Y', strtotime($date));
?>

<table width="50%">
    <tr>
        <td class="col-md-1"><label class="control-label">Province/Region</label>

            <select name="prov_sel" id="prov_sel" class="form-control input-sm" onchange="getData()" >
                <option value="all">All</option>
                <?php
                //query prov
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
        WHERE
        locations.geo_level_id = 2
        and locations.pk_id NOT IN (10)";
               
                //result
             
                $rsprov = mysqli_query($conn,$queryprov);
                
                //fetch data from rsprov
                while ($rowprov = mysqli_fetch_array($rsprov)) {
                    if ($sel_prov == $rowprov['pk_id']) {
                        $sel = "selected='selected'";
                    } else {
                        $sel = "";
                    }
                    ?>
                    <option value="<?php echo $rowprov['pk_id']; ?>" <?php echo $sel; ?>><?php echo $rowprov['location_name']; ?></option>
                    <?php
                }
                ?>

            </select>
        </td>
        <td class="col-md-1"><label class="control-label">District(Select District to see Tehsil Map)</label>

            <select name="dist" id="dist" class="form-control input-sm" onchange="getDataTeh()" >
                <option value="">Select</option>
                <?php
                //query prov
                $queryprov1 = "SELECT
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
            locations.modified_date
FROM
locations
INNER JOIN pilot_districts ON pilot_districts.district_id = locations.pk_id
WHERE
            locations.geo_level_id = 4";
                //result
               
                $rsprov1 = mysqli_query($conn,$queryprov1) or die();
                //fetch data from rsprov
                while ($rowprov = mysqli_fetch_array($rsprov1)) {
                    if ($sel_prov == $rowprov['pk_id']) {
                        $sel = "selected='selected'";
                    } else {
                        $sel = "";
                    }
                    ?>
                    <option value="<?php echo $rowprov['pk_id']; ?>" <?php echo $sel; ?>><?php echo $rowprov['location_name']; ?></option>
                    <?php
                }
                ?>

            </select>
        </td>
    </tr>
</table>
