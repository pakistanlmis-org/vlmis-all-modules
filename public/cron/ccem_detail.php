<?php
require_once 'config_d.php';
$province_id = $_GET['province'];
$ccm_asset_id = $_GET['asset'];
$main_asset_id = $_GET['main'];

if ($ccm_asset_id == 3 || $ccm_asset_id == 1) {
    ?>
    <?php
    $qr = "SELECT
    ccm_asset_types.pk_id,
ccm_asset_types.asset_type_name
FROM
ccm_asset_types
WHERE
ccm_asset_types.parent_id = $ccm_asset_id";
$row_qr = $connv->query($qr);
$res_qr = mysqli_fetch_all($row_qr, MYSQLI_ASSOC);
foreach ($res_qr as $result_qr) {
    
    if ($ccm_asset_id == 1) {
        $id = $result_qr['pk_id'];
        $q1 = "Main.pk_id = '$id'";
    } else if ($ccm_asset_id == 3) {
         $id = $result_qr['pk_id'];
        $q1 = "Main.pk_id = '$id'";
    } else {
        $q1 = "Main.pk_id = '$main_asset_id'";
    }




    $qry_prov = "SELECT
dis.location_name,
locations.location_name as province,
Count(cold_chain.pk_id) AS total,
Sum(IF(ccm_status_history.ccm_status_list_id<>3  , 1, 0)) AS Working,
Sum(IF(ccm_status_history.ccm_status_list_id IS NULL  , 1, 0)) AS v_Working,

Sum(IF(ccm_status_history.ccm_status_list_id=3, 1, 0)) AS NotWorking,
Main.asset_type_name,
ccm_asset_types.asset_type_name,
ccm_asset_types.pk_id AS c_id,
Main.pk_id,
Main.asset_type_name as m_t,
ccm_status_history.ccm_status_list_id
FROM
cold_chain
INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
INNER JOIN locations ON warehouses.province_id = locations.pk_id
INNER JOIN ccm_asset_types AS Main ON cold_chain.ccm_asset_type_id = Main.pk_id
INNER JOIN ccm_asset_types ON Main.parent_id = ccm_asset_types.pk_id
INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
INNER JOIN locations AS dis ON warehouses.district_id = dis.pk_id
WHERE
locations.pk_id = '$province_id' AND

$q1
GROUP BY
dis.location_name


";

    $row = $connv->query($qry_prov);
    $res = mysqli_fetch_all($row, MYSQLI_ASSOC);
    if (count($res) > 0) {
    ?>

   
   <p style="font-size: 10px">
    <?php if ($res['0']['province'] == "National") { ?>
            Level:
        <?php } else { ?>
            Province:
        <?php } ?><b style="font-size: 10px"> <?php echo $res['0']['province'] ?></b></p>
    <p style="font-size: 10px">Asset Type: <b style="font-size: 10px"> <?php
        if ($res['0']['asset_type_name'] == "None") {
        echo $res['0']['asset_type_name'];    
        } else {
            echo $res['0']['m_t'];
        }
        ?></b></p>
    <table class="table table-striped table-hover table-condensed" border="1" cellpadding="4" cellspacing="0" >
        <thead style="font-size: 10px">
            <tr>
                <th class="col-md-2">S.No</th>
                <th class="col-md-2">District</th>
                
                <th class="col-md-2">Functional</th>
                <th class="col-md-2">Non Functional</th>
                <th class="col-md-2">Total</th>
            </tr>
        </thead>
        <tbody  style="font-size: 10px">
    <?php
    $i = 1;
    $total_w = 0;
    $total_n = 0;
    foreach ($res as $result) {
        ?>
                <tr>            
                    <td><?php echo $i; ?></td>
                    <td><?php echo $result['location_name'] ?></td>
                   
                    <td><?php echo number_format($result['Working'] + $result['v_Working']); ?></td>    
                    <td><?php echo number_format($result['NotWorking']); ?></td>    
                    <td><?php echo number_format($result['Working'] + $result['v_Working'] + $result['NotWorking']); ?></td>   
                </tr>
                <?php
                $i++;
                $total_w += $result['Working'] + $result['v_Working'];
                $total_n += $result['NotWorking'];
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="right" style="font-size: 10px">Total</td>
                <td style="font-size: 10px"><?php echo number_format($total_w); ?></td>
                <td style="font-size: 10px"><?php echo number_format($total_n); ?></td>
                <td style="font-size: 10px"><?php echo number_format($total_w + $total_n); ?></td>
            </tr>
        </tfoot>
    </table>
    <?php
}} }else {
    if ($ccm_asset_id == 1) {
        $q1 = "ccm_asset_types.pk_id = '$ccm_asset_id'";
    } else if ($ccm_asset_id == 3) {
        $q1 = "ccm_asset_types.pk_id = '$ccm_asset_id'";
    } else {
        $q1 = "Main.pk_id = '$main_asset_id'";
    }

    $qry_prov = "SELECT
dis.location_name,
locations.location_name as province,
Count(cold_chain.pk_id) AS total,
Sum(IF(ccm_status_history.ccm_status_list_id<>3  , 1, 0)) AS Working,
Sum(IF(ccm_status_history.ccm_status_list_id IS NULL  , 1, 0)) AS v_Working,

Sum(IF(ccm_status_history.ccm_status_list_id=3, 1, 0)) AS NotWorking,
Main.asset_type_name,
ccm_asset_types.asset_type_name,
ccm_asset_types.pk_id AS c_id,
Main.pk_id,
Main.asset_type_name as m_t,
ccm_status_history.ccm_status_list_id
FROM
cold_chain
INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
INNER JOIN locations ON warehouses.province_id = locations.pk_id
INNER JOIN ccm_asset_types AS Main ON cold_chain.ccm_asset_type_id = Main.pk_id
INNER JOIN ccm_asset_types ON Main.parent_id = ccm_asset_types.pk_id
INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
INNER JOIN locations AS dis ON warehouses.district_id = dis.pk_id
WHERE
locations.pk_id = '$province_id' AND

$q1
GROUP BY
dis.location_name


";

    $row = $connv->query($qry_prov);
    $res = mysqli_fetch_all($row, MYSQLI_ASSOC);
    ?>

    <p style="font-size: 10px">
        <?php if ($res['0']['province'] == "National") { ?>
            Level:
        <?php } else { ?>
            Province:
        <?php } ?><b style="font-size: 10px"> <?php echo $res['0']['province'] ?></b></p>
    <p style="font-size: 10px">Asset Type: <b style="font-size: 10px"> <?php
        if ($res['0']['asset_type_name'] == "None") {
            echo $res['0']['m_t'];
        } else {
            echo $res['0']['asset_type_name'];
        }
        ?></b></p>
    <table class="table table-striped table-hover table-condensed" border="1" cellpadding="4" cellspacing="0" >
        <thead style="font-size: 10px">
            <tr>
                <th class="col-md-2">S.No</th>
                <th class="col-md-2">District</th>
               
                <th class="col-md-2">Functional</th>
                <th class="col-md-2">Non Functional</th>
                <th class="col-md-2">Total</th>
            </tr>
        </thead>
        <tbody  style="font-size: 10px">
            <?php
            $i = 1;
            foreach ($res as $result) {
                ?>
                <tr>            
                    <td><?php echo $i; ?></td>
                    <td><?php echo $result['location_name'] ?></td>
                    
                    <td><?php echo number_format($result['Working'] + $result['v_Working']); ?></td>    
                    <td><?php echo number_format($result['NotWorking']); ?></td>    
                    <td><?php echo number_format($result['Working'] + $result['v_Working'] + $result['NotWorking']); ?></td>   
                </tr>
                <?php
                $i++;
                $total_w += $result['Working'] + $result['v_Working'];
                $total_n += $result['NotWorking'];
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="right" style="font-size: 10px">Total</td>
                <td style="font-size: 10px"><?php echo number_format($total_w); ?></td>
                <td style="font-size: 10px"><?php echo number_format($total_n); ?></td>
                <td style="font-size: 10px"><?php echo number_format($total_w + $total_n); ?></td>
            </tr>
        </tfoot>
    </table>

<?php } ?> 




