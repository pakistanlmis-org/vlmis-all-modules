$(function () {
    $('#reset').click(function () {
        window.location.href = appName + '/iadmin/manage-locations/index';
    });
    $('#records').change(function () {
        var counter = $(this).val();
        document.location.href = appName + '/iadmin/manage-locations/?counter=' + counter;
    });
    $('#location_level_add').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-locations/get-location-types",
            data: {geo_level_id: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#location_type_id').html(data);
            }
        });
    });


    $('#search').click(function () {
        $(".reload").trigger("click");
        $(this).button('loading');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-locations/ajax-get-table",
            data: $('#locations').serialize(),
            dataType: 'html',
            success: function (data) {
                $('#search').button('reset');
                $('#ajax-table').html(data);
                $('#sample_2').dataTable({
                    "aoColumnDefs": [
                        {"sType": 'date-uk', "aTargets": [0]},
                        {"bSortable": false, "aTargets": [-1]}
                        /*{
                         "aTargets": [-1],
                         "bVisible": false
                         }*/
                    ],
                    "aaSorting": [[0, 'asc']],
                    "aLengthMenu": [
                        [5, 15, 20, -1],
                        [5, 15, 20, "All"] // change per page values here
                    ],
                    "sDom": "<'row'<'col-md-4 col-sm-12'l><'col-md-4 col-sm-12'T><'col-md-4 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                    //"sDom ": 'T<"clear ">lfrtip',
                    // set the initial value
                    "bDestroy": true,
                    "iDisplayLength": 10,
                    "oTableTools": {
                        "sSwfPath": appName + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf",
                        "aButtons": [{
                                "sExtends": "xls",
                                "sButtonText": "<img src=" + appName + "/images/excel-32.png width=24> Export to Excel",
                                "mColumns": "sortable"
                            }, {
                                "sExtends": "copy",
                                "sButtonText": "<img src=" + appName + "/images/copy.png width=24> Copy Data",
                                "mColumns": "sortable"
                            }]
                    }
                });
                jQuery('#sample_2_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
                jQuery('#sample_2_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
                jQuery('#sample_2_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

                $('#sample_2_column_toggler input[type="checkbox"]').change(function () {
                    /* Get the DataTables object again - this is not a recreation, just a get of the object */
                    var iCol = parseInt($(this).attr("data-column"));
                    var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                    oTable.fnSetColumnVis(iCol, (bVis ? false : true));
                });
                $('#sample_2').on('click', '.update-locations', function () {

                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/ajax-edit",
                        data: {location_id: $(this).attr('itemid')},
                        dataType: 'html',
                        success: function (data) {
                            $('#modal-body-contents').html(data);
                            $('#update-button').show();
                            //   alert($('#location_type').val());
                            //  $('#location_level_edit').val($('#location_type').val());
                            setTimeout(function () {
                                $('#location_level_edit').val($('#location_type').val());
                                if ($('#location_level_edit').val() != "") {

                                    $('#loader_edit').show();
                                    $('#combo1_edit').empty();
                                    $('#combo2_edit').empty();
                                    $('#div_combo1_edit').hide();
                                    $('#div_combo2_edit').hide();
                                    $('#div_combo3_edit').hide();
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-one",
                                        data: {office: $('#location_level_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader_edit').hide();
                                            var val1 = $('#location_level_edit').val();
                                            var role_id = $('#hdn_role_id').val();
                                            if (role_id == 38) {
                                                switch (val1) {

                                                    case '3':
                                                        //  $('#lblcombo1').text('Province');
                                                        //  $('#div_combo1').show();
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '4':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '5':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '6':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                }
                                            }
                                            if (role_id == 39) {
                                                switch (val1) {

                                                    case '3':
                                                        //  $('#lblcombo1').text('Province');
                                                        //  $('#div_combo1').show();
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '4':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '5':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '6':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                }
                                            } else {
                                                switch (val1) {

                                                    case '3':
                                                        $('#lblcombo1_edit').text('Province');
                                                        $('#div_combo1_edit').show();
                                                        $('#combo1_edit').html(data);
                                                        $('#combo1_edit').val($('#province_id_edit').val());
                                                        break;
                                                    case '4':
                                                        $('#lblcombo1_edit').text('Province');
                                                        $('#div_combo1_edit').show();
                                                        $('#combo1_edit').html(data);
                                                        $('#combo1_edit').val($('#province_id_edit').val());
                                                        break;
                                                    case '5':
                                                        $('#lblcombo1_edit').text('Province');
                                                        $('#div_combo1_edit').show();
                                                        $('#combo1_edit').html(data);
                                                        $('#combo1_edit').val($('#province_id_edit').val());
                                                        break;
                                                    case '6':
                                                        $('#lblcombo1_edit').text('Province');
                                                        $('#div_combo1_edit').show();
                                                        $('#combo1_edit').html(data);
                                                        $('#combo1_edit').val($('#province_id_edit').val());
                                                        break;
                                                }
                                            }
                                        }
                                    });
                                }
                                var role_id = $('#hdn_role_id').val();
                                if (role_id == 38) {
                                    $('#loader_edit').show();
                                    $('#combo2_edit').empty();
                                    $('#warehouse').empty();
                                    $('#div_combo2_edit').hide();
                                    $('#wh_combo').hide();
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-two",
                                        data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader_edit').hide();
                                            var val = $('#location_level_edit').val();
                                            switch (val)
                                            {
                                                case '5':
                                                    $('#div_combo2_edit').show();
                                                    $('#combo2_edit').html(data);
                                                    $('#combo2_edit').val($('#district_id_edit').val());
                                                    break;
                                                case '6':
                                                    $('#div_combo2_edit').show();
                                                    $('#combo2_edit').html(data);
                                                    $('#combo2_edit').val($('#district_id_edit').val());
                                                    break;
                                            }
                                        }
                                    });
                                }
                                if (role_id == 39) {
                                    $('#loader_edit').show();
                                    $('#combo2_edit').empty();
                                    $('#warehouse').empty();
                                    $('#div_combo2_edit').hide();
                                    $('#wh_combo').hide();
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-two",
                                        data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader_edit').hide();
                                            var val = $('#location_level_edit').val();
                                            switch (val)
                                            {
                                                case '5':
                                                    $('#div_combo2_edit').show();
                                                    $('#combo2_edit').html(data);
                                                    $('#combo2_edit').val($('#district_id_edit').val());
                                                    break;
                                                case '6':
                                                    $('#div_combo2_edit').show();
                                                    $('#combo2_edit').html(data);
                                                    $('#combo2_edit').val($('#district_id_edit').val());
                                                    break;
                                            }
                                        }
                                    });
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-three",
                                        data: {combo2: $('#district_id_edit').val(), office: $('#location_level_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader_edit').hide();
                                            var val = $('#location_level_edit').val();
                                            switch (val)
                                            {
                                                case '6':
                                                    $('#div_combo3_edit').show();
                                                    $('#combo3_edit').html(data);
                                                    $('#combo3_edit').val($('#parent_id_edit').val());
                                                    break;
                                            }
                                        }
                                    });
                                }
                                if ($('#combo1_edit').val() != "") {

                                    $('#loader_edit').show();
                                    $('#combo2_edit').empty();
                                    $('#div_combo2_edit').hide();
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-two",
                                        data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader_edit').hide();
                                            var val = $('#location_level_edit').val();
                                            switch (val)
                                            {
                                                case '5':
                                                    $('#div_combo2_edit').show();
                                                    $('#combo2_edit').html(data);
                                                    $('#combo2_edit').val($('#district_id_edit').val());
                                                    break;
                                                case '6':
                                                    $('#div_combo2_edit').show();
                                                    $('#combo2_edit').html(data);
                                                    $('#combo2_edit').val($('#district_id_edit').val());
                                                    break;
                                            }
                                        }
                                    });
                                }

                                if ($('#combo2_edit').val() != "") {
                                    $('#loader_edit').show();
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-three",
                                        data: {combo2: $('#district_id_edit').val(), office: $('#location_level_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader_edit').hide();
                                            var val = $('#location_level_edit').val();
                                            switch (val)
                                            {
                                                case '6':
                                                    $('#div_combo3_edit').show();
                                                    $('#combo3_edit').html(data);
                                                    $('#combo3_edit').val($('#parent_id_edit').val());
                                                    break;
                                            }
                                        }
                                    });
                                }

// validate signup form on keyup and submit
                                if ($('#location_level_edit').val() != "") {
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/iadmin/manage-locations/get-location-types",
                                        data: {geo_level_id: $('#location_level_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#location_type_id_update').html(data);
                                            $('#location_type_id_update').val($('#location_type_id_update_hidden').val());
                                        }
                                    });
                                }

                                $('#location_level_edit').change(function () {

                                    $('#combo1_edit').empty();
                                    $('#combo2_edit').empty();
                                    $('#div_combo1_edit').hide();
                                    $('#div_combo2_edit').hide();
                                    $('#div_combo3_edit').hide();
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-one",
                                        data: {office: $(this).val()},
                                        dataType: 'html',
                                        success: function (data) {


                                            var val1 = $('#location_level_add').val();
                                            var role_id = $('#hdn_role_id').val();
                                            if (role_id == 38) {
                                                switch (val1) {

                                                    case '3':
                                                        //  $('#lblcombo1').text('Province');
                                                        //  $('#div_combo1').show();
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '4':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '5':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '6':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                }
                                            } else if (role_id == 39) {
                                                switch (val1) {

                                                    case '3':
                                                        //  $('#lblcombo1').text('Province');
                                                        //  $('#div_combo1').show();
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '4':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '5':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                    case '6':
                                                        $('#combo1_edit').val(data);
                                                        break;
                                                }
                                            } else {
                                                switch (val1) {

                                                    case '3':
                                                        $('#lblcombo1_edit').text('Province');
                                                        $('#div_combo1_edit').show();
                                                        $('#combo1_edit').html(data);
                                                        break;
                                                    case '4':
                                                        $('#lblcombo1_edit').text('Province');
                                                        $('#div_combo1_edit').show();
                                                        $('#combo1_edit').html(data);
                                                        break;
                                                    case '5':
                                                        $('#lblcombo1_edit').text('Province');
                                                        $('#div_combo1_edit').show();
                                                        $('#combo1_edit').html(data);
                                                        break;
                                                    case '6':
                                                        $('#lblcombo1_edit').text('Province');
                                                        $('#div_combo1_edit').show();
                                                        $('#combo1_edit').html(data);
                                                        break;
                                                }
                                            }
                                        }
                                    });
                                    var role_id = $('#hdn_role_id').val();
                                    if (role_id == 38) {
                                        $('#loader_edit').show();
                                        $('#combo2_edit').empty();
                                        $('#warehouse_edit').empty();
                                        $('#div_combo2_edit').hide();
                                        $('#wh_combo_edit').hide();
                                        $.ajax({
                                            type: "POST",
                                            url: appName + "/index/locations-combos-two",
                                            data: {combo1: $('#combo1_edit').val(), office: $('#location_level_edit').val()},
                                            dataType: 'html',
                                            success: function (data) {
                                                $('#loader_edit').hide();
                                                var val = $('#location_level_edit').val();
                                                switch (val)
                                                {
                                                    case '5':
                                                        $('#div_combo2_edit').show();
                                                        $('#combo2_edit').val(data);
                                                        $('#combo1_edit').val($('#combo1_edit').val());
                                                        break;
                                                    case '6':
                                                        $('#div_combo2_edit').show();
                                                        $('#combo2_edit').val(data);
                                                        $('#combo1_edit').val($('#combo1_edit').val());
                                                        break;
                                                }
                                            }
                                        });
                                    }
                                    if (role_id == 39) {
                                        $('#loader_edit').show();
                                        $('#combo2_edit').empty();
                                        $('#warehouse_edit').empty();
                                        $('#div_combo2_edit').hide();
                                        $('#wh_combo_edit').hide();
                                        $.ajax({
                                            type: "POST",
                                            url: appName + "/index/locations-combos-two",
                                            data: {combo1: $('#combo1_edit').val(), office: $('#location_level_edit').val()},
                                            dataType: 'html',
                                            success: function (data) {
                                                $('#loader_edit').hide();
                                                var val = $('#location_level_edit').val();
                                                switch (val)
                                                {
                                                    case '5':
                                                        $('#div_combo2_edit').show();
                                                        $('#combo2_edit').val(data);
                                                        $('#combo1_edit').val($('#combo1_edit').val());
                                                        break;
                                                    case '6':
                                                        $('#div_combo2_edit').show();
                                                        $('#combo2_edit').val(data);
                                                        $('#combo1_edit').val($('#combo1_edit').val());
                                                        break;
                                                }
                                            }
                                        });
                                        $.ajax({
                                            type: "POST",
                                            url: appName + "/index/locations-combos-three",
                                            data: {combo2: $('#combo2').val(), office: $('#location_level_edit').val()},
                                            dataType: 'html',
                                            success: function (data) {
                                                var val = $('#location_level_edit').val();
                                                switch (val)
                                                {
                                                    case '6':
                                                        $('#div_combo3_edit').show();
                                                        $('#combo3_edit').html(data);
                                                }
                                            }
                                        });
                                    }
                                });
                                $('#combo1_edit').change(function () {

                                    $('#combo2_edit').empty();
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-two",
                                        data: {combo1: $(this).val(), office: $('#location_level_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#div_combo2_edit').show();
                                            $('#combo2_edit').html(data);
                                        }
                                    });
                                });
                                $('#combo2_edit').change(function () {

                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-three",
                                        data: {combo2: $(this).val(), office: $('#location_level_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            var val = $('#location_level_edit').val();
                                            switch (val)
                                            {
                                                case '6':
                                                    $('#div_combo3_edit').show();
                                                    $('#combo3_edit').html(data);
                                            }
                                        }
                                    });
                                });
                                $('#combo3_edit').change(function () {

                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-four",
                                        data: {combo3: $(this).val(), office: $('#location_level_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {

                                            $('#div_combo4_edit').show();
                                            $('#combo4_edit').html(data);
                                        }
                                    });
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/iadmin/manage-users/get-default-warehouse-by-level",
                                        data: {
                                            combo3: $(this).val(),
                                            office: $('#location_level_edit').val(),
                                            geo_level_id: function () {
                                                return 5;
                                            },
                                            province_id: function () {
                                                return $("#combo1_edit").val();
                                            },
                                            district_id: function () {
                                                return $("#combo2_edit").val();
                                            },
                                            tehsil_id: function () {
                                                return $("#combo3_edit").val();
                                            }
                                        },
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#default_warehouse_update').html(data);
                                        }
                                    });
                                });
                            }, 100);
                        }

                    });
                });
              $(document).on("click", "a.active", function () {
                    var id = $(this).attr('id');
                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/delete",
                        data: {warehouse_id: id, status: 1, ajaxaction: 'deactive'},
                        dataType: 'html',
                        success: function (data) {
                              
                             $('#s_'+ id).html("In-Active");
                            //$('#s_' + id).html('Active');
                            $('#' + id).html(data);
                            $('#' + id).removeClass("active");
                            $('#' + id).addClass("deactivate");
                        }
                    });
                });

                $(document).on("click", "a.deactivate", function () {
                    var id = $(this).attr('id');
                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/delete",
                        data: {warehouse_id: id, status: 0, ajaxaction: 'active'},
                        dataType: 'html',
                        success: function (data) {
                              
                             $('#s_'+ id).html("Active");
                            //$('#s_' + id).html('In-Active');
                            $('#' + id).html(data);
                            $('#' + id).removeClass("deactivate");
                            $('#' + id).addClass("active");
                        }
                    });
                });
            }
        });
    });
    $("#update-locations").validate({
        rules: {
            location_name_update: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-locations/check-location-update",
                    type: "post",
                    data: {
                        district: function () {
                            return $("#combo2_edit").val();
                        },
                        province: function () {
                            return $("#combo1_edit").val();
                        },
                        locLvl: function () {
                            return $("#location_level_edit").val();
                        },
                        loc_Id: function () {
                            return $("#location_id").val();
                        },
                        locid: function () {
                            return $("#location_level_edit").val();
                        }

                    }
                }
            },
            combo1_edit: {
                required: true
            },
            combo2_edit: {
                required: true
            },
            combo3_edit: {
                required: true
            },
            population_update: {
                required: true,
                number:true,
                min:0
            },
            ccm_location_id_update: {
                required: false,
                number: true,
                remote: {
                    url: appName + "/iadmin/manage-locations/check-ccm-location-update",
                    type: "post",
                    data: {
                        district: function () {
                            return $("#combo2_edit").val();
                        },
                        province: function () {
                            return $("#combo1_edit").val();
                        },
                        locLvl: function () {
                            return $("#location_level_edit").val();
                        },
                        locid: function () {
                            return $("#combo3_edit").val();
                        },
                        loc_Id: function () {
                            return $("#location_id").val();
                        },
                        location_name_update: function () {
                            return $("#location_name_update").val();
                        }

                    }
                }

            }


        },
        messages: {
            location_name_update: {
                remote: "Location is Already Available.",
                required: "Please enter Location"
            },
            ccm_location_id_update: {
                remote: "Ccm Location Id is Already Available.",
                //required: "Please enter Ccm Location Id"

            }
        },
        submitHandler: function (form) {
            $(".reload").trigger("click");
            $('#update').button('loading');
            $('#location_name_edit_hdn').val($('#location_name_update').val());
            $('#action_hdn').val('2');
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-locations/ajax-edit-location",
                data: $('#update-locations').serialize(),
                dataType: 'html',
                success: function (data) {
                    $('#update').button('reset');
                    $(".close").trigger("click");

                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/ajax-get-table",
                        data: $('#locations').serialize(),
                        dataType: 'html',
                        success: function (data) {
                            $('#search').button('reset');
                            $('#ajax-table').html(data);
                            $('#sample_2').dataTable({
                                "aoColumnDefs": [
                                    {"sType": 'date-uk', "aTargets": [0]},
                                    {"bSortable": false, "aTargets": [-1]}
                                    /*{
                                     "aTargets": [-1],
                                     "bVisible": false
                                     }*/
                                ],
                                "aaSorting": [[0, 'asc']],
                                "aLengthMenu": [
                                    [5, 15, 20, -1],
                                    [5, 15, 20, "All"] // change per page values here
                                ],
                                "sDom": "<'row'<'col-md-4 col-sm-12'l><'col-md-4 col-sm-12'T><'col-md-4 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                                //"sDom ": 'T<"clear ">lfrtip',
                                // set the initial value
                                "bDestroy": true,
                                "iDisplayLength": 10,
                                "oTableTools": {
                                    "sSwfPath": appName + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf",
                                    "aButtons": [{
                                            "sExtends": "xls",
                                            "sButtonText": "<img src=" + appName + "/images/excel-32.png width=24> Export to Excel",
                                            "mColumns": "sortable"
                                        }, {
                                            "sExtends": "copy",
                                            "sButtonText": "<img src=" + appName + "/images/copy.png width=24> Copy Data",
                                            "mColumns": "sortable"
                                        }]
                                }
                            });
                            jQuery('#sample_2_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
                            jQuery('#sample_2_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
                            jQuery('#sample_2_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

                            $('#sample_2_column_toggler input[type="checkbox"]').change(function () {
                                /* Get the DataTables object again - this is not a recreation, just a get of the object */
                                var iCol = parseInt($(this).attr("data-column"));
                                var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                                oTable.fnSetColumnVis(iCol, (bVis ? false : true));
                            });
                            $('#sample_2').on('click', '.update-locations', function () {

                                $.ajax({
                                    type: "POST",
                                    url: appName + "/iadmin/manage-locations/ajax-edit",
                                    data: {location_id: $(this).attr('itemid')},
                                    dataType: 'html',
                                    success: function (data) {
                                        $('#modal-body-contents').html(data);
                                        $('#update-button').show();
                                        //   alert($('#location_type').val());
                                        //  $('#location_level_edit').val($('#location_type').val());
                                        setTimeout(function () {
                                            $('#location_level_edit').val($('#location_type').val());
                                            if ($('#location_level_edit').val() != "") {

                                                $('#loader_edit').show();
                                                $('#combo1_edit').empty();
                                                $('#combo2_edit').empty();
                                                $('#div_combo1_edit').hide();
                                                $('#div_combo2_edit').hide();
                                                $('#div_combo3_edit').hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-one",
                                                    data: {office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val1 = $('#location_level_edit').val();
                                                        var role_id = $('#hdn_role_id').val();
                                                        if (role_id == 38) {
                                                            switch (val1) {

                                                                case '3':
                                                                    //  $('#lblcombo1').text('Province');
                                                                    //  $('#div_combo1').show();
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '4':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '5':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '6':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                            }
                                                        }
                                                        if (role_id == 39) {
                                                            switch (val1) {

                                                                case '3':
                                                                    //  $('#lblcombo1').text('Province');
                                                                    //  $('#div_combo1').show();
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '4':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '5':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '6':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                            }
                                                        } else {
                                                            switch (val1) {

                                                                case '3':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    $('#combo1_edit').val($('#province_id_edit').val());
                                                                    break;
                                                                case '4':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    $('#combo1_edit').val($('#province_id_edit').val());
                                                                    break;
                                                                case '5':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    $('#combo1_edit').val($('#province_id_edit').val());
                                                                    break;
                                                                case '6':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    $('#combo1_edit').val($('#province_id_edit').val());
                                                                    break;
                                                            }
                                                        }
                                                    }
                                                });
                                            }
                                            var role_id = $('#hdn_role_id').val();
                                            if (role_id == 38) {
                                                $('#loader_edit').show();
                                                $('#combo2_edit').empty();
                                                $('#warehouse').empty();
                                                $('#div_combo2_edit').hide();
                                                $('#wh_combo').hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '5':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                            case '6':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                        }
                                                    }
                                                });
                                            }
                                            if (role_id == 39) {
                                                $('#loader_edit').show();
                                                $('#combo2_edit').empty();
                                                $('#warehouse').empty();
                                                $('#div_combo2_edit').hide();
                                                $('#wh_combo').hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '5':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                            case '6':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                        }
                                                    }
                                                });
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-three",
                                                    data: {combo2: $('#district_id_edit').val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '6':
                                                                $('#div_combo3_edit').show();
                                                                $('#combo3_edit').html(data);
                                                                $('#combo3_edit').val($('#parent_id_edit').val());
                                                                break;
                                                        }
                                                    }
                                                });
                                            }
                                            if ($('#combo1_edit').val() != "") {

                                                $('#loader_edit').show();
                                                $('#combo2_edit').empty();
                                                $('#div_combo2_edit').hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '5':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                            case '6':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                        }
                                                    }
                                                });
                                            }

                                            if ($('#combo2_edit').val() != "") {
                                                $('#loader_edit').show();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-three",
                                                    data: {combo2: $('#district_id_edit').val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '6':
                                                                $('#div_combo3_edit').show();
                                                                $('#combo3_edit').html(data);
                                                                $('#combo3_edit').val($('#parent_id_edit').val());
                                                                break;
                                                        }
                                                    }
                                                });
                                            }

// validate signup form on keyup and submit
                                            if ($('#location_level_edit').val() != "") {
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-locations/get-location-types",
                                                    data: {geo_level_id: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#location_type_id_update').html(data);
                                                        $('#location_type_id_update').val($('#location_type_id_update_hidden').val());
                                                    }
                                                });
                                            }

                                            $('#location_level_edit').change(function () {

                                                $('#combo1_edit').empty();
                                                $('#combo2_edit').empty();
                                                $('#div_combo1_edit').hide();
                                                $('#div_combo2_edit').hide();
                                                $('#div_combo3_edit').hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-one",
                                                    data: {office: $(this).val()},
                                                    dataType: 'html',
                                                    success: function (data) {


                                                        var val1 = $('#location_level_add').val();
                                                        var role_id = $('#hdn_role_id').val();
                                                        if (role_id == 38) {
                                                            switch (val1) {

                                                                case '3':
                                                                    //  $('#lblcombo1').text('Province');
                                                                    //  $('#div_combo1').show();
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '4':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '5':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '6':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                            }
                                                        } else if (role_id == 39) {
                                                            switch (val1) {

                                                                case '3':
                                                                    //  $('#lblcombo1').text('Province');
                                                                    //  $('#div_combo1').show();
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '4':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '5':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '6':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                            }
                                                        } else {
                                                            switch (val1) {

                                                                case '3':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    break;
                                                                case '4':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    break;
                                                                case '5':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    break;
                                                                case '6':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    break;
                                                            }
                                                        }
                                                    }
                                                });
                                                var role_id = $('#hdn_role_id').val();
                                                if (role_id == 38) {
                                                    $('#loader_edit').show();
                                                    $('#combo2_edit').empty();
                                                    $('#warehouse_edit').empty();
                                                    $('#div_combo2_edit').hide();
                                                    $('#wh_combo_edit').hide();
                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-two",
                                                        data: {combo1: $('#combo1_edit').val(), office: $('#location_level_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#loader_edit').hide();
                                                            var val = $('#location_level_edit').val();
                                                            switch (val)
                                                            {
                                                                case '5':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    $('#combo1_edit').val($('#combo1_edit').val());
                                                                    break;
                                                                case '6':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    $('#combo1_edit').val($('#combo1_edit').val());
                                                                    break;
                                                            }
                                                        }
                                                    });
                                                }
                                                if (role_id == 39) {
                                                    $('#loader_edit').show();
                                                    $('#combo2_edit').empty();
                                                    $('#warehouse_edit').empty();
                                                    $('#div_combo2_edit').hide();
                                                    $('#wh_combo_edit').hide();
                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-two",
                                                        data: {combo1: $('#combo1_edit').val(), office: $('#location_level_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#loader_edit').hide();
                                                            var val = $('#location_level_edit').val();
                                                            switch (val)
                                                            {
                                                                case '5':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    $('#combo1_edit').val($('#combo1_edit').val());
                                                                    break;
                                                                case '6':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    $('#combo1_edit').val($('#combo1_edit').val());
                                                                    break;
                                                            }
                                                        }
                                                    });
                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-three",
                                                        data: {combo2: $('#combo2').val(), office: $('#location_level_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            var val = $('#location_level_edit').val();
                                                            switch (val)
                                                            {
                                                                case '6':
                                                                    $('#div_combo3_edit').show();
                                                                    $('#combo3_edit').html(data);
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                            $('#combo1_edit').change(function () {

                                                $('#combo2_edit').empty();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $(this).val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#div_combo2_edit').show();
                                                        $('#combo2_edit').html(data);
                                                    }
                                                });
                                            });
                                            $('#combo2_edit').change(function () {

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-three",
                                                    data: {combo2: $(this).val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '6':
                                                                $('#div_combo3_edit').show();
                                                                $('#combo3_edit').html(data);
                                                        }
                                                    }
                                                });
                                            });
                                            $('#combo3_edit').change(function () {

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-four",
                                                    data: {combo3: $(this).val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {

                                                        $('#div_combo4_edit').show();
                                                        $('#combo4_edit').html(data);
                                                    }
                                                });
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-users/get-default-warehouse-by-level",
                                                    data: {
                                                        combo3: $(this).val(),
                                                        office: $('#location_level_edit').val(),
                                                        geo_level_id: function () {
                                                            return 5;
                                                        },
                                                        province_id: function () {
                                                            return $("#combo1_edit").val();
                                                        },
                                                        district_id: function () {
                                                            return $("#combo2_edit").val();
                                                        },
                                                        tehsil_id: function () {
                                                            return $("#combo3_edit").val();
                                                        }
                                                    },
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#default_warehouse_update').html(data);
                                                    }
                                                });
                                            });
                                        }, 100);
                                    }

                                });
                            });
                            $(document).on("click", "a.active", function () {
                    var id = $(this).attr('id');
                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/delete",
                        data: {warehouse_id: id, status: 1, ajaxaction: 'deactive'},
                        dataType: 'html',
                        success: function (data) {
                              
                             $('#s_'+ id).html("In-Active");
                            //$('#s_' + id).html('Active');
                            $('#' + id).html(data);
                            $('#' + id).removeClass("active");
                            $('#' + id).addClass("deactivate");
                        }
                    });
                });

                $(document).on("click", "a.deactivate", function () {
                    var id = $(this).attr('id');
                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/delete",
                        data: {warehouse_id: id, status: 0, ajaxaction: 'active'},
                        dataType: 'html',
                        success: function (data) {
                              
                             $('#s_'+ id).html("Active");
                            //$('#s_' + id).html('In-Active');
                            $('#' + id).html(data);
                            $('#' + id).removeClass("deactivate");
                            $('#' + id).addClass("active");
                        }
                    });
                });
                        }
                    });

                }
            });
        }

    });
    $("#locations-add").validate({
        rules: {
            location_name_add: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-locations/check-location",
                    type: "post",
                    data: {
                        district: function () {
                            return $("#combo2_add").val();
                        },
                        province: function () {
                            return $("#combo1_add").val();
                        },
                        locLvl: function () {
                            return $("#location_level_add").val();
                        },
                        locid: function () {
                            return $("#combo3_add").val();
                        }

                    }
                }
            },
            combo1_add: {
                required: true
            },
            location_level_add: {
                required: true
            },
            combo2_add: {
                required: true
            },
            combo3_add: {
                required: true
            },
            location_type_id: {
                required: true

            }, 
            population: {
                required: true,
                number:true,
                min:0
            },
            ccm_location_id: {
                required: false,
                number: true,
                remote: {
                    url: appName + "/iadmin/manage-locations/check-ccm-location",
                    type: "post",
                    data: {
                        district: function () {
                            return $("#combo2_add").val();
                        },
                        province: function () {
                            return $("#combo1_add").val();
                        },
                        locLvl: function () {
                            return $("#location_level_add").val();
                        },
                        locid: function () {
                            return $("#combo3_add").val();
                        }

                    }
                }

            }


        },
        messages: {
            location_name_add: {
                remote: "Location is Already Available.",
                required: "Please enter Location"
            },
            ccm_location_id: {
                remote: "Ccm Location Id is Already Available.",
                required: "Please enter Ccm Location Id"

            }
        },
        submitHandler: function (form) {
            $(".reload").trigger("click");
            $('#add').button('loading');
            $('#location_name_hdn').val($('#location_name_add').val());
            $('#action_hdn').val('1');
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-locations/ajax-add-location",
                data: $('#locations-add').serialize(),
                dataType: 'html',
                success: function (data) {
                    $('#add').button('reset');
                    $(".close").trigger("click");
                     $('#locations-add')[0].reset();
                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/ajax-get-table",
                        data: $('#locations').serialize(),
                        dataType: 'html',
                        success: function (data) {
                            $('#search').button('reset');
                            $('#ajax-table').html(data);
                            $('#sample_2').dataTable({
                                "aoColumnDefs": [
                                    {"sType": 'date-uk', "aTargets": [0]},
                                    {"bSortable": false, "aTargets": [-1]}
                                    /*{
                                     "aTargets": [-1],
                                     "bVisible": false
                                     }*/
                                ],
                                "aaSorting": [[0, 'asc']],
                                "aLengthMenu": [
                                    [5, 15, 20, -1],
                                    [5, 15, 20, "All"] // change per page values here
                                ],
                                "sDom": "<'row'<'col-md-4 col-sm-12'l><'col-md-4 col-sm-12'T><'col-md-4 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                                //"sDom ": 'T<"clear ">lfrtip',
                                // set the initial value
                                "bDestroy": true,
                                "iDisplayLength": 10,
                                "oTableTools": {
                                    "sSwfPath": appName + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf",
                                    "aButtons": [{
                                            "sExtends": "xls",
                                            "sButtonText": "<img src=" + appName + "/images/excel-32.png width=24> Export to Excel",
                                            "mColumns": "sortable"
                                        }, {
                                            "sExtends": "copy",
                                            "sButtonText": "<img src=" + appName + "/images/copy.png width=24> Copy Data",
                                            "mColumns": "sortable"
                                        }]
                                }
                            });
                            jQuery('#sample_2_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
                            jQuery('#sample_2_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
                            jQuery('#sample_2_wrapper .dataTables_length select').select2(); // initialize select2 dropdown

                            $('#sample_2_column_toggler input[type="checkbox"]').change(function () {
                                /* Get the DataTables object again - this is not a recreation, just a get of the object */
                                var iCol = parseInt($(this).attr("data-column"));
                                var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                                oTable.fnSetColumnVis(iCol, (bVis ? false : true));
                            });
                            $('#sample_2').on('click', '.update-locations', function () {

                                $.ajax({
                                    type: "POST",
                                    url: appName + "/iadmin/manage-locations/ajax-edit",
                                    data: {location_id: $(this).attr('itemid')},
                                    dataType: 'html',
                                    success: function (data) {
                                        $('#modal-body-contents').html(data);
                                        $('#update-button').show();
                                        //   alert($('#location_type').val());
                                        //  $('#location_level_edit').val($('#location_type').val());
                                        setTimeout(function () {
                                            $('#location_level_edit').val($('#location_type').val());
                                            if ($('#location_level_edit').val() != "") {

                                                $('#loader_edit').show();
                                                $('#combo1_edit').empty();
                                                $('#combo2_edit').empty();
                                                $('#div_combo1_edit').hide();
                                                $('#div_combo2_edit').hide();
                                                $('#div_combo3_edit').hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-one",
                                                    data: {office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val1 = $('#location_level_edit').val();
                                                        var role_id = $('#hdn_role_id').val();
                                                        if (role_id == 38) {
                                                            switch (val1) {

                                                                case '3':
                                                                    //  $('#lblcombo1').text('Province');
                                                                    //  $('#div_combo1').show();
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '4':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '5':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '6':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                            }
                                                        }
                                                        if (role_id == 39) {
                                                            switch (val1) {

                                                                case '3':
                                                                    //  $('#lblcombo1').text('Province');
                                                                    //  $('#div_combo1').show();
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '4':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '5':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '6':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                            }
                                                        } else {
                                                            switch (val1) {

                                                                case '3':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    $('#combo1_edit').val($('#province_id_edit').val());
                                                                    break;
                                                                case '4':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    $('#combo1_edit').val($('#province_id_edit').val());
                                                                    break;
                                                                case '5':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    $('#combo1_edit').val($('#province_id_edit').val());
                                                                    break;
                                                                case '6':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    $('#combo1_edit').val($('#province_id_edit').val());
                                                                    break;
                                                            }
                                                        }
                                                    }
                                                });
                                            }
                                            var role_id = $('#hdn_role_id').val();
                                            if (role_id == 38) {
                                                $('#loader_edit').show();
                                                $('#combo2_edit').empty();
                                                $('#warehouse').empty();
                                                $('#div_combo2_edit').hide();
                                                $('#wh_combo').hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '5':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                            case '6':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                        }
                                                    }
                                                });
                                            }
                                            if (role_id == 39) {
                                                $('#loader_edit').show();
                                                $('#combo2_edit').empty();
                                                $('#warehouse').empty();
                                                $('#div_combo2_edit').hide();
                                                $('#wh_combo').hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '5':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                            case '6':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                        }
                                                    }
                                                });
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-three",
                                                    data: {combo2: $('#district_id_edit').val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '6':
                                                                $('#div_combo3_edit').show();
                                                                $('#combo3_edit').html(data);
                                                                $('#combo3_edit').val($('#parent_id_edit').val());
                                                                break;
                                                        }
                                                    }
                                                });
                                            }
                                            if ($('#combo1_edit').val() != "") {

                                                $('#loader_edit').show();
                                                $('#combo2_edit').empty();
                                                $('#div_combo2_edit').hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '5':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                            case '6':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
                                                        }
                                                    }
                                                });
                                            }

                                            if ($('#combo2_edit').val() != "") {
                                                $('#loader_edit').show();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-three",
                                                    data: {combo2: $('#district_id_edit').val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader_edit').hide();
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '6':
                                                                $('#div_combo3_edit').show();
                                                                $('#combo3_edit').html(data);
                                                                $('#combo3_edit').val($('#parent_id_edit').val());
                                                                break;
                                                        }
                                                    }
                                                });
                                            }

// validate signup form on keyup and submit
                                            if ($('#location_level_edit').val() != "") {
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-locations/get-location-types",
                                                    data: {geo_level_id: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#location_type_id_update').html(data);
                                                        $('#location_type_id_update').val($('#location_type_id_update_hidden').val());
                                                    }
                                                });
                                            }

                                            $('#location_level_edit').change(function () {

                                                $('#combo1_edit').empty();
                                                $('#combo2_edit').empty();
                                                $('#div_combo1_edit').hide();
                                                $('#div_combo2_edit').hide();
                                                $('#div_combo3_edit').hide();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-one",
                                                    data: {office: $(this).val()},
                                                    dataType: 'html',
                                                    success: function (data) {


                                                        var val1 = $('#location_level_add').val();
                                                        var role_id = $('#hdn_role_id').val();
                                                        if (role_id == 38) {
                                                            switch (val1) {

                                                                case '3':
                                                                    //  $('#lblcombo1').text('Province');
                                                                    //  $('#div_combo1').show();
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '4':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '5':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '6':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                            }
                                                        } else if (role_id == 39) {
                                                            switch (val1) {

                                                                case '3':
                                                                    //  $('#lblcombo1').text('Province');
                                                                    //  $('#div_combo1').show();
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '4':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '5':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                                case '6':
                                                                    $('#combo1_edit').val(data);
                                                                    break;
                                                            }
                                                        } else {
                                                            switch (val1) {

                                                                case '3':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    break;
                                                                case '4':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    break;
                                                                case '5':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    break;
                                                                case '6':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    break;
                                                            }
                                                        }
                                                    }
                                                });
                                                var role_id = $('#hdn_role_id').val();
                                                if (role_id == 38) {
                                                    $('#loader_edit').show();
                                                    $('#combo2_edit').empty();
                                                    $('#warehouse_edit').empty();
                                                    $('#div_combo2_edit').hide();
                                                    $('#wh_combo_edit').hide();
                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-two",
                                                        data: {combo1: $('#combo1_edit').val(), office: $('#location_level_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#loader_edit').hide();
                                                            var val = $('#location_level_edit').val();
                                                            switch (val)
                                                            {
                                                                case '5':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    $('#combo1_edit').val($('#combo1_edit').val());
                                                                    break;
                                                                case '6':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    $('#combo1_edit').val($('#combo1_edit').val());
                                                                    break;
                                                            }
                                                        }
                                                    });
                                                }
                                                if (role_id == 39) {
                                                    $('#loader_edit').show();
                                                    $('#combo2_edit').empty();
                                                    $('#warehouse_edit').empty();
                                                    $('#div_combo2_edit').hide();
                                                    $('#wh_combo_edit').hide();
                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-two",
                                                        data: {combo1: $('#combo1_edit').val(), office: $('#location_level_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#loader_edit').hide();
                                                            var val = $('#location_level_edit').val();
                                                            switch (val)
                                                            {
                                                                case '5':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    $('#combo1_edit').val($('#combo1_edit').val());
                                                                    break;
                                                                case '6':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    $('#combo1_edit').val($('#combo1_edit').val());
                                                                    break;
                                                            }
                                                        }
                                                    });
                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-three",
                                                        data: {combo2: $('#combo2').val(), office: $('#location_level_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            var val = $('#location_level_edit').val();
                                                            switch (val)
                                                            {
                                                                case '6':
                                                                    $('#div_combo3_edit').show();
                                                                    $('#combo3_edit').html(data);
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                            $('#combo1_edit').change(function () {

                                                $('#combo2_edit').empty();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $(this).val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#div_combo2_edit').show();
                                                        $('#combo2_edit').html(data);
                                                    }
                                                });
                                            });
                                            $('#combo2_edit').change(function () {

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-three",
                                                    data: {combo2: $(this).val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        var val = $('#location_level_edit').val();
                                                        switch (val)
                                                        {
                                                            case '6':
                                                                $('#div_combo3_edit').show();
                                                                $('#combo3_edit').html(data);
                                                        }
                                                    }
                                                });
                                            });
                                            $('#combo3_edit').change(function () {

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-four",
                                                    data: {combo3: $(this).val(), office: $('#location_level_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {

                                                        $('#div_combo4_edit').show();
                                                        $('#combo4_edit').html(data);
                                                    }
                                                });
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-users/get-default-warehouse-by-level",
                                                    data: {
                                                        combo3: $(this).val(),
                                                        office: $('#location_level_edit').val(),
                                                        geo_level_id: function () {
                                                            return 5;
                                                        },
                                                        province_id: function () {
                                                            return $("#combo1_edit").val();
                                                        },
                                                        district_id: function () {
                                                            return $("#combo2_edit").val();
                                                        },
                                                        tehsil_id: function () {
                                                            return $("#combo3_edit").val();
                                                        }
                                                    },
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#default_warehouse_update').html(data);
                                                    }
                                                });
                                            });
                                        }, 100);
                                    }

                                });
                            });
                           $(document).on("click", "a.active", function () {
                    var id = $(this).attr('id');
                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/delete",
                        data: {warehouse_id: id, status: 1, ajaxaction: 'deactive'},
                        dataType: 'html',
                        success: function (data) {
                              
                             $('#s_'+ id).html("In-Active");
                            //$('#s_' + id).html('Active');
                            $('#' + id).html(data);
                            $('#' + id).removeClass("active");
                            $('#' + id).addClass("deactivate");
                        }
                    });
                });

                $(document).on("click", "a.deactivate", function () {
                    var id = $(this).attr('id');
                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/delete",
                        data: {warehouse_id: id, status: 0, ajaxaction: 'active'},
                        dataType: 'html',
                        success: function (data) {
                              
                             $('#s_'+ id).html("Active");
                            //$('#s_' + id).html('In-Active');
                            $('#' + id).html(data);
                            $('#' + id).removeClass("deactivate");
                            $('#' + id).addClass("active");
                        }
                    });
                });
                        }
                    });

                }
            })
        }




    });
    $('#sample_2').on('click', '.update-locations', function () {

        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-locations/ajax-edit",
            data: {location_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
                //   alert($('#location_type').val());
                //  $('#location_level_edit').val($('#location_type').val());
                setTimeout(function () {
                    $('#location_level_edit').val($('#location_type').val());
                    if ($('#location_level_edit').val() != "") {

                        $('#loader_edit').show();
                        $('#combo1_edit').empty();
                        $('#combo2_edit').empty();
                        $('#div_combo1_edit').hide();
                        $('#div_combo2_edit').hide();
                        $('#div_combo3_edit').hide();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-one",
                            data: {office: $('#location_level_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader_edit').hide();
                                var val1 = $('#location_level_edit').val();
                                var role_id = $('#hdn_role_id').val();
                                if (role_id == 38) {
                                    switch (val1) {

                                        case '3':
                                            //  $('#lblcombo1').text('Province');
                                            //  $('#div_combo1').show();
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '4':
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '5':
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '6':
                                            $('#combo1_edit').val(data);
                                            break;
                                    }
                                }
                                if (role_id == 39) {
                                    switch (val1) {

                                        case '3':
                                            //  $('#lblcombo1').text('Province');
                                            //  $('#div_combo1').show();
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '4':
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '5':
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '6':
                                            $('#combo1_edit').val(data);
                                            break;
                                    }
                                } else {
                                    switch (val1) {

                                        case '3':
                                            $('#lblcombo1_edit').text('Province');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            $('#combo1_edit').val($('#province_id_edit').val());
                                            break;
                                        case '4':
                                            $('#lblcombo1_edit').text('Province');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            $('#combo1_edit').val($('#province_id_edit').val());
                                            break;
                                        case '5':
                                            $('#lblcombo1_edit').text('Province');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            $('#combo1_edit').val($('#province_id_edit').val());
                                            break;
                                        case '6':
                                            $('#lblcombo1_edit').text('Province');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            $('#combo1_edit').val($('#province_id_edit').val());
                                            break;
                                    }
                                }
                            }
                        });
                    }
                    var role_id = $('#hdn_role_id').val();
                    if (role_id == 38) {
                        $('#loader_edit').show();
                        $('#combo2_edit').empty();
                        $('#warehouse').empty();
                        $('#div_combo2_edit').hide();
                        $('#wh_combo').hide();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-two",
                            data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader_edit').hide();
                                var val = $('#location_level_edit').val();
                                switch (val)
                                {
                                    case '5':
                                        $('#div_combo2_edit').show();
                                        $('#combo2_edit').html(data);
                                        $('#combo2_edit').val($('#district_id_edit').val());
                                        break;
                                    case '6':
                                        $('#div_combo2_edit').show();
                                        $('#combo2_edit').html(data);
                                        $('#combo2_edit').val($('#district_id_edit').val());
                                        break;
                                }
                            }
                        });
                    }
                    if (role_id == 39) {
                        $('#loader_edit').show();
                        $('#combo2_edit').empty();
                        $('#warehouse').empty();
                        $('#div_combo2_edit').hide();
                        $('#wh_combo').hide();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-two",
                            data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader_edit').hide();
                                var val = $('#location_level_edit').val();
                                switch (val)
                                {
                                    case '5':
                                        $('#div_combo2_edit').show();
                                        $('#combo2_edit').html(data);
                                        $('#combo2_edit').val($('#district_id_edit').val());
                                        break;
                                    case '6':
                                        $('#div_combo2_edit').show();
                                        $('#combo2_edit').html(data);
                                        $('#combo2_edit').val($('#district_id_edit').val());
                                        break;
                                }
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-three",
                            data: {combo2: $('#district_id_edit').val(), office: $('#location_level_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader_edit').hide();
                                var val = $('#location_level_edit').val();
                                switch (val)
                                {
                                    case '6':
                                        $('#div_combo3_edit').show();
                                        $('#combo3_edit').html(data);
                                        $('#combo3_edit').val($('#parent_id_edit').val());
                                        break;
                                }
                            }
                        });
                    }
                    if ($('#combo1_edit').val() != "") {

                        $('#loader_edit').show();
                        $('#combo2_edit').empty();
                        $('#div_combo2_edit').hide();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-two",
                            data: {combo1: $('#province_id_edit').val(), office: $('#location_level_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader_edit').hide();
                                var val = $('#location_level_edit').val();
                                switch (val)
                                {
                                    case '5':
                                        $('#div_combo2_edit').show();
                                        $('#combo2_edit').html(data);
                                        $('#combo2_edit').val($('#district_id_edit').val());
                                        break;
                                    case '6':
                                        $('#div_combo2_edit').show();
                                        $('#combo2_edit').html(data);
                                        $('#combo2_edit').val($('#district_id_edit').val());
                                        break;
                                }
                            }
                        });
                    }

                    if ($('#combo2_edit').val() != "") {
                        $('#loader_edit').show();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-three",
                            data: {combo2: $('#district_id_edit').val(), office: $('#location_level_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader_edit').hide();
                                var val = $('#location_level_edit').val();
                                switch (val)
                                {
                                    case '6':
                                        $('#div_combo3_edit').show();
                                        $('#combo3_edit').html(data);
                                        $('#combo3_edit').val($('#parent_id_edit').val());
                                        break;
                                }
                            }
                        });
                    }

// validate signup form on keyup and submit
                    if ($('#location_level_edit').val() != "") {
                        $.ajax({
                            type: "POST",
                            url: appName + "/iadmin/manage-locations/get-location-types",
                            data: {geo_level_id: $('#location_level_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#location_type_id_update').html(data);
                                $('#location_type_id_update').val($('#location_type_id_update_hidden').val());
                            }
                        });
                    }

                    $('#location_level_edit').change(function () {

                        $('#combo1_edit').empty();
                        $('#combo2_edit').empty();
                        $('#div_combo1_edit').hide();
                        $('#div_combo2_edit').hide();
                        $('#div_combo3_edit').hide();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-one",
                            data: {office: $(this).val()},
                            dataType: 'html',
                            success: function (data) {


                                var val1 = $('#location_level_add').val();
                                var role_id = $('#hdn_role_id').val();
                                if (role_id == 38) {
                                    switch (val1) {

                                        case '3':
                                            //  $('#lblcombo1').text('Province');
                                            //  $('#div_combo1').show();
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '4':
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '5':
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '6':
                                            $('#combo1_edit').val(data);
                                            break;
                                    }
                                } else if (role_id == 39) {
                                    switch (val1) {

                                        case '3':
                                            //  $('#lblcombo1').text('Province');
                                            //  $('#div_combo1').show();
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '4':
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '5':
                                            $('#combo1_edit').val(data);
                                            break;
                                        case '6':
                                            $('#combo1_edit').val(data);
                                            break;
                                    }
                                } else {
                                    switch (val1) {

                                        case '3':
                                            $('#lblcombo1_edit').text('Province');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            break;
                                        case '4':
                                            $('#lblcombo1_edit').text('Province');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            break;
                                        case '5':
                                            $('#lblcombo1_edit').text('Province');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            break;
                                        case '6':
                                            $('#lblcombo1_edit').text('Province');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            break;
                                    }
                                }
                            }
                        });
                        var role_id = $('#hdn_role_id').val();
                        if (role_id == 38) {
                            $('#loader_edit').show();
                            $('#combo2_edit').empty();
                            $('#warehouse_edit').empty();
                            $('#div_combo2_edit').hide();
                            $('#wh_combo_edit').hide();
                            $.ajax({
                                type: "POST",
                                url: appName + "/index/locations-combos-two",
                                data: {combo1: $('#combo1_edit').val(), office: $('#location_level_edit').val()},
                                dataType: 'html',
                                success: function (data) {
                                    $('#loader_edit').hide();
                                    var val = $('#location_level_edit').val();
                                    switch (val)
                                    {
                                        case '5':
                                            $('#div_combo2_edit').show();
                                            $('#combo2_edit').val(data);
                                            $('#combo1_edit').val($('#combo1_edit').val());
                                            break;
                                        case '6':
                                            $('#div_combo2_edit').show();
                                            $('#combo2_edit').val(data);
                                            $('#combo1_edit').val($('#combo1_edit').val());
                                            break;
                                    }
                                }
                            });
                        }
                        if (role_id == 39) {
                            $('#loader_edit').show();
                            $('#combo2_edit').empty();
                            $('#warehouse_edit').empty();
                            $('#div_combo2_edit').hide();
                            $('#wh_combo_edit').hide();
                            $.ajax({
                                type: "POST",
                                url: appName + "/index/locations-combos-two",
                                data: {combo1: $('#combo1_edit').val(), office: $('#location_level_edit').val()},
                                dataType: 'html',
                                success: function (data) {
                                    $('#loader_edit').hide();
                                    var val = $('#location_level_edit').val();
                                    switch (val)
                                    {
                                        case '5':
                                            $('#div_combo2_edit').show();
                                            $('#combo2_edit').val(data);
                                            $('#combo1_edit').val($('#combo1_edit').val());
                                            break;
                                        case '6':
                                            $('#div_combo2_edit').show();
                                            $('#combo2_edit').val(data);
                                            $('#combo1_edit').val($('#combo1_edit').val());
                                            break;
                                    }
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: appName + "/index/locations-combos-three",
                                data: {combo2: $('#combo2').val(), office: $('#location_level_edit').val()},
                                dataType: 'html',
                                success: function (data) {
                                    var val = $('#location_level_edit').val();
                                    switch (val)
                                    {
                                        case '6':
                                            $('#div_combo3_edit').show();
                                            $('#combo3_edit').html(data);
                                    }
                                }
                            });
                        }
                    });
                    $('#combo1_edit').change(function () {

                        $('#combo2_edit').empty();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-two",
                            data: {combo1: $(this).val(), office: $('#location_level_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#div_combo2_edit').show();
                                $('#combo2_edit').html(data);
                            }
                        });
                    });
                    $('#combo2_edit').change(function () {

                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-three",
                            data: {combo2: $(this).val(), office: $('#location_level_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                var val = $('#location_level_edit').val();
                                switch (val)
                                {
                                    case '6':
                                        $('#div_combo3_edit').show();
                                        $('#combo3_edit').html(data);
                                }
                            }
                        });
                    });
                    $('#combo3_edit').change(function () {

                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-four",
                            data: {combo3: $(this).val(), office: $('#location_level_edit').val()},
                            dataType: 'html',
                            success: function (data) {

                                $('#div_combo4_edit').show();
                                $('#combo4_edit').html(data);
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: appName + "/iadmin/manage-users/get-default-warehouse-by-level",
                            data: {
                                combo3: $(this).val(),
                                office: $('#location_level_edit').val(),
                                geo_level_id: function () {
                                    return 5;
                                },
                                province_id: function () {
                                    return $("#combo1_edit").val();
                                },
                                district_id: function () {
                                    return $("#combo2_edit").val();
                                },
                                tehsil_id: function () {
                                    return $("#combo3_edit").val();
                                }
                            },
                            dataType: 'html',
                            success: function (data) {
                                $('#default_warehouse_update').html(data);
                            }
                        });
                    });
                }, 500);
            }

        });
    });
    $(document).on("click", "a.active", function () {
                    var id = $(this).attr('id');
                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/delete",
                        data: {warehouse_id: id, status: 1, ajaxaction: 'deactive'},
                        dataType: 'html',
                        success: function (data) {
                              
                             $('#s_'+ id).html("In-Active");
                            //$('#s_' + id).html('Active');
                            $('#' + id).html(data);
                            $('#' + id).removeClass("active");
                            $('#' + id).addClass("deactivate");
                        }
                    });
                });

                $(document).on("click", "a.deactivate", function () {
                    var id = $(this).attr('id');
                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-locations/delete",
                        data: {warehouse_id: id, status: 0, ajaxaction: 'active'},
                        dataType: 'html',
                        success: function (data) {
                              
                             $('#s_'+ id).html("Active");
                            //$('#s_' + id).html('In-Active');
                            $('#' + id).html(data);
                            $('#' + id).removeClass("deactivate");
                            $('#' + id).addClass("active");
                        }
                    });
                });
    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function (e) {
        e.preventDefault();
        var self = $(this);
        var make_name = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';
        order = self.data('order');
        sort = self.data('sort');
        make_name = $('#name').val();
        counter = $('#records').val();
        page = $('#current').val();
        if (make_name.length > 1) {
            document.location = appName + '/cadmin/manage-makes/?order=' + order + '&sort=' + sort + '&name=' + make_name + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/cadmin/manage-makes/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
});