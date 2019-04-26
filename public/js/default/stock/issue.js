$(function () {

    $("#number").select2();

    $("#issue-print").trigger("click");

    if ($("#activity_id").val() != '') {
        getProductsByStakeholder($("#activity_id").val());
    }

    $('#item_id').change(function () {
        $('#vvm_stage').html('');
        $("#available_quantity").val('');
        $("#expiry_date").val('');
        $('#number').html('');
        $("#number").select2("val", "Select");
        $('#product-unit').html('');
        var activity_id = $('#activity_id').val();

        getProductPairByProduct($(this).val());

        if ($(this).val() != "") {


            $.ajax({
                type: "POST",
                url: appName + "/stock-batch/ajax-product-category",
                data: {item_id: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    if (data == 3) {
                        $('#manufacturer_div').html("Manufacturer |");
                    } else {
                        $('#manufacturer_div').html("");
                    }
                    if (data == 2) {
                        $("#expiry_div").hide();
                    } else {
                        $("#expiry_div").show();
                    }
                }
            });

            $.ajax({
                type: "POST",
                url: appName + "/stock-batch/ajax-running-batches",
                data: {item_id: $(this).val(), page: 'issue', transaction_date: $('#transaction_date').val()},
                dataType: 'html',
                success: function (data) {

                    $('#number').html(data);
                }
            });
            $.ajax({
                type: "POST",
                url: appName + "/stock-batch/ajax-product-batches",
                data: {item_id: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    $('#product-unit').html(data);
                }
            });



            if (activity_id == 2) {
                $.ajax({
                    type: "POST",
                    url: appName + "/stock/ajax-get-campaigns-by-product",
                    data: {
                        warehouse_id: $('#warehouse').val(), item_id: $(this).val()
                    },
                    dataType: 'html',
                    success: function (data) {
                        $('#campaign_id').html(data);
                        $('#campaign_id').css('backgroundColor', 'Green');
                        $.cookie('blink_div_background_color', "campaign_id");
                        setTimeout(changeColor, 500);
                    }
                });
            }

            if ($('#hdn_activity_id').val() == 2) {
                $.ajax({
                    type: "POST",
                    url: appName + "/stock/ajax-get-campaigns-by-product",
                    data: {
                        warehouse_id: $('#hdn_to_warehouse_id').val(), item_id: $(this).val()
                    },
                    dataType: 'html',
                    success: function (data) {
                        $('#campaign_id').html(data);
                        $('#campaign_id').css('backgroundColor', 'Green');
                        $.cookie('blink_div_background_color', "campaign_id");
                        setTimeout(changeColor, 500);
                    }
                });
            }
        }
    });

    $('#number').click(function () {
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-available-batch-quantity",
            data: {batch: $('#number').val(), tr_date: $("#transaction_date").val(), page: 'issue'},
            dataType: 'html',
            success: function (data) {
                $('#itembatches').html(data);
            }
        });

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-available-vvm-stages",
            data: {batch: $('#number').val(), page: 'issue'},
            dataType: 'html',
            success: function (data) {
                $('#vvm_stage_lable').html(data);
            }
        });
    });

    $('#warehouse').change(function () {

        if ($('#activity_id').val() == 2) {
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-get-campaigns-by-product",
                data: {
                    warehouse_id: $('#warehouse').val(), item_id: $('#item_id').val()
                },
                dataType: 'html',
                success: function (data) {
                    $('#campaign_id').html(data);
                    $('#campaign_id').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "campaign_id");
                    setTimeout(changeColor, 500);
                }
            });
        }
    });
    $('#transaction_date').change(function () {
        $('#item_id').val("");
        $("#number").select2("val", "");
        $('#vvm_stage').html("NA");
        $('#available_quantity').val("");
        $('#expiry_date').val("");

        getProductsByStakeholder($("#activity_id").val());
    });


    //  }
    $('#transaction_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'd/m/Y h:i A',
        maxDate: 0,
        minDate: '2013/01/01'
    });

    $("#expiry_date").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#issue_from").datepicker({
        minDate: "-1Y",
        maxDate: "+2Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#issue_to").datepicker({
        minDate: "-1Y",
        maxDate: "+2Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $('#expiry_date').mask('00/00/0000');

//    $('#quantity, #item_id').change(function(e) {
//        var quantity = $('#quantity').val();
//        var itemId = $('#item_id').val();
//        if (quantity !== 0 && quantity !== '' && itemId !== '') {
//            $.ajax({
//                type: "POST",
//                url: appName + "/stock-batch/ajax-product-category",
//                data: 'quantity=' + quantity + '&itemId=' + itemId,
//                success: function(doses) {
//                    if (doses != '')
//                    {
//                        $('#product-unit').css('display', 'table-row');
//                        $('#product-unit').html(doses);
//                    }
//                }
//            });
//        }
//    });
    $("#issue_period").change(function () {
        var option = $(this).val();
        if (option == 'custom') {
            $("#issue_period_date").show();
        } else {
            $("#issue_period_date").hide();
        }
    });
    $("#print_issue2").click(function () {
        window.open('stock_issue_print', '_blank', 'width=860,height=595');
    }
    );
    $("#wh_link").click(function () {
        var wh_id;
        wh_id = $("#warehouse").val();
        window.open('coldchain_show_list.php?wh_id=' + wh_id, '_blank', 'width=860,height=595');
    }
    );
    /*
     * 
     * Batch and Quantity on Stock Issue is Temporarily disabled by Abdul Qadir as per Mr. Wasif instruction
     * Dated: 06-08-2014
     * 
     * 
     $.inlineEdit({
     quantity: appName + "/stock/ajax-inline-edit?type=quantity&adjustment_type=2&id=",
     batch: appName + "/stock/ajax-inline-edit?type=batch&adjustment_type=2&id="
     }, {
     animate: true,
     filterElementValue: function($o) {
     return $o.html().trim();
     },
     afterSave: function() {
     }
     });
     */
    $('#quantity').priceFormat({
        prefix: '',
        thousandsSeparator: ',',
        suffix: '',
        centsLimit: 0,
        limit: 10
    });
    $('#warehouse').change(function () {
        var warehouse = $(this).val();
        $('#wh_link').show();
        $('#wh_button').html('<a href="#?wh_id=' + warehouse + '"><button type="button" class="btn btn-info" name="wh_link" id="wh_link">Cold chain</button></a>');
    });
    $('#batch_no').change(function () {
        //$objStockBatch->FindItemQtyByBatchId();
    });
    $('[data-toggle="notyfy"]').click(function ()
    {
        $.notyfy.closeAll();
        var self = $(this);
        notyfy({
            text: notification[self.data('type')],
            type: self.data('type'),
            dismissQueue: true,
            layout: self.data('layout'),
            buttons: (self.data('type') != 'confirm') ? false : [{
                    addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                    text: '<i></i> Ok',
                    onClick: function ($notyfy) {
                        var id = self.attr("id");

                        $notyfy.close();
//                       $.ajax({
//                            type: "POST",
//                            url: appName + "/stock/delete-issue?id=" + id + "&p=issue",
//                            data: {make_id: self.data('bind')},
//                            dataType: 'html',
//                            success: function(data) {
//                                notyfy({
//                                    force: true,
//                                    text: 'Make has been deleted!',
//                                    type: 'success',
//                                    layout: self.data('layout')
//                                });
//                                self.closest("tr").remove();
//                            }
//                        });
                        window.location.href = appName + "/stock/delete-issue?id=" + id + "&p=issue";
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
                         }); */
                    }
                }]
        });
        return false;
    });
});

var notification = [];
notification['confirm'] = 'Do you want to continue?';

$("#btn-loading").click(function (e) {
    e.preventDefault();
    var validator = $("#new_issue_form").validate();
    var aval_qty = $("#available_quantity").val();
    var quantity = $("#quantity").val();
    aval_qty = aval_qty.replace(/,/g, "");
    quantity = quantity.replace(/,/g, "");
    if (parseInt(quantity) <= 0) {
        validator.showErrors({
            "quantity": "Quantity should greater than 0"
        });
    } else if (parseInt(aval_qty) < parseInt(quantity)) {
        validator.showErrors({
            "quantity": "Quantity not available"
        });
    } else {
        $("#new_issue_form").submit();
    }
});

$('#vvm_stage').change(function (e) {
    if ($(this).val() == 3) {
        alert("VVM Stage 3 Not Usable");
    } else if ($(this).val() == 4) {
        alert("VVM Stage 4 Not Usable");
    }

});
$('#quantity, #item_id').change(function (e) {

    var quantity = $('#quantity').val();
    var itemId = $('#item_id').val();

    $('#product-doses').css('display', 'none');

    if (quantity != 0 && quantity != '' && item_id != '')
    {
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-product-cat-and-doses",
            data: 'quantity=' + quantity + '&itemId=' + itemId,
            success: function (doses) {
                if (doses != '')
                {
                    $('#product-doses').css('display', 'table-row');
                    $('#product-doses').html(doses);
                }
            }
        });
    }
});

// validate signup form on keyup and submit
$("#new_issue_form").validate({
    rules: {
        item_id: {
            required: true
        },
        number: {
            required: true
        },
        quantity: {
            required: true
        },
        production_date: {
            required: true
        },
        office: {
            required: true
        },
        combo1: {
            required: true
        },
        warehouse: {
            required: true,
            custom_warehouse: true
        },
        activity_id: {
            required: true
        },
        transaction_reference: {
            refnum: true
        }
    },
    messages: {
        item_id: {
            required: "Please select product"
        },
        number: {
            required: "Please select batch number"
        },
        quantity: {
            required: "Please enter quantity"
        },
        production_date: {
            required: "Production date is required"
        },
        office: {
            required: "Please select office"
        },
        combo1: {
            required: "Please select store"
        },
        warehouse: {
            required: "Please select store"
        },
        activity_id: {
            required: "Please select purpose"
        },
        transaction_reference: {
            refnum: "Only these characters are allowed: Alphanumeric space * , .-_=/# |()"
        }
    },
    submitHandler: function (form) {
        // $('#btn-loading')
        $('#btn-loading').attr('disabled', 'disabled');
        form.submit();
    }
});

/* Characters allowed for Reference No: 
 * Alphanumeric
 * Space
 * ,
 * .
 * -
 * _
 * =
 * /
 * # 
 * |
 * (
 * )
 */

$.validator.addMethod("refnum", function (value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z0-9-\s\#\_/,/./=/|/(/)]+$/);
});

// validate signup form on keyup and submit
$("#issue_stock").validate({
    rules: {
        issue_from: {
            required: true
        },
        issue_to: {
            required: true
        }
    },
    messages: {
        issue_from: {
            required: "Please select issue from"
        },
        issue_to: {
            required: "Please select issue to"
        }
    }
});

$('#activity_id').change(function (e) {
    $("#number").select2("val", "");
    $('#available_quantity').val('');
    $('#expiry_date').val('');
    var activity_id = $('#activity_id').val();
    if (activity_id == 2) {
        $('#div_campaign_id').show('slow');
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-get-campaigns-by-product",
            data: {
                warehouse_id: $("#warehouse").val(), item_id: $("#item_id").val()
            },
            dataType: 'html',
            success: function (data) {
                $('#campaign_id').html(data);
                $('#campaign_id').css('backgroundColor', 'Green');
                $.cookie('blink_div_background_color', "campaign_id");
                setTimeout(changeColor, 500);
            }
        });

    } else {
        $('#div_campaign_id').hide('slow');
    }

    getProductsByStakeholder(activity_id);
});

function getProductsByStakeholder(activity_id) {
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-get-products-by-stakeholder-activity",
        data: {
            activity_id: activity_id, type: 2, tran_date: $("#transaction_date").val()
        },
        dataType: 'html',
        success: function (data) {
            $('#item_id').html(data);
            $('#item_id').css('backgroundColor', 'Green');
            $.cookie('blink_div_background_color', "item_id");
            setTimeout(changeColor, 500);
        }
    });
}

$.validator.addMethod("custom_warehouse", function (value, element) {
    var curr_value = $("#curr_wh").val();
    return curr_value != value;
}, "Please select another store");

$("#print_issue").click(function () {
    var id = $('#hdn_master_id').val();
    window.open(appName + '/stock/print-issue?id=' + id, '_blank', 'scrollbars=1,width=860,height=595');
});

$("#print_issue1").click(function () {
    var id = $('#hdn_master_id1').val();
    window.open(appName + '/stock/print-issue?id=' + id, '_blank', 'scrollbars=1,width=860,height=595');
});


$("span[id$='-stockedit']").click(function () {
    var value = $(this).attr("id");
    var id = value.replace("-stockedit", "");
    document.location = appName + "/stock/issue/id/" + id;
});

$("#whchange").click(function () {
    $("#whcancel").show();
    $("#whchange").hide();
    $("#wh_change_div").show();
});

$("#whcancel").click(function () {
    $("#whchange").show();
    $("#whcancel").hide();
    $("#wh_change_div").hide();
});

function getProductPairByProduct(item_id) {
    $.ajax({
        type: "POST",
        url: appName + "/stock-demand/ajax-get-product-pair-by-product",
        data: {
            item_id: item_id
        },
        dataType: 'html',
        success: function (data) {
            $('#used_for').html(data);
            $('#used_for').css('backgroundColor', 'Green');
            $.cookie('blink_div_background_color', "usage");
            setTimeout(changeColor, 500);
        }
    });
}