<!DOCTYPE html>
<?php
set_time_limit(0);
error_reporting(1);
//include '/home/vlmispk/cron/config.php';
include 'config.php';
?>
<html lang="en">
    <head>
        <title>Mapping</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />



    </head>
    <style>
        table#myTable{margin-top:0px !important; color: #000;}
        table#myTable{margin-top:20px;border-collapse: collapse;border-spacing: 0; border:1px solid #999;}
        table#myTable tr td{font-size:11px;padding:3px; text-align:left; border:1px solid #999; color: #000;}
        table#myTable tr th{font-size:11px;padding:3px; text-align:center; border:1px solid #999; color: #000;}
        table#myTable tr td.TAR{text-align:right; padding:5px;width:50px !important;}
        .sb1NormalFont {color: #444444; font-size: 11px; font-weight: bold; text-decoration: none;}
        p{margin-bottom:5px; font-size:11px !important; line-height:1 !important; padding:0 !important; color: #000;}
        table#headerTable tr td{ font-size:11px; color: #000;}
        h4{margin:0; color: #000; font-size:14px;}
        h5{margin:15px 0 5px 0; color: #000;}
        h6{margin:0; color: #000; font-size:12px;}
        .right{text-align:right !important;}
        .center{text-align:center !important;}

        /* Print styles */
        @media only print
        {
            table#myTable{margin-top:0px !important;}
            table#myTable tr th{font-size:8px;padding:3px !important; text-align:center; border:1px solid #999; color: #000;}
            table#myTable tr td{font-size:8px;padding:3px !important; text-align:left; border:1px solid #999; color: #000;}
            #doNotPrint{display: none !important;}
            h4{margin:0; color: #000;}
            h5{margin:0; color: #000;}
            h6{margin:0; color: #000;}
            p{margin-bottom:5px; font-size:11px !important; line-height:1 !important; padding:0 !important; color: #000;}    
        }
    </style>
    <body>

        <div class="container">
            <br>

            <form method="post" action="mapping.php" id="for" name="for" >
                <div class="row">
                    <h4>   CCEM Location </h4>
                    <div class="form-group col-xs-2">
                        <label for="province">Province:</label>
                        <select id="province" class="form-control" required >
                            <option>Select</option>
                            <?php
                            $query = $conn->query("SELECT
                                        DISTINCT
                                        
                                        import_ccem.subnational_1
                                        FROM
                                        import_ccem");
                            while ($row = $query->fetch_assoc()) {
                                // $id=$row["id_expense"];  
                                $pk_id = $row["subnational_1"];
                                $product_name = $row["subnational_1"];
                                ?>
                                <option value= "<?php echo $pk_id; ?>" ><?php echo $product_name; ?> </option>
                            <?php }
                            ?>

                        </select>

                    </div>

                    <div class="form-group col-xs-2">
                        <label for="district">District:</label>
                        <!--<select name="stakeholder" id="stakeholder" class="form-control">-->
                        <select id="district" name="district" class="form-control" required="">

                        </select>
                    </div>


                </div>
                <div class="row">
                    <h4>  LMIS Location </h4>
                    <div class="form-group col-xs-2">
                        <label for="province">Province:</label>
                        <select id="m_province"  name="m_province" class="form-control" required >
                            <option>Select</option>
                            <?php
                            $query = $conn->query("SELECT
                            locations.location_name,
                            locations.geo_level_id,
                            locations.pk_id
                            FROM
                            locations
                            WHERE
                            locations.geo_level_id = 2
                            and pk_id NOT IN (10)");
                            while ($row = $query->fetch_assoc()) {
                                // $id=$row["id_expense"];  
                                $pk_id = $row["pk_id"];
                                $product_name = $row["location_name"];
                                ?>
                                <option value= "<?php echo $pk_id; ?>" ><?php echo $product_name; ?> </option>
                            <?php }
                            ?>

                        </select>

                    </div>

                    <div class="form-group col-xs-2">
                        <label for="district">District:</label>
                        <!--<select name="stakeholder" id="stakeholder" class="form-control">-->
                        <select id="m_district" name="m_district" class="form-control" required="">

                        </select>
                    </div>
                    <div class="form-group col-xs-2">

                        <p style="margin-top:23px !important; "><button type="submit" class="btn btn-primary" onclick="loaddata();" name="submit" id="submit">Search</button></p>
                    </div>

                </div>
            </form>
            <?php
            if (isset($_REQUEST['district'])) {
                $dis = $_REQUEST['district'];
                // Get Stakeholders
                $stk = "SELECT
                        DISTINCT
                import_ccem.subnational_1,

                import_ccem.lowest_distribution,

                import_ccem.service_point,
                import_ccem.hf_id
                FROM
                import_ccem
                where 
                lowest_distribution IS NOT NULL
                and service_point IS NOT NULL
                and (hf_id IS NULL OR hf_id = '')
                and level = 'SP'
                and lowest_distribution = '$dis'
 
                ORDER BY subnational_1,lowest_distribution,service_point
              ";
                $row_select_make = $conn->query($stk);
            }


            if (count($row_select_make) > 0) {
                ?>
                <h2>Mapping</h2>
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th width="20%">Province</th>

                            <th width="20%">District</th>
                            <th width="20%">HF</th>
                            <th width="40%">LMIS Location</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $i = 1;
                        while ($row = $row_select_make->fetch_assoc()) {
                            ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $row['subnational_1']; ?></td>
                                <td style="text-align:center;"><?php echo $row['lowest_distribution']; ?></td>
                                <td style="text-align:center;"><?php echo $row['service_point']; ?></td>

                                <td >
                                    <div id="<?php echo $i; ?>" name="<?php echo $i; ?>" >
                                    </div>
                                    <select class="form-control selectpicker" data-live-search="true" id="<?php echo $i; ?>-main_hf" name="main_hf[]"  onchange="onChang(<?php echo $i; ?>)">
                                        <option>Select </option>
                                        <option value = "<?php echo $row['service_point'] . '|' .'0' ?>">Empty </option>

                                        <?php
                                        // Get Stakeholders
                                        $m_dis = $_REQUEST['m_district'];
                                        $stk1 = "SELECT
                                        warehouses.pk_id,
                                        prov.location_name AS prov,
                                        dis.location_name as dis,
                                        warehouses.warehouse_name AS war
                                        FROM
                                        warehouses
                                        INNER JOIN locations AS dis ON warehouses.district_id = dis.pk_id
                                        INNER JOIN locations AS prov ON warehouses.province_id = prov.pk_id
                                        WHERE
                                        warehouses.stakeholder_office_id = 6 AND
                                        warehouses.`status` = 1
                                       
                                        and warehouses.district_id = '$m_dis' 
                                        order by prov,dis,war";
                                        $row_select_make1 = $conn->query($stk1);

                                        while ($row2 = $row_select_make1->fetch_assoc()) {
                                            ?>                    
                                            <option value="<?php echo $row['service_point'] . '|' . $row2['pk_id'] ?>"><?php echo $row2['prov'] . ' | ' . $row2['dis'] . ' | ' . $row2['war']; ?></option>
                                        <?php }
                                        ?>  




                                    </select>

                                </td>


                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>

            <?php } ?>

        </div>

    </body>
</html>
<script>
    $(function () {

        $(".selectpicker").select2();
    });
    $('#province').change(function () {
        $.ajax({
            type: "POST",
            url: "ajax-district.php",
            data: {province_id: $(this).val()},
            dataType: 'html',
            success: function (data) {

                $('#district').html(data);
            }
        });
    })
    $('#m_province').change(function () {
        $.ajax({
            type: "POST",
            url: "ajax-m_district.php",
            data: {province_id: $(this).val()},
            dataType: 'html',
            success: function (data) {

                $('#m_district').html(data);
            }
        });
    })
    function onChang(i) {

        $.ajax({
            type: "POST",
            url: "ajax-update-mapping.php",
            data: {hf_id: $("#" + i + "-main_hf").val()},
            dataType: 'html',
            success: function (data) {
                $("#" + i).html("<p style='color:red'>Mapped</p>");

            }
        });
    }
</script>