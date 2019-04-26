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
    $("#starting_on_update").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#from_edit_update").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $('#reset').click(function () {
        window.location.href = appName + '/iadmin/manage-stores/inventory';
    });

    $('#records').change(function () {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-stores/inventory/?counter=' + counter;
    });

    $('#office_type_add').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stores/get-warehouse-types",
            data: {geo_level_id: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#warehouse_type').html(data);
            }
        });
    });

    $('#combo2_add').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stores/get-warehouse-types",
            data: {geo_level_id: $('#office_type_add').val()},
            dataType: 'html',
            success: function (data) {
                $('#warehouse_type').html(data);
            }
        });
    });

    $('#search').click(function () {
        $(".reload").trigger("click");
        $(this).button('loading');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stores/ajax-get-inventory-table",
            data: $('#search-stores').serialize(),
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
                        url: appName + "/iadmin/manage-stores/ajax-edit",
                        data: {wh_id: $(this).attr('itemid')},
                        dataType: 'html',
                        success: function (data) {
                            $('#modal-body-contents').html(data);

                            $('#update-button').show();
                            //   alert($('#location_type').val());
                            //  $('#location_level_edit').val($('#location_type').val());
                            setTimeout(function () {

                                $('#office_type_edit').val($('#office_id_edit').val());

                                if ($('#office_type_edit').val() != "") {

                                    $('#loader').show();
                                    $('#combo1_edit').empty();
                                    $('#combo2_edit').empty();
                                    $('#combo3_edit').empty();
                                    $('#combo4_edit').empty();

                                    $('#div_combo1_edit').hide();
                                    $('#div_combo2_edit').hide();
                                    $('#div_combo3_edit').hide();
                                    $('#div_combo4_edit').hide();

                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-one",
                                        data: {office: $('#office_type_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader').hide();
                                            var val1 = $('#office_type_edit').val();
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
                                                    case '2':
                                                        $('#lblcombo1_edit').text('Province');
                                                        $('#div_combo1_edit').show();
                                                        $('#combo1_edit').html(data);
                                                        $('#combo1_edit').val($('#province_id_edit').val());
                                                        break;
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
                                    var role_id = $('#hdn_role_id').val();
                                    if (role_id == 38) {
                                        $('#loader').show();
                                        $('#combo2_edit').empty();

                                        $('#div_combo2_edit').hide();

                                        $.ajax({
                                            type: "POST",
                                            url: appName + "/index/locations-combos-two",
                                            data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                            dataType: 'html',
                                            success: function (data) {
                                                $('#loader').hide();

                                                var val = $('#office_type_edit').val();

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

                                    if (role_id == 39) {
                                        $('#loader').show();
                                        $('#combo2_edit').empty();

                                        $('#div_combo2_edit').hide();

                                        $.ajax({
                                            type: "POST",
                                            url: appName + "/index/locations-combos-two",
                                            data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                            dataType: 'html',
                                            success: function (data) {
                                                $('#loader').hide();

                                                var val = $('#office_type_edit').val();

                                                switch (val)
                                                {
                                                    case '4':
                                                        $('#div_combo2_edit').show();
                                                        $('#combo2_edit').val(data);
                                                        //      $('#combo2_edit').val($('#district_id_edit').val());
                                                        break;
                                                    case '5':
                                                        $('#div_combo2_edit').show();
                                                        $('#combo2_edit').val(data);
                                                        //    $('#combo2_edit').val($('#district_id_edit').val());
                                                        break;
                                                    case '6':
                                                        $('#div_combo2_edit').show();
                                                        $('#combo2_edit').val(data);
                                                        //  $('#combo2_edit').val($('#district_id_edit').val());
                                                        break;
                                                }
                                            }
                                        });

                                        $.ajax({
                                            type: "POST",
                                            url: appName + "/index/locations-combos-three",
                                            data: {combo2: $('#district_id_edit').val(), office: $('#office_type_edit').val()},
                                            dataType: 'html',
                                            success: function (data) {
                                                $('#loader').hide();
                                                var val = $('#office_type_edit').val();
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

                                }

                                if ($('#combo1_edit').val() != "") {
                                    $('#loader').show();
                                    $('#combo2_edit').empty();

                                    $('#div_combo2_edit').hide();

                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-two",
                                        data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader').hide();

                                            var val = $('#office_type_edit').val();

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
                                        data: {combo2: $('#district_id_edit').val(), office: $('#office_type_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader').hide();
                                            var val = $('#office_type_edit').val();
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
                                        data: {combo3: $('#tehsil_id_edit').val(), office: $('#office_type_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#loader').hide();
                                            var val = $('#office_type_edit').val();
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

                                if ($('#office_type_edit').val() != "") {
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/iadmin/manage-stores/get-warehouse-types",
                                        data: {geo_level_id: $('#office_type_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#warehouse_type_update').html(data);
                                            $('#warehouse_type_update').val($('#warehouse_type_id_hidden').val());
                                        }
                                    });
                                }
                            }, 300);
                        }

                    });


                });
                $('#sample_2').on('click', '[data-toggle="notyfy"]', function () {
                    //    $('[data-toggle="notyfy"]').click(function ()
                    //    {
                    $.notyfy.closeAll();
                    var self = $(this);

                    notyfy({
                        text: 'Do you want to continue?',
                        type: self.data('type'),
                        dismissQueue: true,
                        layout: self.data('layout'),
                        buttons: (self.data('type') != 'confirm') ? false : [{
                                addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                                text: '<i></i> Ok',
                                onClick: function ($notyfy) {
                                    $notyfy.close();
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/iadmin/manage-stores/delete",
                                        data: {warehouse_id: self.data('bind'), status: self.data('status')},
                                        dataType: 'html',
                                        success: function (data) {
                                            notyfy({
                                                force: true,
                                                text: 'Store has been deleted!',
                                                type: 'success',
                                                layout: self.data('layout')
                                            });
                                            self.closest("tr").remove();
                                        }
                                    });
                                }
                            }, {
                                addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                                text: '<i></i> Cancel',
                                onClick: function ($notyfy) {
                                    $notyfy.close();
//                        notyfy({
//                            force: true,
//                            text: '<strong>You clicked "Cancel" button<strong>',
//                            type: 'error',
//                            layout: self.data('layout')
//                        });
                                }
                            }]
                    });
                    return false;
                });
            }
        });
    });


    $("#add-stores").validate({
        rules: {
            store_name_add: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-stores/check-stores-inventory",
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
                            return $("#office_type_add").val();
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
            ccm_warehouse_id: {
                //required: true,
                remote: {
                    url: appName + "/iadmin/manage-stores/check-ccm-warehouse",
                    type: "post",
                    data: {
                        district: function () {
                            return $("#combo2_add").val();
                        },
                        province: function () {
                            return $("#combo1_add").val();
                        },
                        locLvl: function () {
                            return $("#office_type_add").val();
                        },
                        locid: function () {
                            return $("#combo3_add").val();
                        }

                    }
                }
            }
        },
        messages: {
            store_name_add: {
                remote: "Warehouse is Already Available.",
                required: "Please enter Warehouse"
            },
            ccm_warehouse_id: {
                remote: "Ccm Warehouse Id is Already Available.",
                //required: "Please enter Ccm Warehouse Id"

            }
        },
        submitHandler: function (form) {
            $(".reload").trigger("click");
            $('#add').button('loading');
            $('#store_name_add_hdn').val($('#store_name_add').val());
            $('#action_hdn').val('1');
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-stores/ajax-add-inventory-store",
                data: $('#add-stores').serialize(),
                dataType: 'html',
                success: function (data) {
                    $('#add').button('reset');
                    $(".close").trigger("click");

                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-stores/ajax-get-inventory-table",
                        data: $('#search-stores').serialize(),
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
                                    url: appName + "/iadmin/manage-stores/ajax-edit",
                                    data: {wh_id: $(this).attr('itemid')},
                                    dataType: 'html',
                                    success: function (data) {
                                        $('#modal-body-contents').html(data);

                                        $('#update-button').show();
                                        //   alert($('#location_type').val());
                                        //  $('#location_level_edit').val($('#location_type').val());
                                        setTimeout(function () {

                                            $('#office_type_edit').val($('#office_id_edit').val());

                                            if ($('#office_type_edit').val() != "") {

                                                $('#loader').show();
                                                $('#combo1_edit').empty();
                                                $('#combo2_edit').empty();
                                                $('#combo3_edit').empty();
                                                $('#combo4_edit').empty();

                                                $('#div_combo1_edit').hide();
                                                $('#div_combo2_edit').hide();
                                                $('#div_combo3_edit').hide();
                                                $('#div_combo4_edit').hide();

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-one",
                                                    data: {office: $('#office_type_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();
                                                        var val1 = $('#office_type_edit').val();
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
                                                                case '2':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    $('#combo1_edit').val($('#province_id_edit').val());
                                                                    break;
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
                                                var role_id = $('#hdn_role_id').val();
                                                if (role_id == 38) {
                                                    $('#loader').show();
                                                    $('#combo2_edit').empty();

                                                    $('#div_combo2_edit').hide();

                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-two",
                                                        data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#loader').hide();

                                                            var val = $('#office_type_edit').val();

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

                                                if (role_id == 39) {
                                                    $('#loader').show();
                                                    $('#combo2_edit').empty();

                                                    $('#div_combo2_edit').hide();

                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-two",
                                                        data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#loader').hide();

                                                            var val = $('#office_type_edit').val();

                                                            switch (val)
                                                            {
                                                                case '4':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    //      $('#combo2_edit').val($('#district_id_edit').val());
                                                                    break;
                                                                case '5':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    //    $('#combo2_edit').val($('#district_id_edit').val());
                                                                    break;
                                                                case '6':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    //  $('#combo2_edit').val($('#district_id_edit').val());
                                                                    break;
                                                            }
                                                        }
                                                    });

                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-three",
                                                        data: {combo2: $('#district_id_edit').val(), office: $('#office_type_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#loader').hide();
                                                            var val = $('#office_type_edit').val();
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

                                            }

                                            if ($('#combo1_edit').val() != "") {
                                                $('#loader').show();
                                                $('#combo2_edit').empty();

                                                $('#div_combo2_edit').hide();

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();

                                                        var val = $('#office_type_edit').val();

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
                                                    data: {combo2: $('#district_id_edit').val(), office: $('#office_type_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();
                                                        var val = $('#office_type_edit').val();
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
                                                    data: {combo3: $('#tehsil_id_edit').val(), office: $('#office_type_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();
                                                        var val = $('#office_type_edit').val();
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

                                            if ($('#office_type_edit').val() != "") {
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-stores/get-warehouse-types",
                                                    data: {geo_level_id: $('#office_type_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#warehouse_type_update').html(data);
                                                        $('#warehouse_type_update').val($('#warehouse_type_id_hidden').val());
                                                    }
                                                });
                                            }
                                        }, 300);
                                    }

                                });


                            });
                            $('#sample_2').on('click', '[data-toggle="notyfy"]', function () {
                                //          $('[data-toggle="notyfy"]').click(function ()
                                //         {
                                $.notyfy.closeAll();
                                var self = $(this);

                                notyfy({
                                    text: 'Do you want to continue?',
                                    type: self.data('type'),
                                    dismissQueue: true,
                                    layout: self.data('layout'),
                                    buttons: (self.data('type') != 'confirm') ? false : [{
                                            addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                                            text: '<i></i> Ok',
                                            onClick: function ($notyfy) {
                                                $notyfy.close();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-stores/delete",
                                                    data: {warehouse_id: self.data('bind'), status: self.data('status')},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        notyfy({
                                                            force: true,
                                                            text: 'Store has been deleted!',
                                                            type: 'success',
                                                            layout: self.data('layout')
                                                        });
                                                        self.closest("tr").remove();
                                                    }
                                                });
                                            }
                                        }, {
                                            addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                                            text: '<i></i> Cancel',
                                            onClick: function ($notyfy) {
                                                $notyfy.close();
//                        notyfy({
//                            force: true,
//                            text: '<strong>You clicked "Cancel" button<strong>',
//                            type: 'error',
//                            layout: self.data('layout')
//                        });
                                            }
                                        }]
                                });
                                return false;
                            });
                        }
                    });

                }
            })
        }

    });





// validate signup form on keyup and submit
    $("#update-stores").validate({
        rules: {
            store_name_update: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-stores/check-stores-update-inventory",
                    type: "post",
                    data: {
                        district: function () {
                            return $("#combo2_edit").val();
                        },
                        wh_id: function () {
                            return $("#wh_id").val();
                        },
                        province: function () {
                            return $("#combo1_edit").val();
                        },
                        locid: function () {
                            return $("#combo3_edit").val();
                        },
                        stk_id: function () {
                            return $("#office_type_edit").val();
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
            }
            ,
            ccm_warehouse_id_update: {
                //required: true,
                remote: {
                    url: appName + "/iadmin/manage-stores/check-ccm-warehouse-update",
                    type: "post",
                    data: {
                        district: function () {
                            return $("#combo2_edit").val();
                        },
                        wh_id: function () {
                            return $("#wh_id").val();
                        },
                        province: function () {
                            return $("#combo1_edit").val();
                        },
                        locLvl: function () {
                            return $("#office_type_edit").val();
                        },
                        locid: function () {
                            return $("#combo3_edit").val();
                        }

                    }
                }
            }
        },
        messages: {
            store_name_update: {
                remote: "Warehouse is Already Available.",
                required: "Please enter Warehouse"
            },
            ccm_warehouse_id_update: {
                remote: "Ccm Warehouse Id is Already Available.",
                // required: "Please enter Ccm Warehouse Id"

            }
        },
        submitHandler: function (form) {
            $(".reload").trigger("click");
            $('#update').button('loading');
            $('#store_name_edit_hdn').val($('#store_name_update').val());
            $('#action_hdn').val('2');
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-stores/ajax-edit-inventory-store",
                data: $('#update-stores').serialize(),
                dataType: 'html',
                success: function (data) {
                    $('#update').button('reset');
                    $(".close").trigger("click");

                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-stores/ajax-get-inventory-table",
                        data: $('#search-stores').serialize(),
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
                                    url: appName + "/iadmin/manage-stores/ajax-edit",
                                    data: {wh_id: $(this).attr('itemid')},
                                    dataType: 'html',
                                    success: function (data) {
                                        $('#modal-body-contents').html(data);

                                        $('#update-button').show();
                                        //   alert($('#location_type').val());
                                        //  $('#location_level_edit').val($('#location_type').val());
                                        setTimeout(function () {

                                            $('#office_type_edit').val($('#office_id_edit').val());

                                            if ($('#office_type_edit').val() != "") {

                                                $('#loader').show();
                                                $('#combo1_edit').empty();
                                                $('#combo2_edit').empty();
                                                $('#combo3_edit').empty();
                                                $('#combo4_edit').empty();

                                                $('#div_combo1_edit').hide();
                                                $('#div_combo2_edit').hide();
                                                $('#div_combo3_edit').hide();
                                                $('#div_combo4_edit').hide();

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-one",
                                                    data: {office: $('#office_type_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();
                                                        var val1 = $('#office_type_edit').val();
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
                                                                case '2':
                                                                    $('#lblcombo1_edit').text('Province');
                                                                    $('#div_combo1_edit').show();
                                                                    $('#combo1_edit').html(data);
                                                                    $('#combo1_edit').val($('#province_id_edit').val());
                                                                    break;
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
                                                var role_id = $('#hdn_role_id').val();
                                                if (role_id == 38) {
                                                    $('#loader').show();
                                                    $('#combo2_edit').empty();

                                                    $('#div_combo2_edit').hide();

                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-two",
                                                        data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#loader').hide();

                                                            var val = $('#office_type_edit').val();

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

                                                if (role_id == 39) {
                                                    $('#loader').show();
                                                    $('#combo2_edit').empty();

                                                    $('#div_combo2_edit').hide();

                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-two",
                                                        data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#loader').hide();

                                                            var val = $('#office_type_edit').val();

                                                            switch (val)
                                                            {
                                                                case '4':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    //      $('#combo2_edit').val($('#district_id_edit').val());
                                                                    break;
                                                                case '5':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    //    $('#combo2_edit').val($('#district_id_edit').val());
                                                                    break;
                                                                case '6':
                                                                    $('#div_combo2_edit').show();
                                                                    $('#combo2_edit').val(data);
                                                                    //  $('#combo2_edit').val($('#district_id_edit').val());
                                                                    break;
                                                            }
                                                        }
                                                    });

                                                    $.ajax({
                                                        type: "POST",
                                                        url: appName + "/index/locations-combos-three",
                                                        data: {combo2: $('#district_id_edit').val(), office: $('#office_type_edit').val()},
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#loader').hide();
                                                            var val = $('#office_type_edit').val();
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

                                            }

                                            if ($('#combo1_edit').val() != "") {
                                                $('#loader').show();
                                                $('#combo2_edit').empty();

                                                $('#div_combo2_edit').hide();

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();

                                                        var val = $('#office_type_edit').val();

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
                                                    data: {combo2: $('#district_id_edit').val(), office: $('#office_type_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();
                                                        var val = $('#office_type_edit').val();
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
                                                    data: {combo3: $('#tehsil_id_edit').val(), office: $('#office_type_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#loader').hide();
                                                        var val = $('#office_type_edit').val();
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

                                            if ($('#office_type_edit').val() != "") {
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-stores/get-warehouse-types",
                                                    data: {geo_level_id: $('#office_type_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#warehouse_type_update').html(data);
                                                        $('#warehouse_type_update').val($('#warehouse_type_id_hidden').val());
                                                    }
                                                });
                                            }
                                        }, 300);
                                    }

                                });


                            });
                            $('#sample_2').on('click', '[data-toggle="notyfy"]', function () {
                                //         $('[data-toggle="notyfy"]').click(function ()
                                //       {
                                $.notyfy.closeAll();
                                var self = $(this);

                                notyfy({
                                    text: 'Do you want to continue?',
                                    type: self.data('type'),
                                    dismissQueue: true,
                                    layout: self.data('layout'),
                                    buttons: (self.data('type') != 'confirm') ? false : [{
                                            addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                                            text: '<i></i> Ok',
                                            onClick: function ($notyfy) {
                                                $notyfy.close();
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-stores/delete",
                                                    data: {warehouse_id: self.data('bind'), status: self.data('status')},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        notyfy({
                                                            force: true,
                                                            text: 'Store has been deleted!',
                                                            type: 'success',
                                                            layout: self.data('layout')
                                                        });
                                                        self.closest("tr").remove();
                                                    }
                                                });
                                            }
                                        }, {
                                            addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                                            text: '<i></i> Cancel',
                                            onClick: function ($notyfy) {
                                                $notyfy.close();
//                        notyfy({
//                            force: true,
//                            text: '<strong>You clicked "Cancel" button<strong>',
//                            type: 'error',
//                            layout: self.data('layout')
//                        });
                                            }
                                        }]
                                });
                                return false;
                            });
                        }
                    });

                }
            });
        }

    });

    $('#sample_2').on('click', '.update-stores', function () {

        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stores/ajax-edit",
            data: {wh_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);

                $('#update-button').show();
                //   alert($('#location_type').val());
                //  $('#location_level_edit').val($('#location_type').val());
                setTimeout(function () {

                    $('#office_type_edit').val($('#office_id_edit').val());

                    if ($('#office_type_edit').val() != "") {

                        $('#loader').show();
                        $('#combo1_edit').empty();
                        $('#combo2_edit').empty();
                        $('#combo3_edit').empty();
                        $('#combo4_edit').empty();

                        $('#div_combo1_edit').hide();
                        $('#div_combo2_edit').hide();
                        $('#div_combo3_edit').hide();
                        $('#div_combo4_edit').hide();

                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-one",
                            data: {office: $('#office_type_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader').hide();
                                var val1 = $('#office_type_edit').val();
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
                                        case '2':
                                            $('#lblcombo1_edit').text('Province');
                                            $('#div_combo1_edit').show();
                                            $('#combo1_edit').html(data);
                                            $('#combo1_edit').val($('#province_id_edit').val());
                                            break;
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
                        var role_id = $('#hdn_role_id').val();
                        if (role_id == 38) {
                            $('#loader').show();
                            $('#combo2_edit').empty();

                            $('#div_combo2_edit').hide();

                            $.ajax({
                                type: "POST",
                                url: appName + "/index/locations-combos-two",
                                data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                dataType: 'html',
                                success: function (data) {
                                    $('#loader').hide();

                                    var val = $('#office_type_edit').val();

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

                        if (role_id == 39) {
                            $('#loader').show();
                            $('#combo2_edit').empty();

                            $('#div_combo2_edit').hide();

                            $.ajax({
                                type: "POST",
                                url: appName + "/index/locations-combos-two",
                                data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                                dataType: 'html',
                                success: function (data) {
                                    $('#loader').hide();

                                    var val = $('#office_type_edit').val();

                                    switch (val)
                                    {
                                        case '4':
                                            $('#div_combo2_edit').show();
                                            $('#combo2_edit').val(data);
                                            //      $('#combo2_edit').val($('#district_id_edit').val());
                                            break;
                                        case '5':
                                            $('#div_combo2_edit').show();
                                            $('#combo2_edit').val(data);
                                            //    $('#combo2_edit').val($('#district_id_edit').val());
                                            break;
                                        case '6':
                                            $('#div_combo2_edit').show();
                                            $('#combo2_edit').val(data);
                                            //  $('#combo2_edit').val($('#district_id_edit').val());
                                            break;
                                    }
                                }
                            });

                            $.ajax({
                                type: "POST",
                                url: appName + "/index/locations-combos-three",
                                data: {combo2: $('#district_id_edit').val(), office: $('#office_type_edit').val()},
                                dataType: 'html',
                                success: function (data) {
                                    $('#loader').hide();
                                    var val = $('#office_type_edit').val();
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

                    }

                    if ($('#combo1_edit').val() != "") {
                        $('#loader').show();
                        $('#combo2_edit').empty();

                        $('#div_combo2_edit').hide();

                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-two",
                            data: {combo1: $('#province_id_edit').val(), office: $('#office_type_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader').hide();

                                var val = $('#office_type_edit').val();

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
                            data: {combo2: $('#district_id_edit').val(), office: $('#office_type_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader').hide();
                                var val = $('#office_type_edit').val();
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
                            data: {combo3: $('#tehsil_id_edit').val(), office: $('#office_type_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#loader').hide();
                                var val = $('#office_type_edit').val();
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

                    if ($('#office_type_edit').val() != "") {
                        $.ajax({
                            type: "POST",
                            url: appName + "/iadmin/manage-stores/get-warehouse-types",
                            data: {geo_level_id: $('#office_type_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#warehouse_type_update').html(data);
                                $('#warehouse_type_update').val($('#warehouse_type_id_hidden').val());
                            }
                        });
                    }
                }, 300);
            }

        });


    });
    $('#sample_2').on('click', '[data-toggle="notyfy"]', function () {
        // $('[data-toggle="notyfy"]').click(function ()
        //{
        $.notyfy.closeAll();
        var self = $(this);

        notyfy({
            text: 'Do you want to continue?',
            type: self.data('type'),
            dismissQueue: true,
            layout: self.data('layout'),
            buttons: (self.data('type') != 'confirm') ? false : [{
                    addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                    text: '<i></i> Ok',
                    onClick: function ($notyfy) {
                        $notyfy.close();
                        $.ajax({
                            type: "POST",
                            url: appName + "/iadmin/manage-stores/delete",
                            data: {warehouse_id: self.data('bind'), status: self.data('status')},
                            dataType: 'html',
                            success: function (data) {
                                notyfy({
                                    force: true,
                                    text: 'Store has been deleted!',
                                    type: 'success',
                                    layout: self.data('layout')
                                });
                                self.closest("tr").remove();
                            }
                        });
                    }
                }, {
                    addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                    text: '<i></i> Cancel',
                    onClick: function ($notyfy) {
                        $notyfy.close();
//                        notyfy({
//                            force: true,
//                            text: '<strong>You clicked "Cancel" button<strong>',
//                            type: 'error',
//                            layout: self.data('layout')
//                        });
                    }
                }]
        });
        return false;
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