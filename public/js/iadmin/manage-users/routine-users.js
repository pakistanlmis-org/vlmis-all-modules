$(function () {

    $('#reset').click(function () {
        window.location.href = appName + '/iadmin/manage-users/routine-users';
    });

    $('#records').change(function () {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-users/routine-users?counter=' + counter;
    });

    $('#search').click(function () {

        $(".reload").trigger("click");
        $(this).button('loading');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-users/ajax-get-routine-table",
            data: $('#locatins').serialize(),
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
                        url: appName + "/iadmin/manage-users/ajax-edit-routine",
                        data: {wh_id: $(this).attr('itemid')},
                        dataType: 'html',
                        success: function (data) {
                            $('#modal-body-contents').html(data);

                            $('#update-button').show();
                            setTimeout(function () {


                                $('#combo1_edit').val($('#province_id_edit').val());
                                if ($('#combo1_edit').val() != "") {
                                    $('#loader').show();
                                    $('#combo2_edit').empty();
                                    $('#div_combo2_edit').hide();

                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/index/locations-combos-two",
                                        data: {combo1: $('#province_id_edit').val(), office: '6'},
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
                                        data: {combo2: $('#district_id_edit').val(), office: '6'},
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
                                        data: {combo3: $('#tehsil_id_edit').val(), office: '6'},
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

                                if ($('#combo4_edit').val() != "") {
                                    $.ajax({
                                        type: "POST",
                                        url: appName + "/iadmin/manage-users/get-default-warehouse",
                                        data: {geo_level_id: '6', location_id: $('#parent_id_edit').val()},
                                        dataType: 'html',
                                        success: function (data) {
                                            $('#default_warehouse_update').html(data);
                                            $('#default_warehouse_update').val($('#default_warehouse_update_hidden').val());
                                        }
                                    });
                                }


                            }, 300);
                        }

                    });


                });

                $('[data-toggle="notyfy"]').click(function ()
                {
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
                                        url: appName + "/iadmin/manage-users/delete",
                                        data: {user_id: self.data('bind')},
                                        dataType: 'html',
                                        success: function (data) {
                                            notyfy({
                                                force: true,
                                                text: 'User has been deleted!',
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

                                }
                            }]
                    });
                    return false;
                });
            }
        });


    });

    $.validator.addMethod("alphanumeric", function (value, element) {
        return this.optional(element) || /^[a-z0-9\s]+$/i.test(value);
    }, "Username must contain only letters and numbers.");

    // validate signup form on keyup and submit
    $("#update-stores").validate({
        rules: {
            user_name_update: {
                required: true,
                alphanumeric: true,
                remote: {
                    url: appName + "/iadmin/manage-users/check-users-update",
                    type: "post",
                    data: {
                        user_name_update_hidden: function () {
                            return $("#user_name_update_hidden").val();
                        },
                        province: function () {
                            return $("#combo1_edit").val();
                        },
                        office_type_edit: function () {
                            return '6';
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
            default_warehouse_update: {
                required: true
            }


        },
        messages: {
            user_name_update: {
                remote: "Username already exists.",
                required: "Please enter Username",
                alphanumeric: 'Only alphabets, underscore and numeric values are allowed.'
            },
            combo1: {
                required: "Please select Province."
            },
            combo2: {
                required: "Please select District."
            },
            combo3: {
                required: "Please select Tehsil."
            },
            combo4: {
                required: "Please select UC."
            }

        }, submitHandler: function (form) {
            $(".reload").trigger("click");
            $('#update').button('loading');
            $('#location_name_edit_hdn').val($('#user_name_update').val());
            $('#action_hdn').val('2');
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-users/ajax-edit-routine-user",
                data: $('#update-stores').serialize(),
                dataType: 'html',
                success: function (data) {
                    $('#update').button('reset');
                    $(".close").trigger("click");

                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-users/ajax-get-routine-table",
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
                                    url: appName + "/iadmin/manage-users/ajax-edit-routine",
                                    data: {wh_id: $(this).attr('itemid')},
                                    dataType: 'html',
                                    success: function (data) {
                                        $('#modal-body-contents').html(data);

                                        $('#update-button').show();
                                        setTimeout(function () {


                                            $('#combo1_edit').val($('#province_id_edit').val());
                                            if ($('#combo1_edit').val() != "") {
                                                $('#loader').show();
                                                $('#combo2_edit').empty();
                                                $('#div_combo2_edit').hide();

                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/index/locations-combos-two",
                                                    data: {combo1: $('#province_id_edit').val(), office: '6'},
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
                                                    data: {combo2: $('#district_id_edit').val(), office: '6'},
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
                                                    data: {combo3: $('#tehsil_id_edit').val(), office: '6'},
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

                                            if ($('#combo4_edit').val() != "") {
                                                $.ajax({
                                                    type: "POST",
                                                    url: appName + "/iadmin/manage-users/get-default-warehouse",
                                                    data: {geo_level_id: '6', location_id: $('#parent_id_edit').val()},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        $('#default_warehouse_update').html(data);
                                                        $('#default_warehouse_update').val($('#default_warehouse_update_hidden').val());
                                                    }
                                                });
                                            }


                                        }, 300);
                                    }

                                });


                            });

                            $('[data-toggle="notyfy"]').click(function ()
                            {
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
                                                    url: appName + "/iadmin/manage-users/delete",
                                                    data: {user_id: self.data('bind')},
                                                    dataType: 'html',
                                                    success: function (data) {
                                                        notyfy({
                                                            force: true,
                                                            text: 'User has been deleted!',
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

    $("#add-stores").validate({
        rules: {
            user_name_add: {
                required: true,
                alphanumeric: true,
                remote: {
                    url: appName + "/iadmin/manage-users/check-users",
                    type: "post",
                    data: {
                        province: function () {
                            return $("#combo1_add").val();
                        },
                        office_type_add: function () {

                            return '6';
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
            default_warehouse: {
                required: true
            },
            password: {
                required: true
            },
            confirm_password: {
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                number: true
            }

        },
        messages: {
            user_name_add: {
                remote: "Username already exists.",
                required: "Please enter username.",
                alphanumeric: 'Only alphabets and numeric values are allowed.'
            },
            combo1: {
                required: "Please select Province."
            },
            combo2: {
                required: "Please select District."
            },
            combo3: {
                required: "Please select Tehsil."
            },
            combo4: {
                required: "Please select UC."
            },
            phone: {
                required: "Please enter phone number.",
                number: "Only numbers are allowed."
            },
            default_warehouse: {
                required: "Select default warehouse."
            },
            password: {
                required: "Please enter password."
            },
            confirm_password: {
                equalTo: "Must be same as password."
            }

        }, 
        submitHandler: function (form) {
            $(".reload").trigger("click");
            $('#add').button('loading');
            $('#location_name_hdn').val($('#user_name_add').val());
            $('#action_hdn').val('1');
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-users/ajax-add-routine-user",
                data: $('#add-stores').serialize(),
                dataType: 'html',
                success: function (data) {
                    $('#add').button('reset');
                    $(".close").trigger("click");

                    $.ajax({
                        type: "POST",
                        url: appName + "/iadmin/manage-users/ajax-get-routine-table",
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
            url: appName + "/iadmin/manage-users/ajax-edit-routine",
            data: {wh_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);

                $('#update-button').show();
                setTimeout(function () {


                    $('#combo1_edit').val($('#province_id_edit').val());
                    if ($('#combo1_edit').val() != "") {
                        $('#loader').show();
                        $('#combo2_edit').empty();
                        $('#div_combo2_edit').hide();

                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-two",
                            data: {combo1: $('#province_id_edit').val(), office: '6'},
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
                            data: {combo2: $('#district_id_edit').val(), office: '6'},
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
                            data: {combo3: $('#tehsil_id_edit').val(), office: '6'},
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

                    if ($('#combo4_edit').val() != "") {
                        $.ajax({
                            type: "POST",
                            url: appName + "/iadmin/manage-users/get-default-warehouse",
                            data: {geo_level_id: '6', location_id: $('#parent_id_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#default_warehouse_update').html(data);
                                $('#default_warehouse_update').val($('#default_warehouse_update_hidden').val());
                            }
                        });
                    }


                }, 300);
            }

        });


    });

    $('[data-toggle="notyfy"]').click(function ()
    {
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
                            url: appName + "/iadmin/manage-users/delete",
                            data: {user_id: self.data('bind')},
                            dataType: 'html',
                            success: function (data) {
                                notyfy({
                                    force: true,
                                    text: 'User has been deleted!',
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

    $('#combo4_add').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-users/get-default-warehouse",
            data: {geo_level_id: '6', location_id: $('#combo4_add').val()},
            dataType: 'html',
            success: function (data) {
                $('#default_warehouse').html(data);
            }
        });
    });
    $('#sample_2').on('click', '.update-stores', function () {

        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-users/ajax-edit-routine",
            data: {wh_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);

                $('#update-button').show();
                setTimeout(function () {


                    $('#combo1_edit').val($('#province_id_edit').val());
                    if ($('#combo1_edit').val() != "") {
                        $('#loader').show();
                        $('#combo2_edit').empty();
                        $('#div_combo2_edit').hide();

                        $.ajax({
                            type: "POST",
                            url: appName + "/index/locations-combos-two",
                            data: {combo1: $('#province_id_edit').val(), office: '6'},
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
                            data: {combo2: $('#district_id_edit').val(), office: '6'},
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
                            data: {combo3: $('#tehsil_id_edit').val(), office: '6'},
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

                    if ($('#combo4_edit').val() != "") {
                        $.ajax({
                            type: "POST",
                            url: appName + "/iadmin/manage-users/get-default-warehouse",
                            data: {geo_level_id: '6', location_id: $('#parent_id_edit').val()},
                            dataType: 'html',
                            success: function (data) {
                                $('#default_warehouse_update').html(data);
                                $('#default_warehouse_update').val($('#default_warehouse_update_hidden').val());
                            }
                        });
                    }


                }, 300);
            }

        });


    });

    $('[data-toggle="notyfy"]').click(function ()
    {
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
                            url: appName + "/iadmin/manage-users/delete",
                            data: {user_id: self.data('bind')},
                            dataType: 'html',
                            success: function (data) {
                                notyfy({
                                    force: true,
                                    text: 'User has been deleted!',
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