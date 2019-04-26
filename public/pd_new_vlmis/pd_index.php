<?php
include("config_vlmis.php");
//
?>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
</head>
<!-- END HEAD -->
<body class="page-header-fixed page-quick-sidebar-over-content">
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
                                                <label>Province</label>
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
                                        <div class="col-md-2" id="division_div">
                                            <div class="control-group">
                                                <label>Division</label>
                                                <div class="controls">
                                                    <select name="division" id="division" class="form-control input-sm">
                                                        <option value="0" >Select</option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2" id="district_div">
                                            <div class="control-group">
                                                <label>District</label>
                                                <div class="controls">
                                                    <select name="district" id="district" class="form-control input-sm">
<option value="0" >Select</option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2" id="tehsil_div">
                                            <div class="control-group">
                                                <label>Tehsil</label>
                                                <div class="controls">
                                                    <select name="tehsil" id="tehsil" class="form-control input-sm">
<option value="0" >Select</option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2" id="uc_div">
                                            <div class="control-group">
                                                <label>UC</label>
                                                <div class="controls">
                                                    <select name="uc" id="uc" class="form-control input-sm">
                                                        <option value="0" >Select</option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label>&nbsp;</label>
                                                <div class="controls">
                                                    <input type="button" id="submit" value="GO" class="btn btn-primary input-sm" onclick="display_result()" >
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
                    <div class="col-md-12">
                        <div class="widget">
                            <div class="col-lg-offset-4" style="display:none" id="div_loader"><img src="loading.gif" /></div>
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
           var divsion = document.getElementById("division").value;
                var dist = document.getElementById("district").value;
                var teh = document.getElementById("tehsil").value;
                var u = document.getElementById("uc").value;
                var chl=document.getElementById("province").value;
            $.ajax({
                type: "POST",
                url: "display_record.php",
                data: {provinceId:chl, divi_id: divsion, dist_id: dist, teh_id: teh, uc_id: u},
                dataType: 'html',
                beforeSend: function () {

                    document.getElementById("data_div").innerHTML = "";
                    $('#div_loader').show();
                },
                success: function (data)
                {
                    $('#data_div').html(data);
                },
                complete: function () {
                    $('#div_loader').hide();
                }
            });
        }
        $(function () {
//            $('#level').change(function () {
//                officeType($(this).val());
            //});
           
            $('#province').change(function () {
                
                var divsion = document.getElementById("division").value;
                var dist = document.getElementById("district").value;
                var teh = document.getElementById("tehsil").value;
                var u = document.getElementById("uc").value;
                var chl=document.getElementById("province").value;
                alert("popup division value "+divsion);
               
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {provinceId: $(this).val(), divi_id: divsion, dist_id: dist, teh_id: teh, uc_id: u},
                    dataType: 'html',
                    beforeSend: function () {

                        document.getElementById("data_div").innerHTML = "";
                        $('#div_loader').show();
                    },
                    success: function (data)
                    {
                        $('#division').html(data);
                    },
                    complete: function () {
                        $('#div_loader').hide();
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "ajax_dist.php",
                    data: {provinceId: $(this).val(), divi_id: divsion, dist_id: dist, teh_id: teh, uc_id: u},
                    dataType: 'html',
                    beforeSend: function () {

                        document.getElementById("data_div").innerHTML = "";
                        $('#div_loader').show();
                    },
                    success: function (data)
                    {
                        $('#district').html(data);
                    },
                    complete: function () {
                        $('#div_loader').hide();
                    }
                });
$.ajax({
                    type: "POST",
                    url: "ajax_teh.php",
                    data: {provinceId: $(this).val(), divi_id: divsion, dist_id: dist, teh_id: teh, uc_id: u},
                    dataType: 'html',
                    beforeSend: function () {

                        document.getElementById("data_div").innerHTML = "";
                        $('#div_loader').show();
                    },
                    success: function (data)
                    {
                        $('#tehsil').html(data);
                    },
                    complete: function () {
                        $('#div_loader').hide();
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "ajax_uc.php",
                    data: {provinceId: $(this).val(), divi_id: divsion, dist_id: dist, teh_id: teh, uc_id: u},
                    dataType: 'html',
                    beforeSend: function () {

                        document.getElementById("data_div").innerHTML = "";
                        $('#div_loader').show();
                    },
                    success: function (data)
                    {
                        $('#uc').html(data);
                    },
                    complete: function () {
                        $('#div_loader').hide();
                    }
                });

            });
            
             $('#district').change(function () {
                
                var dist = document.getElementById("district").value;
                var teh = document.getElementById("tehsil").value;
                var u = document.getElementById("uc").value;
                var chl=document.getElementById("province").value;
                alert("popup division value "+dist);
               
                $.ajax({
                    type: "POST",
                    url: "ajax_teh.php",
                    data: {provinceId: chl, dist_id: dist, teh_id: teh, uc_id: u},
                    dataType: 'html',
                    beforeSend: function () {

                        document.getElementById("data_div").innerHTML = "";
                        $('#div_loader').show();
                    },
                    success: function (data)
                    {
                        $('#tehsil').html(data);
                    },
                    complete: function () {
                        $('#div_loader').hide();
                    }
                });
             });
             $('#tehsil').change(function () {
                
                var dist = document.getElementById("district").value;
                var teh = document.getElementById("tehsil").value;
                var u = document.getElementById("uc").value;
                var chl=document.getElementById("province").value;
                alert("popup division value "+dist);
               
                $.ajax({
                    type: "POST",
                    url: "ajax_uc.php",
                    data: {provinceId: chl, dist_id: dist, teh_id: teh, uc_id: u},
                    dataType: 'html',
                    beforeSend: function () {

                        document.getElementById("data_div").innerHTML = "";
                        $('#div_loader').show();
                    },
                    success: function (data)
                    {
                        $('#uc').html(data);
                    },
                    complete: function () {
                        $('#div_loader').hide();
                    }
                });
             });
//
//            $('#division').change(function () {
//                
//                var divsion = document.getElementById("division").value;
//                var dist = document.getElementById("district").value;
//                var teh = document.getElementById("tehsil").value;
//                var u = document.getElementById("uc").value;
//                $.ajax({
//                    type: "POST",
//                    url: "ajax.php",
//                    data: {provinceId: document.getElementById("province").value, divi_id: divsion, dist_id: dist, teh_id: teh, uc_id: u},
//                    dataType: 'html',
//                    beforeSend: function () {
//
//                        document.getElementById("data_div").innerHTML = "";
//                        $('#div_loader').show();
//                    },
//                    success: function (data)
//                    {
//                        $('#district').html(data);
//                    },
//                    complete: function () {
//                        $('#div_loader').hide();
//                    }
//                });
//
//
//            });
//
        });
//        function officeType(officeLevel)
//        {
//            if (parseInt(officeLevel) == 1)
//            {
//                $('#province_div').hide();
//            } else if (parseInt(officeLevel) == 2)
//            {
//                $('#province_div').show();
//            } else if (parseInt(officeLevel) == 3)
//            {
//                $('#province_div').show();
//            }
//        }

    </script>
</body>
</html>