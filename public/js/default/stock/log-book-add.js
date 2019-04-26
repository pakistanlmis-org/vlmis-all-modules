
$(function () {

    var initTable2 = function () {
        var oTable = $('#sample_2').dataTable({
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
    };





    $('#province').change(function () {
        if ($(this).val() == "") {
            $("#district").empty();
            $("#tehsil").empty();
            $("#uc").empty();
        }

        if ($(this).val() != "") {

            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-get-districts",
                data: {province_id: $('#province').val()},
                dataType: 'html',
                success: function (data) {
                    $("#district").html(data);
                }
            });
        }
    });

    $('#province').val(2);
    $('#province').trigger("change");

    $('#district').change(function () {
        if ($(this).val() == "") {
            $("#tehsil").empty();
            $("#uc").empty();
        }

        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-get-tehsils",
                data: {district_id: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    $("#tehsil").html(data);
                }
            });
        }
    });

    $('#tehsil').change(function () {
        if ($(this).val() == "") {
            $("#uc").empty();
        }

        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-get-ucs-by-tehsil",
                data: {tehsil_id: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    $("#uc").html(data);
                }
            });
        }
    });

    $('#btn-loading').click(function () {
        var validator = $("#log_book_add").valid();
        if (validator)
        {
            Metronic.startPageLoading('Please wait...');
            var form_data = $("#log_book_add").serialize();
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-display-log-book-result",
                data: form_data,
                success: function (data) {
                    Metronic.stopPageLoading();
                    $("#log_book_result").html(data);
                    initTable2();

                    $(document).on('click', '[data-toggle="notyfy"]', function () {
                        $.notyfy.closeAll();
                        var self = $(this);
//
//                        notyfy({
//                            text: notification[self.data('type')],
//                            type: self.data('type'),
//                            dismissQueue: true,
//                            layout: self.data('layout'),
//                            buttons: (self.data('type') != 'confirm') ? false : [
//                                {
//                                    addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
//                                    text: '<i></i> Ok',
//                                    onClick: function ($notyfy) {
//                                        Metronic.startPageLoading('Please wait...');
//                                        var id = self.attr("id");
//                                        $notyfy.close();
//                                        $.ajax({
//                                            type: "POST",
//                                            url: appName + "/stock/delete-log-book",
//                                            data: {id: id},
//                                            success: function (data) {
//                                                Metronic.stopPageLoading();
//                                                if (data == 1) {
//                                                    self.closest("tr").remove();
//                                                    notyfy({
//                                                        force: true,
//                                                        text: '<strong>Deleted successfully!<strong>',
//                                                        type: 'success',
//                                                        layout: self.data('layout')
//                                                    });
//                                                } else {
//                                                    notyfy({
//                                                        force: true,
//                                                        text: '<strong>An error occur! Try later.<strong>',
//                                                        type: 'error',
//                                                        layout: self.data('layout')
//                                                    });
//                                                }
//                                            }
//                                        });
//
//                                        //window.location.href = appName + '/stock/delete-issue?p=stock&id=' + id;
//                                    }
//                                },
//                                {
//                                    addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
//                                    text: '<i></i> Cancel',
//                                    onClick: function ($notyfy) {
//                                        $notyfy.close();
//                                        /*   notyfy({
//                                         force: true,
//                                         text: '<strong>You clicked "Cancel" button<strong>',
//                                         type: 'error',
//                                         layout: self.data('layout')
//                                         });*/
//                                    }
//                                }
//                            ]
//                        });
                        return false;
                    });

                    var notification = [];
                    notification['confirm'] = 'Do you want to continue?';

                }
            });
            $("#name").val("");
            $("#father_name").val("");
            $("#age").val("");
            $("#contact").val("");
            $("#address").val("");
            $("#remarks").val("");

        }


    });

    $("#log_book_add").validate({
        rules: {
            name: {
                required: true,
                alphaspace: true,
                minlength: 3
            },
            father_name: {
                required: true,
                alphaspace: true,
                minlength: 3
            },
            age: {
                required: true,
                min: 0,
                max: 23
            },
            gender: {
                required: true
            },
            contact: {
                
                number: true
            },
            address: {
                required: true,
                alphanumspacehash: true
            },
            remarks: {
                alphanumspace: true,
                maxlength: 300
            },
            province: {
                required: true
            },
            district: {
                required: true
            },
            tehsil: {
                //required: true
            },
            uc: {
                //required: true
            },
            day: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please enter name",
                alphaspace: "Please enter alphabets only"
            },
            father_name: {
                required: "Please enter father name",
                alphaspace: "Please enter alphabets only"
            },
            age: {
                required: "Please enter age",
                min: "Please enter a positive value"
            },
            gender: {
                required: "Please select gender"
            },
            contact: {
                
                number: "Please enter numbers only"
            },
            address: {
                required: "Please enter address",
                alphanumspacehash: "Please alphabets, numbers, comma and number sign only"
            },
            remarks: {
                alphanumspace: "Please alphabets and numbers only",
                maxlength: "Please enter less than 300 characters"
            },
            province: {
                required: "Please select province"
            },
            district: {
                required: "Please select district"
            },
            tehsil: {
                //required: "Please select tehsil"
            },
            uc: {
                //required: "Please select union council"
            },
            day: {
                required: "Please select day"
            }
        }
    });


    $.validator.addMethod("alphaspace", function (value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    });

    $.validator.addMethod("alphanumspace", function (value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z0-9\-\s]+$/);
    });
    //return this.optional(element) || value === "NA" || value.match(/^[a-zA-Z0-9-_/]+$/);
    $.validator.addMethod("alphanumspacehash", function (value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z0-9-\s\#\_/,]+$/);
    });

//    function doseValidation()
//    {
//        var q = 0;
//        var inp = $('.qty');
//        for (var i = 0; i < inp.length; i++) {
//            if (inp[i].value != '') {
//                if (parseFloat(inp[i].value) < parseFloat(inp[i].getAttribute('min')))
//                {
//                    alert('Quantity can not be less than ' + inp[i].getAttribute('min'));
//                    inp[i].focus();
//                    return false;
//                }
//                else if (parseFloat(inp[i].value) > parseFloat(inp[i].getAttribute('max'))) {
//                    alert('Quantity can not be greater than ' + inp[i].getAttribute('max'));
//                    inp[i].focus();
//                    return false;
//                }
//            }
//        }
//    }

    $(document).on('click', '[data-toggle="notyfy"]', function () {
        //$('[data-toggle="notyfy"]').click(function () {
        $.notyfy.closeAll();
        var self = $(this);

        notyfy({
            text: notification[self.data('type')],
            type: self.data('type'),
            dismissQueue: true,
            layout: self.data('layout'),
            buttons: (self.data('type') != 'confirm') ? false : [
                {
                    addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                    text: '<i></i> Ok',
                    onClick: function ($notyfy) {
                        Metronic.startPageLoading('Please wait...');
                        var id = self.attr("id");
                        $notyfy.close();
                        $.ajax({
                            type: "POST",
                            url: appName + "/stock/delete-log-book",
                            data: {id: id},
                            success: function (data) {
                                Metronic.stopPageLoading();
                                if (data == 1) {
                                    self.closest("tr").remove();
                                    notyfy({
                                        force: true,
                                        text: '<strong>Deleted successfully!<strong>',
                                        type: 'success',
                                        layout: self.data('layout')
                                    });
                                } else {
                                    notyfy({
                                        force: true,
                                        text: '<strong>An error occur! Try later.<strong>',
                                        type: 'error',
                                        layout: self.data('layout')
                                    });
                                }
                            }
                        });

                        //window.location.href = appName + '/stock/delete-issue?p=stock&id=' + id;
                    }
                },
                {
                    addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                    text: '<i></i> Cancel',
                    onClick: function ($notyfy) {
                        $notyfy.close();
                        /*   notyfy({
                         force: true,
                         text: '<strong>You clicked "Cancel" button<strong>',
                         type: 'error',
                         layout: self.data('layout')
                         });*/
                    }
                }
            ]
        });
        return false;
    });

    var notification = [];
    notification['confirm'] = 'Do you want to continue?';


    $("#father_name").change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-check-logbook-name",
            data: {
                name: $('#name').val(),
                fname: $('#father_name').val(),
                do: $('#do').val()
            },
            dataType: 'html',
            success: function (data) {
                if (data == 1) {
                    $("#reference_tooltip").show();
                    $(".tooltips").trigger("mouseover");
                    setTimeout(hideTooltip, 5000);
                } else {
                    $("#reference_tooltip").hide();
                }
            }
        });
    });

    function hideTooltip() {
        $(".tooltips").trigger("mouseout");
    }
});