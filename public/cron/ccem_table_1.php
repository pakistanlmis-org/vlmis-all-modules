<?php
require_once 'config_d.php';

$qry_prov = "SELECT
ccm_asset_types.asset_type_name,
ccm_asset_types.pk_id
FROM
ccm_asset_types
WHERE
ccm_asset_types.parent_id = 0
and pk_id <> 0";

$row = $connv->query($qry_prov);
$res = mysqli_fetch_all($row, MYSQLI_ASSOC);
?>

<body class="page-header-fixed login">


    <table class="table table-striped table-bordered table-advance table-hover" border="1" style="font-size: 11px;" id="tbl">

        <tr>

            <th >Asset Type</th>

            <th>No. of Assets</th>

        </tr>





        <tbody>
            <?php
            foreach ($res as $result) {
                ?> 
                <tr>
                    <td colspan="2" style="font-weight: bold;"> 

                        <?php
                        $pk_id = $result['pk_id'];
                        echo $result['asset_type_name'];
                        ?></td>


                </tr>

                <?php
                if ($pk_id == 1) {
                    $q1 = "ccm_asset_types.pk_id = $pk_id";
                } else if ($pk_id == 3) {
                    $q1 = "ccm_asset_types.pk_id = $pk_id";
                } else {
                    $q1 = "Main.pk_id = $pk_id";
                }
                $qry_prov1 = "SELECT
	ccm_asset_types.asset_type_name `Asset Type`,
	Main.asset_type_name sub_asset,
	Count(cold_chain.pk_id) AS total
FROM
	cold_chain
INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
INNER JOIN locations ON warehouses.province_id = locations.pk_id
INNER JOIN ccm_asset_types AS Main ON cold_chain.ccm_asset_type_id = Main.pk_id
INNER JOIN ccm_asset_types ON Main.parent_id = ccm_asset_types.pk_id
where $q1
GROUP BY
	Main.asset_type_name
ORDER BY
	ccm_asset_types.asset_type_name
        ";

                $row1 = $connv->query($qry_prov1);
                $res1 = mysqli_fetch_all($row1, MYSQLI_ASSOC);
                ?>

                <?php
                $asset_total = 0;
                foreach ($res1 as $result1) {
                    ?> 
                    <tr>

                        <td><?php echo $result1['sub_asset'] ?></td> 
                        <td><?php
                            echo number_format($result1['total']);
                            $asset_total += $result1['total'];
                            $overall_total += $result1['total'];
                            ?></td> 

                    </tr>
                <?php } ?>
                    <?php if($pk_id== 1 || $pk_id == 3) {?>
                <tr>
                    <td class="right" style="font-weight: bold;">Total</td>
                    <td><?php echo number_format($asset_total); ?></td>
                    
                </tr>
                    <?php }?>
            <?php } ?>

        </tbody>
        <tfoot>
            <tr>
                <td  class="right" style="font-weight: bold;">Total</td>
                <td><?php echo number_format($overall_total); ?></td>

        </tfoot>
    </table>

</body>
<!-- END BODY -->
</html>


