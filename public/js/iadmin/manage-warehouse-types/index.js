$(function () {

$('#reset').click(function () {
    window.location.href = appName + '/iadmin/manage-warehouse-types/index';
});

    $('#resupply_interval, #reserved_stock, #usage_percentage, #list_rank').keydown(function (e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) { // if shift, ctrl or alt keys held down
            e.preventDefault();         // Prevent character input
        } else {
            var n = e.keyCode;
            if (!((n == 8)              // backspace
                    || (n == 9)                // Tab
                    || (n == 46)                // delete
                    || (n >= 35 && n <= 40)     // arrow keys/home/end
                    || (n >= 48 && n <= 57)     // numbers on keyboard
                    || (n >= 96 && n <= 105))   // number on keypad
                    ) {
                e.preventDefault();     // Prevent character input
            }
        }
    });


    // validate form on keyup and submit
    $("#form-add-warehouse-type").validate({
        rules: {
            warehouse_type_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-warehouse-types/check-warehouse-type",
                    type: "post",
                    data: {
                        warehouse_type_name_hidden: function () {
                            return '';
                        }
                    }

                }
            },
            warehouse_type_category: {
                required: true
            },
            geo_level: {
                required: true
            },
            resupply_interval: {
                required: true,
                number: true
            },
            reserved_stock: {
                required: true,
                number: true
            },
            usage_percentage: {
                required: true,
                number: true
            },
            list_rank: {
                required: true,
                number: true
            }
        },
        messages: {
            warehouse_type_name: {
                required: "Please enter warehouse type name.",
                remote: "Warehouse type name already exists."
            },
            warehouse_type_category: {
                required: "Please select Warehouse Type Category."
            },
            geo_level: {
                required: "Please select Geo Level."
            },
            resupply_interval: {
                required: "Please enter Resupply Interval."
            },
            reserved_stock: {
                required: "Please enter Reserved Stock."
            },
            usage_percentage: {
                required: "Please enter Usage Percentage."
            },
            list_rank: {
                required: "Please enter Rank"
            }

        }
    });

    // validate form on keyup and submit
    $("#form-update-warehouse-type").validate({
        rules: {
            warehouse_type_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-warehouse-types/check-warehouse-type",
                    type: "post",
                    data: {
                        warehouse_type_name_hidden: function () {
                            return $("#warehouse_type_name_hidden").val();
                        }
                    }

                }
            },
            warehouse_type_category: {
                required: true
            },
            geo_level: {
                required: true
            },
            resupply_interval: {
                required: true,
                number: true
            },
            reserved_stock: {
                required: true,
                number: true
            },
            usage_percentage: {
                required: true,
                number: true
            },
            list_rank: {
                required: true,
                number: true
            }
        },
        messages: {
            warehouse_type_name: {
                required: "Please enter warehouse type name.",
                remote: "Warehouse type name already exists."
            },
            warehouse_type_category: {
                required: "Please select Warehouse Type Category."
            },
            geo_level: {
                required: "Please select Geo Level."
            },
            resupply_interval: {
                required: "Please enter Resupply Interval."
            },
            reserved_stock: {
                required: "Please enter Reserved Stock."
            },
            usage_percentage: {
                required: "Please enter Usage Percentage."
            },
            list_rank: {
                required: "Please enter Rank"
            }
        }
    });
    $('#sample_2').on('click', '.btn-update-warehouse-type', function () {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-warehouse-types/ajax-warehouse-type-edit",
            data: {warehouse_type_id: $(this).attr('whtypeid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

//$( document ).on( "click", "a.active", function() {
//        var id = $(this).attr('id');
//        $.ajax({
//            type: "POST",
//            url: appName + "/iadmin/manage-geo-levels/ajax-change-status",
//            data: {id: id, ajaxaction: 'active'},
//            dataType: 'html',
//            success: function(data) {
//                $('#' + id).html(data);
//                $('#' + id).removeClass("active");
//                $('#' + id).addClass("deactive");
//            }
//        });
//    });
//
//     $( document ).on( "click", "a.deactive", function() {
//        var id = $(this).attr('id');
//        $.ajax({
//            type: "POST",
//            url: appName + "/iadmin/manage-geo-levels/ajax-change-status",
//            data: {id: id, ajaxaction: 'deactive'},
//            dataType: 'html',
//            success: function(data) {
//                $('#' + id).html(data);
//                $('#' + id).removeClass("deactive");
//                $('#' + id).addClass("active");
//            }
//        });
//    });

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
                            url: appName + "/iadmin/manage-warehouse-types/delete-warehouse-type",
                            data: {country_id: self.data('bind')},
                            dataType: 'html',
                            success: function (data) {
                                notyfy({
                                    force: true,
                                    text: 'Indicator has been deleted!',
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
                        /*   notyfy({
                         force: true,
                         text: '<strong>You clicked "Cancel" button<strong>',
                         type: 'error',
                         layout: self.data('layout')
                         });*/
                    }
                }]
        });
        return false;
    });

    $("#btn-add-warehouse-type").click(function () {
        var add_form = $("#form-add-warehouse-type");
        add_form.validate();
    });

    $("#btn-update-warehouse-type").click(function () {
        var update_form = $("#form-update-warehouse-type");
        update_form.validate();
    });

});