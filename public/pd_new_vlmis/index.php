<?php
include("config_vlmis.php");
//
?>
<head>
    <script src="tableToExcel.js"></script>
    <link rel="stylesheet" type="text/css" href="datatable/datatables.min.css"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="bootstrap/bootstrap.min.js"></script>   
     <script type="text/javascript" src="datatable/datatables.min.js"></script> 



</head>
<!-- END HEAD -->
<body class="page-header-fixed page-quick-sidebar-over-content" style='overflow-x: hidden'>
    <div class="page-container">
        <div class="page-content-wrapper">
            <div class="page-content" style="min-height:353px; margin-left: 0px !important">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-body">

                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="col-md-2" id="province_div">
                                            <div class="control-group">
                                                <label>Province/Region</label>
                                                <div class="controls">
                                                    <select name="province" id="province" class="form-control input-sm">
                                                        <option>Select</option>
                                                        <?php
                                                        $qry_for_province = "SELECT 
	Province.pk_id,
	Province.location_name
FROM
	locations AS Province
WHERE
	Province.geo_level_id = 2
AND Province.parent_id IS NOT NULL
ORDER BY
	Province.parent_id ASC";
                                                        $result = mysqli_query($conn, $qry_for_province);
                                                        if (($result) == true) {

                                                            while ($row = $result->fetch_assoc()) {
                                                                ?>
                                                                <option value="<?php echo $row['pk_id']; ?>" ><?php echo $row['location_name']; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2" id="level_div" style="display:none">
                                            <div class="control-group">
                                                <label>Level</label>
                                                <div class="controls" >
                                                    <select name="level" id="level" class="form-control input-sm">

                                                        <option value="-1">ALL</option>
                                                        <option value="2" >Province</option>
                                                        <option value="3" >Division</option>
                                                        <option value="4" >District</option>
                                                        <option value="5" >Tehsil</option>
                                                        <option value="6" >UC</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label>&nbsp;</label>
                                                <div class="controls">
                                                    <input type="button" id="submit" value="GO" class="btn btn-primary input-sm" onclick="display_result()" style="height:25px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget">
                            <div class="col-lg-offset-5" style="display:none;margin-top: 100px;" id="div_loader" ><img src="loading.gif" /></div>
                            <div id="data_div"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <style>
        .page-content-wrapper .page-content{
            margin-left:0px !important;
        }
    </style>
    <script>
        function display_result() {
            // document.getElementById("data_div").innerHTML="Data Loading..";
            var lev = document.getElementById("level").value;

            var chl = document.getElementById("province").value;


            $.ajax({
                type: "POST",
                url: "display_record.php",
                data: {provinceId: chl, lev_id: lev},
                dataType: 'html',
                beforeSend: function () {

                    document.getElementById("data_div").innerHTML = "";
                    $('#div_loader').show();
                },
                success: function (data)
                {  $('#div_loader').hide();
                    $('#data_div').html(data);
                    $('#example').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false
                    });
                },
                complete: function () {

                }
            });
        }
        $(function () {

            $('#province').change(function () {

                $('#level_div').show();
            });
        });



    </script>
</body>
</html>