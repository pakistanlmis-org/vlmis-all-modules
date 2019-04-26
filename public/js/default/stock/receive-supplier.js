$(function () {

    $("#receive-print").trigger("click");

    $('#transaction_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'd/m/Y h:i A',
        maxDate: 0,
        minDate: "2013/01/01"
    });

    /*$('#transaction_date').datetimepicker({
     format: "dd/mm/yyyy HH:ii P",
     showMeridian: true,
     autoclose: true,
     startDate: "dd/mm/yyyy 10:00",
     todayBtn: true,
     changeMonth: true,
     changeYear: true
     });*/

    $("#expiry_date").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: $("#defaultdate").val()
    });

    $("#production_date").datepicker({
        minDate: "-5Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: $("#defaultdate").val()
    });

    $('#expiry_date, #production_date').mask('00/00/0000');

    $("#item_id").change(function () {
        var quantity = $('#quantity').val();
        var item_id = $('#item_id').val();
        var activity_id = $('#activity_id').val();

        getProductPairByProduct(item_id);

        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-check-product-category",
            data: {
                item_id: item_id
            },
            dataType: 'html',
            success: function (data) {
                $('#cold_chain').html('<option value="" selected="selected">Select</option>');
                if (data == 1) {
                    $("#unit_price").rules("add", {
                        min: '0.01'
                    });


                    $("#expiry_date").rules("add", "required");

                    $("#vvmtype_div").show();
                    $("#vvmstage_div").show();
                } else {
                    $("#unit_price").rules("add", {
                        min: '0'
                    });
                    $("#expiry_date").rules("remove", "required");
                    $("#vvmtype_div").hide();
                    $("#vvmstage_div").hide();
                }

                $.ajax({
                    type: "POST",
                    url: appName + "/stock-batch/ajax-placement-locations",
                    data: {product: item_id},
                    dataType: 'html',
                    success: function (data) {
                        $('#cold_chain').html(data);
                    }
                });
            }
        });



        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-product-batches",
            data: {
                item_id: item_id
            },
            dataType: 'html',
            success: function (data) {
                $('#product-unit').html(data);
//                if (data == '2') {
//                    $("#expiry_date").rules("remove", "required");
//                    $("#vvmtype_div").hide();
//                    $("#vvmstage_div").hide();
//                } else {
//                    $("#expiry_date").rules("add", "required");
//                    $("#vvmtype_div").show();
//                    $("#vvmstage_div").show();
//                }
            }
        });

        if (activity_id == 2) {
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-get-campaigns-by-product",
                data: {
                    item_id: item_id
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

        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-get-manufacturer-by-product",
            data: {
                item_id: item_id
            },
            dataType: 'html',
            success: function (data) {
                $('#manufacturer_id').html(data);
            }
        });

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-product-vvm-stages",
            data: {product: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#vvm_stage').html(data);
            }
        });

    });

    $.inlineEdit_receive_supplier({
        quantity: appName + "/stock/ajax-inline-edit?type=quantity&adjustment_type=1&id=",
        batch: appName + "/stock/ajax-inline-edit?type=batch&adjustment_type=1&id="
    }, {
        animate: false,
        filterElementValue: function ($o) {
            return $o.html().trim();
        },
        afterSave: function () {
        }

    });

    $('#unit_price').priceFormat({
        prefix: '',
        thousandsSeparator: '',
        suffix: '',
        centsLimit: 2
    });

    $('[data-toggle="notyfy"]').click(function () {
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
                        var id = self.attr("id");
                        $notyfy.close();
                        window.location.href = appName + '/stock/delete?id=' + id;
                    }
                },
                {
                    addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                    text: '<i></i> Cancel',
                    onClick: function ($notyfy) {
                        $notyfy.close();
                        /*  notyfy({
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

    $.validator.setDefaults({
        ignore: ':hidden, [readonly=readonly]'
    });

});

var notification = [];
notification['confirm'] = 'Do you want to continue?';

$('#quantity').priceFormat({
    prefix: '',
    thousandsSeparator: ',',
    suffix: '',
    centsLimit: 0,
    limit: 10
});

$("#print_vaccine_placement").click(function (e) {
    var id = $('#stockid').val();
    window.open(appName + '/stock/print-receive?id=' + id, '_blank', 'scrollbars=1,width=860,height=595');
});

$("#print_vaccine_placement1").click(function (e) {
    var id = $('#stockid1').val();
    window.open(appName + '/stock/print-receive?id=' + id, '_blank', 'scrollbars=1,width=860,height=595');
});

$('#quantity').change(function (e) {
    var quantity = $('#quantity').val();
    var item_id = $('#item_id').val();

    $('#product-doses').css('display', 'none');

    if (quantity != 0 && quantity != '' && item_id != '')
    {
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-product-cat-and-doses",
            data: 'quantity=' + quantity + '&itemId=' + item_id,
            success: function (doses) {
                if (doses != '') {
                    $('#product-doses').css('display', 'table-row');
                    $('#product-doses').html(doses);
                }
            }
        });
    }


});

$('#item_id').change(function (e) {
    var quantity = $('#quantity').val();
    var item_id = $('#item_id').val();

    $('#product-doses').css('display', 'none');

    if (quantity != 0 && quantity != '' && item_id != '')
    {
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-product-cat-and-doses",
            data: 'quantity=' + quantity + '&itemId=' + item_id,
            success: function (doses) {
                if (doses != '') {
                    $('#product-doses').css('display', 'table-row');
                    $('#product-doses').html(doses);
                }
            }
        });

        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-get-campaigns-by-product",
            data: {
                item_id: item_id
            },
            dataType: 'html',
            success: function (data) {
                $('#campaign_id').html(data);
            }
        });
    }
});
$("#transaction_reference").change(function () {
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-check-reference",
        data: {
            ref: $(this).val()
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

$.validator.addMethod("custom_alphanumeric", function (value, element) {
    return this.optional(element) || value === "NA" || value.match(/^[a-zA-Z0-9-_/]+$/);
}, "Letters, numbers, hyphen and underscores only please");

$.validator.addMethod("positive_integer", function (value, element) {
    return this.optional(element) || value === "NA" || value.match(/^[1-9]\d*$/);
}, "Positive numbers only please");

$('#number').keyup(function () {
    $(this).val($(this).val().toUpperCase());
});

// validate signup form on keyup and submit
$("#new_receive").validate({
    rules: {
        'item_id': {
            required: true
        },
        'number': {
            required: true,
            custom_alphanumeric: true
        },
        'quantity': {
            required: true
        },
        'transaction_reference': {
            required: true,
            refnum: true
        },
        'from_warehouse_id': {
            required: true
        },
        'expiry_date': {
            required: true
        },
        'activity_id': {
            required: true
        },
        manufacturer_id: {
            required: true
        },
        cold_chain: {
            required: true
        },
        vvm_stage: {
            required: true
        },
        vvm_type_id: {
            required: true
        },
        unit_price: {
            required: true

        }

    },
    messages: {
        'transaction_reference': {
            required: "Please enter reference number",
            refnum: "Only these characters are allowed: Alphanumeric space * , .-_=/# |()"
        },
        'item_id': {
            required: "Please select product"
        },
        'number': {
            required: "Please enter batch number"
        },
        'quantity': {
            required: "Please enter quantity",
            min: "Quantity should be greater than 0"
        },
        'expiry_date': {
            required: "Expiry date is required"
        },
        'from_warehouse_id': {
            required: "Please select funding source"
        },
        'activity_id': {
            required: "Please select purpose"
        },
        manufacturer_id: {
            required: "Please select manufacturer"
        },
        cold_chain: {
            required: "Please select placement location"
        },
        vvm_stage: {
            required: "Please select VVM stage"
        },
        vvm_type_id: {
            required: "Please select VVM type"
        },
        unit_price: {
            required: "Please enter unit price"
        }
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

$('#activity_id').change(function (e) {
    var activity_id = $('#activity_id').val();
    if (activity_id == 2) {
        $('#div_campaign_id').show('slow');
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-get-campaigns-by-product",
            data: {
                item_id: $('#item_id').val()
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
    //get products by stakeholder activity
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-get-products-by-stakeholder-activity",
        data: {
            activity_id: activity_id, type: 2
        },
        dataType: 'html',
        success: function (data) {
            $('#item_id').html(data);
            $('#item_id').css('backgroundColor', 'Green');
            $.cookie('blink_div_background_color', "item_id");
            setTimeout(changeColor, 500);
        }
    });

});

$('#vvm_stage').change(function (e) {
    if ($(this).val() == 3) {
        alert("VVM stage is not usable. Are you sure you want to continue?");
    } else if ($(this).val() == 4) {
        alert("VVM stage is not usable. Are you sure you want to continue?");
    }
});


$("#new-manufacturer-popup").validate({
    rules: {
        manufacturer_name: {
            required: true
        },
        quantity_per_pack: {
            required: true,
            positive_integer: true
        }
    },
    messages: {
        manufacturer_name: {
            required: "Please enter manufacturer name"
        },
        quantity_per_pack: {
            required: "Please enter Quantity Per Pack",
            positive_integer: "Positive numbers only please"
        }
    }
});

$('#save').click(function (e) {
    e.preventDefault();
    var manufacture_name = $("#manufacturer_name").val();
    var quantity_per_pack = $("#quantity_per_pack").val();
    if (manufacture_name != '' && quantity_per_pack != '') {
        $.ajax({
            type: "POST",
            url: appName + "/stock/add-new-manufacturer",
            data: {name: manufacture_name, quantity: quantity_per_pack, item_id: $("#item_id").val()},
            dataType: 'html',
            success: function (data) {
                $(".close").trigger("click");
                $('#manufacturer_id').html(data);
            }
        });
    }
});

$("span[id$='-stockedit']").click(function () {
    var value = $(this).attr("id");
    var id = value.replace("-stockedit", "");
    document.location = appName + "/stock/receive-supplier/id/" + id + "/t/r";
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