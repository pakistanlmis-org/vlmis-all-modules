<?php
//exit("You are not allowed to access this file");
require_once("../Includes/db.php");
$province = '';
$filter = 'tbl_locations.LocLvl = 2';
$district = '';
if (isset($_POST['filter-prov'])) {
    $province = $_POST['sel_prov'];
    $district = $_POST['sel_dist'];
    /* if(!empty($province))
      {
      $filter=" tbl_locations.LocLvl = 2 AND province_id=".$province;

      } */
    if (!empty($province) && !empty($district)) {
        $filter = " tbl_locations.LocLvl = 4 AND district_id=" . $district;
    } elseif (!empty($province)) {
        $filter = " tbl_locations.LocLvl = 2 AND province_id=" . $province;
    }
}

$sqlFilter = "SELECT
tbl_locations.PkLocID,
tbl_locations.LocName
FROM
tbl_locations
WHERE
tbl_locations.ParentID = 10";

$sql = "SELECT
tbl_locations.PkLocID,
tbl_locations.LocName
FROM
tbl_locations
WHERE " . $filter;
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

<table style="border-bottom: 1px solid #C1DAD7;border-right: 1px solid #C1DAD7;">
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
            $where = '';
        }
        if (!empty($province) && !empty($district)) {
            $where = 'where District.PkLocID =' . $id;
        } else {

            $where = 'where Province.PkLocID=' . $id;
        }
         $strSql = "SELECT DISTINCT
                sysuser_tab.UserID AS userId,
		sysuser_tab.usrlogin_id AS Username,
		Province.LocName AS Province,
		UCs.LocName AS UC,
		District.LocName AS District,
		tbl_warehouse.wh_name AS Warehouse,
		sysuser_tab.sysusr_pwd AS pwd,
		stakeholder.stkname,
		tbl_warehouse.wh_id,
		tbl_warehouse.locid,
		stakeholder.lvl,
		tbl_warehouse.ccm_wh_id,
		UCs.ccm_loc_id
		FROM
		sysuser_tab
		INNER JOIN wh_user ON wh_user.sysusrrec_id = sysuser_tab.UserID
		
		INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = wh_user.wh_id
		LEFT JOIN tbl_locations AS UCs ON tbl_warehouse.locid = UCs.PkLocID
		INNER JOIN tbl_locations AS District ON tbl_warehouse.dist_id = District.PkLocID
		INNER JOIN tbl_locations AS Province ON District.province_id = Province.PkLocID
		INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
		$where
		ORDER BY stakeholder.lvl,Username";
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

            <th>Wh_id </td><td> CCM WH Id</th>
            <th>Loc_id </td><td> CCM LOC Id</th>


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
            <tr >
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

                <td ><?php echo $result->wh_id ?></td><td> <?php if($result->ccm_wh_id){ ?><a onclick="window.open('add-ccm-whid.php?wh_id=<?= $result->wh_id ?>&user_id=<?= $result->userId ?>', '_blank', 'scrollbars=1,width=300,height=305')" href="javascript:void(0);"> <?php echo $result->ccm_wh_id; ?></a><?php } else {?>  <a onclick="window.open('add-ccm-whid.php?wh_id=<?= $result->wh_id ?>&user_id=<?= $result->userId ?>', '_blank', 'scrollbars=1,width=300,height=305')" href="javascript:void(0);">Add</a><?php }?></td>
                <td><?php
                   echo $result->locid ?></td><td><?php if($result->ccm_loc_id){ ?><a onclick="window.open('add-ccm-locid.php?loc_id=<?= $result->locid ?>&user_id=<?= $result->userId ?>', '_blank', 'scrollbars=1,width=300,height=305')" href="javascript:void(0);"><?php echo $result->ccm_loc_id;?></a><?php }  else {?><a onclick="window.open('add-ccm-locid.php?loc_id=<?= $result->locid ?>&user_id=<?= $result->userId ?>', '_blank', 'scrollbars=1,width=300,height=305')" href="javascript:void(0);">Add</a><?php }?></td>

            </tr>
            <?php
            $countcenter++;
        }
    }
    ?>
</table>