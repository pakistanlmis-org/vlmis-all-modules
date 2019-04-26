$(function () {


    $("#starting_on").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#from_edit").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });





    $('#reset').click(function () {
        window.location.href = appName + '/iadmin/manage-stores/routine';
    });

    $('#records').change(function () {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-stores/routine/?counter=' + counter;
    });

    $('#search').click(function () {
        $(".reload").trigger("click");
        $(this).button('loading');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stores/ajax-get-routine-table",
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
                $('#sample_2').on('click', '.update-stores', function () {

                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-stores/ajax-routine-edit",
                        data: {wh_id: $(this).attr('itemid')},
                        dataType: 'html',
                        success: function (data) {
                            $('#modal-body-contents').html(data);

                            $('#update-button').show();



                            //   alert($('#location_type').val());
                            //  $('#location_level_edit').val($('#location_type').val());
                            setTimeout(function () {

                                $('#combo1_edit').val($('#province_id_edit').val());


                                if ($('#combo1_edit').val() != "") {
                                    $('#loader').show();
                                    $('#combo2_edit').empty();

                                    $('#div_combo2_edit').hide();

                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-two",
                                        data: {combo1: $('#province_id_edit').val(), office: 6},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader').hide();

                                            var val = '6';

                                            switch (val)
                                            {
                                                case '4':
                                                    $('#div_combo2_edit').show();
                                                    $('#combo2_edit').html(data);
                                                    $('#combo2_edit').val($('#district_id_edit').val());
                                                    break;
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
                                    $('#loader').show();
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-three",
                                        data: {combo2: $('#district_id_edit').val(), office: 6},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader').hide();
                                            var val = '6';
                                            switch (val)
                                            {
                                                case '5':
                                                    $('#div_combo3_edit').show();
                                                    $('#combo3_edit').html(data);
                                                    $('#combo3_edit').val($('#tehsil_id_edit').val());
                                                    break;

                                                case '6':
                                                    $('#div_combo3_edit').show();
                                                    $('#combo3_edit').html(data);
                                                    $('#combo3_edit').val($('#tehsil_id_edit').val());
                                                    break;

                                            }
                                        }
                                    });
                                }

                                if ($('#combo3_edit').val() != "") {
                                    $('#loader').show();
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-four",
                                        data: {combo3: $('#tehsil_id_edit').val(), office: 6},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader').hide();
                                            var val = '6';
                                            switch (val)
                                            {
                                                case '6':
                                                    $('#div_combo4_edit').show();
                                                    $('#combo4_edit').html(data);
                                                    $('#combo4_edit').val($('#parent_id_edit').val());
                                                    break;

                                            }
                                        }
                                    });
                                }

                                if ($('#combo1_edit').val() != "") {
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/iadmin/manage-stores/get-warehouse-types",
                                        data: {geo_level_id: '6'},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#warehouse_type_update').html(data);
                                            $('#warehouse_type_update').val($('#warehouse_type_id_hidden').val());
                                        }
                                    });
                                }
                            }, 30);


                        }

                    });


                });

                $(document).on("click", "a.active", function () {
                    var id = $(this).attr('id');
                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-stores/delete",
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
                        url: appName + "/iadmin/manage-stores/delete",
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


    // validate signup form on keyup and submit
    $("#update-stores").validate({
        rules: {
            store_name_update: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-stores/check-stores-update",
                    type: "post",
                    data: {
                        district: function () {
                            return $("#combo2_edit").val();
                        },
                        province: function () {
                            return $("#combo1_edit").val();
                        },
                        locid: function () {
                            return $("#combo3_edit").val();
                        },
                        stk_id: function () {
                            return '9';
                        },
                        locid_uc: function () {
                            return $("#combo4_edit").val();
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
            combo4_edit: {
                required: true
            },
            population_update:{
                required:true,
                number:true,
                min:0
            },
            ccm_warehouse_id_update: {
                //required: true,
                /*remote: {
                 url: appName + "/iadmin/manage-stores/check-ccm-warehouse-update",
                 type: "post",
                 data: {
                 district: function() {
                 return $("#combo2_edit").val();
                 },
                 province: function() {
                 return $("#combo1_edit").val();
                 },
                 locLvl: function() {
                 return '6';
                 },
                 locid: function() {
                 return $("#combo4_edit").val();
                 }
                 
                 }
                 }*/

            }


        },
        messages: {
            store_name_update: {
                remote: "Store is Already Available.",
                required: "Please enter Location"
            },
            ccm_warehouse_id_update: {
                //remote: "Ccm Code Id is Already Available.",
                //required: "Please enter Ccm Location Id"

            }
        },
        submitHandler: function (form) {
            $(".reload").trigger("click");
            $('#update').button('loading');
            $('#location_name_edit_hdn').val($('#store_name_update').val());
            $('#action_hdn').val('2');
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-stores/ajax-edit-routine-store",
                data: $('#update-stores').serialize(),
                dataType: 'html',
                success: function (data) {
                    $('#update').button('reset');
                    $(".close").trigger("click");

                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-stores/ajax-get-routine-table",
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
                            $('#sample_2').on('click', '.update-stores', function () {

                                $.ajax({
                                    type: "POST",
                                    url: appName + "/iadmin/manage-stores/ajax-routine-edit",
                                    data: {wh_id: $(this).attr('itemid')},
                                    dataType: 'html',
                                    success: function (data) {
                                        $('#modal-body-contents').html(data);

                                        $('#update-button').show();



                                        //   alert($('#location_type').val());
                                        //  $('#location_level_edit').val($('#location_type').val());
                                        setTimeout(function () {

                                            $('#combo1_edit').val($('#province_id_edit').val());


                                            if ($('#combo1_edit').val() != "") {
                                                $('#loader').show();
                                                $('#combo2_edit').empty();

                                                $('#div_combo2_edit').hide();

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: 6},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();

                                                        var val = '6';

                                                        switch (val)
                                                        {
                                                            case '4':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
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
                                                $('#loader').show();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-three",
                                                    data: {combo2: $('#district_id_edit').val(), office: 6},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();
                                                        var val = '6';
                                                        switch (val)
                                                        {
                                                            case '5':
                                                                $('#div_combo3_edit').show();
                                                                $('#combo3_edit').html(data);
                                                                $('#combo3_edit').val($('#tehsil_id_edit').val());
                                                                break;

                                                            case '6':
                                                                $('#div_combo3_edit').show();
                                                                $('#combo3_edit').html(data);
                                                                $('#combo3_edit').val($('#tehsil_id_edit').val());
                                                                break;

                                                        }
                                                    }
                                                });
                                            }

                                            if ($('#combo3_edit').val() != "") {
                                                $('#loader').show();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-four",
                                                    data: {combo3: $('#tehsil_id_edit').val(), office: 6},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();
                                                        var val = '6';
                                                        switch (val)
                                                        {
                                                            case '6':
                                                                $('#div_combo4_edit').show();
                                                                $('#combo4_edit').html(data);
                                                                $('#combo4_edit').val($('#parent_id_edit').val());
                                                                break;

                                                        }
                                                    }
                                                });
                                            }

                                            if ($('#combo1_edit').val() != "") {
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-stores/get-warehouse-types",
                                                    data: {geo_level_id: '6'},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#warehouse_type_update').html(data);
                                                        $('#warehouse_type_update').val($('#warehouse_type_id_hidden').val());
                                                    }
                                                });
                                            }
                                        }, 30);


                                    }

                                });


                            });

                            $(document).on("click", "a.active", function () {
                                var id = $(this).attr('id');
                                $.ajax({
                                    type: "POST",
                                    url: appName + "/iadmin/manage-stores/delete",
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
                                    url: appName + "/iadmin/manage-stores/delete",
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

    $("#add-stores").validate({
        rules: {
            store_name_add: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-stores/check-stores",
                    type: "post",
                    data: {
                        district: function () {
                            return $("#combo2_add").val();
                        },
                        province: function () {
                            return $("#combo1_add").val();
                        },
                        locid: function () {
                            return $("#combo3_add").val();
                        },
                        stk_id: function () {
                            return '9';
                        },
                        locid_uc: function () {
                            return $("#combo4_add").val();
                        }

                    }
                }
            },
            combo1_add: {
                required: true
            },
            combo2_add: {
                required: true
            },
            combo3_add: {
                required: true
            },
            combo4_add: {
                required: true
            },
            warehouse_type: {
                required: true

            },
             population: {
                required: true,
                number:true,
                min:0

            },
            ccm_warehouse_id: {
                //required: true,
                /*remote: {
                 url: appName + "/iadmin/manage-stores/check-ccm-warehouse",
                 type: "post",
                 data: {
                 district: function() {
                 return $("#combo2_add").val();
                 },
                 province: function() {
                 return $("#combo1_add").val();
                 },
                 locLvl: function() {
                 return '6';
                 },
                 locid: function() {
                 return $("#combo4_add").val();
                 }
                 
                 }
                 }*/
            }
        },
        messages: {
            store_name_add: {
                remote: "Warehouse is Already Available.",
                required: "Please enter Warehouse"
            },
            ccm_warehouse_id: {
                // remote: "Ccm Warehouse Id is Already Available.",
                // required: "Please enter Ccm Warehouse Id"

            }
        },
        submitHandler: function (form) {
            $(".reload").trigger("click");
            $('#add').button('loading');
            $('#location_name_hdn').val($('#store_name_add').val());
            $('#action_hdn').val('1');
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-stores/ajax-add-routine-store",
                data: $('#add-stores').serialize(),
                dataType: 'html',
                success: function (data) {
                    $('#add').button('reset');
                    $(".close").trigger("click");
                    $('#add-stores')[0].reset();

                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-stores/ajax-get-routine-table",
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
                            $('#sample_2').on('click', '.update-stores', function () {

                                $.ajax({
                                    type: "POST",
                                    url: appName + "/iadmin/manage-stores/ajax-routine-edit",
                                    data: {wh_id: $(this).attr('itemid')},
                                    dataType: 'html',
                                    success: function (data) {
                                        $('#modal-body-contents').html(data);

                                        $('#update-button').show();



                                        //   alert($('#location_type').val());
                                        //  $('#location_level_edit').val($('#location_type').val());
                                        setTimeout(function () {

                                            $('#combo1_edit').val($('#province_id_edit').val());


                                            if ($('#combo1_edit').val() != "") {
                                                $('#loader').show();
                                                $('#combo2_edit').empty();

                                                $('#div_combo2_edit').hide();

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: 6},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();

                                                        var val = '6';

                                                        switch (val)
                                                        {
                                                            case '4':
                                                                $('#div_combo2_edit').show();
                                                                $('#combo2_edit').html(data);
                                                                $('#combo2_edit').val($('#district_id_edit').val());
                                                                break;
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
                                                $('#loader').show();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-three",
                                                    data: {combo2: $('#district_id_edit').val(), office: 6},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();
                                                        var val = '6';
                                                        switch (val)
                                                        {
                                                            case '5':
                                                                $('#div_combo3_edit').show();
                                                                $('#combo3_edit').html(data);
                                                                $('#combo3_edit').val($('#tehsil_id_edit').val());
                                                                break;

                                                            case '6':
                                                                $('#div_combo3_edit').show();
                                                                $('#combo3_edit').html(data);
                                                                $('#combo3_edit').val($('#tehsil_id_edit').val());
                                                                break;

                                                        }
                                                    }
                                                });
                                            }

                                            if ($('#combo3_edit').val() != "") {
                                                $('#loader').show();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-four",
                                                    data: {combo3: $('#tehsil_id_edit').val(), office: 6},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();
                                                        var val = '6';
                                                        switch (val)
                                                        {
                                                            case '6':
                                                                $('#div_combo4_edit').show();
                                                                $('#combo4_edit').html(data);
                                                                $('#combo4_edit').val($('#parent_id_edit').val());
                                                                break;

                                                        }
                                                    }
                                                });
                                            }

                                            if ($('#combo1_edit').val() != "") {
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-stores/get-warehouse-types",
                                                    data: {geo_level_id: '6'},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#warehouse_type_update').html(data);
                                                        $('#warehouse_type_update').val($('#warehouse_type_id_hidden').val());
                                                    }
                                                });
                                            }
                                        }, 30);


                                    }

                                });


                            });

                            $(document).on("click", "a.active", function () {
                                var id = $(this).attr('id');
                                $.ajax({
                                    type: "POST",
                                    url: appName + "/iadmin/manage-stores/delete",
                                    data: {warehouse_id: id, status: 1, ajaxaction: 'deactive'},
                                    dataType: 'html',
                                    success: function (data) {
                                        
                                        $('#s_'+ id).html("In-Active");
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
                                    url: appName + "/iadmin/manage-stores/delete",
                                    data: {warehouse_id: id, status: 0, ajaxaction: 'active'},
                                    dataType: 'html',
                                    success: function (data) {
                                         
                                        $('#s_'+ id).html("");
                                        $('#s_'+ id).html("Active");
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

    $('#warehouse_type').change(function () {
        if ($(this).val() == 11) {
            $('#div_warehouse_type').show();
        } else {
            $('#div_warehouse_type').hide();
        }

    });
    $('#combo1_add').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stores/get-warehouse-types",
            data: {geo_level_id: '6'},
            dataType: 'html',
            success: function (data) {
                $('#warehouse_type').html(data);
            }
        });
    });

    $('#combo3_add').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stores/get-warehouse-types",
            data: {geo_level_id: '6'},
            dataType: 'html',
            success: function (data) {
                $('#warehouse_type').html(data);
            }
        });
    });
    $('#sample_2').on('click', '.update-stores', function () {

        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stores/ajax-routine-edit",
            data: {wh_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);

                $('#update-button').show();



                //   alert($('#location_type').val());
                //  $('#location_level_edit').val($('#location_type').val());
                setTimeout(function () {

                    $('#combo1_edit').val($('#province_id_edit').val());


                    if ($('#combo1_edit').val() != "") {
                        $('#loader').show();
                        $('#combo2_edit').empty();

                        $('#div_combo2_edit').hide();

                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-two",
                            data: {combo1: $('#province_id_edit').val(), office: 6},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader').hide();

                                var val = '6';

                                switch (val)
                                {
                                    case '4':
                                        $('#div_combo2_edit').show();
                                        $('#combo2_edit').html(data);
                                        $('#combo2_edit').val($('#district_id_edit').val());
                                        break;
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
                        $('#loader').show();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-three",
                            data: {combo2: $('#district_id_edit').val(), office: 6},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader').hide();
                                var val = '6';
                                switch (val)
                                {
                                    case '5':
                                        $('#div_combo3_edit').show();
                                        $('#combo3_edit').html(data);
                                        $('#combo3_edit').val($('#tehsil_id_edit').val());
                                        break;

                                    case '6':
                                        $('#div_combo3_edit').show();
                                        $('#combo3_edit').html(data);
                                        $('#combo3_edit').val($('#tehsil_id_edit').val());
                                        break;

                                }
                            }
                        });
                    }

                    if ($('#combo3_edit').val() != "") {
                        $('#loader').show();
                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-four",
                            data: {combo3: $('#tehsil_id_edit').val(), office: 6},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader').hide();
                                var val = '6';
                                switch (val)
                                {
                                    case '6':
                                        $('#div_combo4_edit').show();
                                        $('#combo4_edit').html(data);
                                        $('#combo4_edit').val($('#parent_id_edit').val());
                                        break;

                                }
                            }
                        });
                    }

                    if ($('#combo1_edit').val() != "") {
                        $.ajax({
                            type: "POST",
                            url: appName + "/iadmin/manage-stores/get-warehouse-types",
                            data: {geo_level_id: '6'},
                            dataType: 'html',
                            success: function (data) {
                                $('#warehouse_type_update').html(data);
                                $('#warehouse_type_update').val($('#warehouse_type_id_hidden').val());
                            }
                        });
                    }
                }, 30);


            }

        });


    });

    $(document).on("click", "a.active", function () {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stores/delete",
            data: {warehouse_id: id, status: 1, ajaxaction: 'deactive'},
            dataType: 'html',
            success: function (data) {
                  
                $('#s_'+ id).html("");
                $('#s_'+ id).html("In-Active");
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
            url: appName + "/iadmin/manage-stores/delete",
            data: {warehouse_id: id, status: 0, ajaxaction: 'active'},
            dataType: 'html',
            success: function (data) {
               
               $('#s_'+ id).html("");
               $('#s_'+ id).html("Active");
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