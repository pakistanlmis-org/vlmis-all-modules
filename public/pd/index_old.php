<?php
//exit("You are not allowed to access this file");
require_once("inc/db.php");
$province = '';
$filter = 'locations.geo_level_id = 2 AND province_id=1';
$district = '';
if (isset($_POST['filter-prov'])) {
    $province = $_POST['sel_prov'];
    $district = $_POST['sel_dist'];
    /* if(!empty($province))
      {
      $filter=" tbl_locations.LocLvl = 2 AND province_id=".$province;

      } */
    if (!empty($province) && !empty($district)) {
        $filter = " locations.geo_level_id = 4 AND district_id=" . $district;
    } elseif (!empty($province)) {
        $filter = " locations.geo_level_id = 2 AND province_id=" . $province;
    }
}

$sqlFilter = "SELECT
	locations.pk_id as PkLocID,
	locations.location_name as LocName
FROM
	locations
WHERE
	locations.parent_id = 10";
//echo $sqlFilter."<br>";

$sql = "SELECT
	locations.pk_id as PkLocID,
	locations.location_name as LocName
FROM
	locations
WHERE " . $filter;
//echo $sql."<br>";
$rs = mysql_query($sql);
?>
<style>
    table {
        border-right: 1px solid #C1DAD7;
        border-left: 1px solid #C1DAD7;
        border-bottom: 1px solid #C1DAD7;
        border-top: 1px solid #C1DAD7;        
    }
    th {
        font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica,
            sans-serif;
        color: #000;
        border-right: 1px solid #C1DAD7;
        border-bottom: 1px solid #C1DAD7;
        border-top: 1px solid #C1DAD7;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-align: left;
        padding: 6px 6px 6px 12px;
        background: #CAE8EA no-repeat;
    }
    td {
        font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica,
            sans-serif;
        border-right: 1px solid #C1DAD7;
        border-bottom: 1px solid #C1DAD7;
        background: #fff;
        padding: 6px 6px 6px 12px;
        color: #000;
    }
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
    $(function() {
        //$('#sel_prov').change(function(){

        $.ajax({
            url: 'ajaxDistrict.php',
            data: 'province_id=' + $('#sel_prov').val() + '&dist_id=' + '<?php
if (isset($_POST['sel_dist'])) {
    echo $_POST['sel_dist'];
} else {
    echo ' ';
}
?>',
            type: 'POST',
            success: function(data) {
                $('#sel_dist').html(data);
            }
        });
        $('#sel_prov').change(function() {

            $.ajax({
                url: 'ajaxDistrict.php',
                data: 'province_id=' + $('#sel_prov').val(),
                type: 'POST',
                success: function(data) {
                    $('#sel_dist').html(data);
                }
            });
        });

    });
</script>

<table>
    <form name="filter_prov" action="" method="post">
        <tr>
            <td><select name="sel_prov" id="sel_prov">
                    <option value="">Select Province</option>
                    <?php
                    $rs1 = mysql_query($sqlFilter);
                    while ($rowProv = mysql_fetch_assoc($rs1)) {
                        ?><option value="<?php echo $rowProv['PkLocID'] ?>" <?php
                        if (isset($province) && ($province == $rowProv['PkLocID'])) {
                            echo "selected=selected";
                        }
                        ?>><?php echo $rowProv['LocName'] ?></option>
                            <?php } ?>
                </select></td>
            <td>  <select name="sel_dist" id="sel_dist">
                    <option value="">Select Province First</option>
                </select>
            </td>
            <td><input type="submit" name="filter-prov" value="Filter"></td>
            <td id="ajaxSummary" colspan="5" height> 

            </td>

        </tr></form> 

    <?php
    $where = '';
    while ($row = mysql_fetch_object($rs)) {


        echo "<tr><td colspan='10'><h2>" . $row->LocName . "</h2></td> </tr>";
        $id = $row->PkLocID;
        if (empty($province)) {
            $where = 'where warehouses.status=1 ';
        }
        if (!empty($province) && !empty($district)) {
            $where = 'where District.pk_id =' . $id.' AND warehouses.status=1 ';
        } else {
            $where = 'where Province.pk_id=' . $id.' AND warehouses.status=1';
        }
        
        $strSql = "SELECT DISTINCT
	users.pk_id AS userId,
	users.login_id AS Username,
	Province.location_name AS Province,
	UCs.location_name AS UC,
	District.location_name AS District,
	warehouses.warehouse_name AS Warehouse,
	users.`password` AS pwd,
	stakeholders.stakeholder_name as stkname,
	warehouses.pk_id as wh_id,
	warehouses.location_id as locid,
	stakeholders.geo_level_id as lvl,
	warehouses.ccem_id as ccm_wh_id
FROM
	users
INNER JOIN warehouse_users ON warehouse_users.user_id = users.pk_id
INNER JOIN warehouses ON warehouses.pk_id = warehouse_users.warehouse_id
LEFT JOIN locations AS UCs ON warehouses.location_id = UCs.pk_id
INNER JOIN locations AS District ON warehouses.district_id = District.pk_id
INNER JOIN locations AS Province ON District.province_id = Province.pk_id
INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
$where
ORDER BY
	stakeholders.geo_level_id,
	Username";
        //echo $strSql."<br>";
        $user_id = '';
        $rsSql = mysql_query($strSql);
        ?>

        <tr>
            <th>Sr.No</th>
            <th>Province</th>
            <th>District</th>
            <th>UC</th>
            <th>Warehouse</th>
            <th>Username</th>
            <th></th>

            <th>Wh_id-loc_id</th>
            <th>CCM WH Id</th>


        </tr>
        <?php
        $stkname = '';

        while ($result = mysql_fetch_object($rsSql)) {


            if ($result->stkname != $stkname) {
                $countcenter = 1;
                $count = 1;
                ?>
                <tr><td colspan="10" style="background-color: #d0e9c6; "><h3><?php echo $result->stkname; ?>s</h3></td></tr>

                <?php
                $stkname = $result->stkname;
            }
            ?>
            <tr>
                <td><?php echo $countcenter; ?> </td>
                <td><?php echo $result->Province; ?></td>
                <td><?php echo $result->District; ?></td>
                <td><?php
                    if ($result->lvl == 6) {
                        echo $result->UC;
                    } else
                        echo "-";
                    ?></td>
                <td><?php echo $result->Warehouse; ?></a></td>
                <td><a onclick="window.open('update-user.php?user=<?= $result->userId ?>', '_blank', 'scrollbars=1,width=350,height=315')" href="javascript:void(0);"><?php
                        //if($result->lvl==6){echo $count++;};

                        echo $result->Username;
                        ?> </a></td>
                <td style="color:#fff;"><?php echo base64_decode($result->pwd); ?></td>

                <td><?php echo $result->wh_id . "-" . $result->locid; ?></td>
                <td><?php
                    if (!empty($result->ccm_wh_id)) {
                    ?>
                    <a onclick="window.open('update-ccm-whid.php?wh_id=<?= $result->wh_id ?>&user_id=<?= $result->userId ?>', '_blank', 'scrollbars=1,width=300,height=305')" href="javascript:void(0);">  <?=$result->ccm_wh_id;?> </a>
                    <?php
                        } else {
                        ?>
                        <a onclick="window.open('add-ccm-whid.php?wh_id=<?= $result->wh_id ?>&user_id=<?= $result->userId ?>', '_blank', 'scrollbars=1,width=300,height=305')" href="javascript:void(0);">Add</a>
                        <?php
                    }
                    ?></td>

            </tr>
            <?php
            $countcenter++;
        }
    }
    ?>
</table>