<?php
require_once 'config_d.php';

$qry_prov = "SELECT
locations.pk_id,
locations.location_name
FROM
locations
WHERE
locations.geo_level_id = 2
ORDER BY
locations.pk_id";

$row = $connv->query($qry_prov);
$res = mysqli_fetch_all($row, MYSQLI_ASSOC);
?>

<body class="page-header-fixed login">


    <table class="table table-striped table-bordered table-advance table-hover" border="1" style="font-size: 11px;" id="tbl">

        <tr>

            <th >Province</th>

<?php
$qry_tbl = "SELECT
                DISTINCT
                ccm_asset_types.pk_id,

                IF(ccm_asset_types.asset_type_name = 'None', Main.asset_type_name, ccm_asset_types.asset_type_name) as main
                FROM
                cold_chain
                INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                INNER JOIN locations ON warehouses.province_id = locations.pk_id
                INNER JOIN ccm_asset_types AS Main ON cold_chain.ccm_asset_type_id = Main.pk_id
                INNER JOIN ccm_asset_types ON Main.parent_id = ccm_asset_types.pk_id
                where Main.pk_id  <> 3
                GROUP BY
                locations.location_name,
                Main.asset_type_name
                ORDER BY main";
$row1 = $connv->query($qry_tbl);
$res1 = mysqli_fetch_all($row1, MYSQLI_ASSOC);
$ii = 1;
foreach ($res1 as $result1) {
    
    ?>
                <th> <?php echo $result1['main']; ?></th>

            <?php 
            
            $ii++;
} ?>
 <th>Total</th>

        </tr>





        <tbody>
<?php

foreach ($res as $result) {
    ?> 
                <tr>
                    <td>
                       
                        <?php echo $result['location_name']; ?></td>
                <?php
                $province_id = $result['pk_id'];
               $total_row = 0;
                foreach ($res1 as $result11) {
                    ?> <td>
                       
                    <?php
                    if ($result11['main'] == 'Vaccine Carriers-Cold Boxes') {
                        $ccm_id = "2";
                         $q1 = "and ccm_asset_types.pk_id = 0 "
                                 . " and   Main.pk_id = '$ccm_id'";
                    } else if ($result11['main'] == 'Ice Packs') {
                        $ccm_id = "4";
                         $q1 = "and ccm_asset_types.pk_id = 0 "
                                 . " and   Main.pk_id = '$ccm_id'";
                    }else if ($result11['main'] == 'Stabilizers'){
                        $ccm_id = "5";
                         $q1 = "and ccm_asset_types.pk_id = 0 "
                                 . " and   Main.pk_id = '$ccm_id'";
                    } else if ($result11['main'] == 'Generators'){
                       $ccm_id = "6";
                         $q1 = "and ccm_asset_types.pk_id = 0 "
                                 . " and   Main.pk_id = '$ccm_id'"; 
                    } else if ($result11['main'] == 'Vehicles'){
                       $ccm_id = "7";
                         $q1 = "and ccm_asset_types.pk_id = 0 "
                                 . " and   Main.pk_id = '$ccm_id'"; 
                    }
                    
                    else {
                        $ccm_id = $result11['pk_id'];
                        $q1 = "and   ccm_asset_types.pk_id = '$ccm_id'";
                    }
                    
                    $qry_province = "SELECT
                    locations.location_name,
                    Count(cold_chain.pk_id) AS total,
                    Main.asset_type_name,
                    ccm_asset_types.asset_type_name,
                    ccm_asset_types.pk_id as c_id,
                    Main.pk_id
                    FROM
                    cold_chain
                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                    INNER JOIN locations ON warehouses.province_id = locations.pk_id
                    INNER JOIN ccm_asset_types AS Main ON cold_chain.ccm_asset_type_id = Main.pk_id
                    INNER JOIN ccm_asset_types ON Main.parent_id = ccm_asset_types.pk_id
                    WHERE
                    locations.pk_id = '$province_id'
                     $q1
                    ";

                    $row2 = $connv->query($qry_province);
                    $res2 = mysqli_fetch_all($row2, MYSQLI_ASSOC);
                   
                    foreach ($res2 as $result2) {
                        ?>
                           
                         
                            <?php
                            $ccem_asset_id = $result2['c_id'];
                            $main_asset_id = $result2['pk_id'];
                            $t = $result2['total'];
                            $link = "ccem_detail.php?province=$province_id&asset=$ccem_asset_id&main=$main_asset_id&total=$t";
                           if ($t == "0"){
                               echo $t;
                           }else {
                            ?>
                                <a onclick="window.open('<?php echo $link; ?>', '_blank', 'scrollbars=1,width=600,height=500')"> <?php echo number_format($result2['total']) ?> </a>   
                          
                    <?php 
                    
                     $total_row += $result2['total'];
                           } } ?>
                                 </td>
                     <?php   } ?>    
                                 <td>
                                     <?php echo number_format($total_row);?>
                                 </td>
                </tr>

                <?php } ?>
        </tbody>
 <tfoot>
        <tr>
            <td  class="right">Total</td>
            <?php
             foreach ($res1 as $result11) {
                    ?> <td>
                       
                    <?php
                    if ($result11['main'] == 'Vaccine Carriers-Cold Boxes') {
                        $ccm_id = "2";
                         $q1 = " ccm_asset_types.pk_id = 0 "
                                 . " and   Main.pk_id = '$ccm_id'";
                    } else if ($result11['main'] == 'Ice Packs') {
                        $ccm_id = "4";
                         $q1 = " ccm_asset_types.pk_id = 0 "
                                 . " and   Main.pk_id = '$ccm_id'";
                    }else if ($result11['main'] == 'Stabilizers'){
                        $ccm_id = "5";
                         $q1 = " ccm_asset_types.pk_id = 0 "
                                 . " and   Main.pk_id = '$ccm_id'";
                    } else if ($result11['main'] == 'Generators'){
                       $ccm_id = "6";
                         $q1 = " ccm_asset_types.pk_id = 0 "
                                 . " and   Main.pk_id = '$ccm_id'"; 
                    } else if ($result11['main'] == 'Vehicles'){
                       $ccm_id = "7";
                         $q1 = " ccm_asset_types.pk_id = 0 "
                                 . " and   Main.pk_id = '$ccm_id'"; 
                    }
                    
                    else {
                        $ccm_id = $result11['pk_id'];
                        $q1 = "   ccm_asset_types.pk_id = '$ccm_id'";
                    }
                    
                    $qry_t_province = "SELECT
                    locations.location_name,
                    Count(cold_chain.pk_id) AS total,
                    Main.asset_type_name,
                    ccm_asset_types.asset_type_name,
                    ccm_asset_types.pk_id as c_id,
                    Main.pk_id
                    FROM
                    cold_chain
                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                    INNER JOIN locations ON warehouses.province_id = locations.pk_id
                    INNER JOIN ccm_asset_types AS Main ON cold_chain.ccm_asset_type_id = Main.pk_id
                    INNER JOIN ccm_asset_types ON Main.parent_id = ccm_asset_types.pk_id
                    WHERE
                    
                     $q1
                    ";

                    $row22 = $connv->query($qry_t_province);
                    $res22 = mysqli_fetch_all($row22, MYSQLI_ASSOC);
                    foreach ($res22 as $result22) {
                        ?>
                        <?php echo number_format($result22['total']); ?>
                     
                    <?php    }?>
                        </td>
             <?php $t_toal += $result22['total'];} ?> 
                        <td>
                           <?php echo number_format($t_toal);?> 
                        </td>
        </tr>
    </tfoot>
    </table>

</body>
<!-- END BODY -->
</html>


