$(function () {

    if ($("#activity_id").val() != '') {
        getProductsByStakeholder($("#activity_id").val());
    }

    $("#requisition_number").change(function () {
        if (this.value != "") {
            window.location = appName + '/stock-demand/requisition?id=' + this.value;
        } else {
            window.location = appName + '/stock-demand/requisition';
        }
    });

    $('#suggested_date').datepicker({
        minDate: 0,
        maxDate: "+5Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $('#activity_id').change(function (e) {
        getProductsByStakeholder($("#activity_id").val());
    });

    $('#period').change(function (e) {
        if ($("#item_id").val() == '' || $("#period").val() == '')
        {
            $("#remaining_balance_div").hide();
        }
        else
        {
            $("#remaining_balance_div").show();
        }
    });

    $('#item_id').change(function (e) {
        if ($("#item_id").val() == '' || $("#period").val() == '')
        {
            $("#remaining_balance_div").hide();
        }
        else
        {
            $("#remaining_balance_div").show();
            getProductAllocatedQtyAction($("#item_id").val(), $("#period").val());
        }

        getProductPairByProduct($("#item_id").val());
    });

    // validate signup form on keyup and submit
    $("#new_issue_form").validate({
        rules: {
            warehouse_name: {
                required: true
            },
            period: {
                required: true
            },
            activity_id: {
                required: true
            },
            quantity: {
                required: true
            },
            suggested_date: {
                required: true
            },
            quantity: {
                required: true
            },
            item_id: {
                required: true
            }

        },
        messages: {
            warehouse_name: {
                required: "Please select to store."
            },
            period: {
                required: "Please select period."
            },
            activity_id: {
                required: "Please select purpose."
            },
            quantity: {
                required: "Please enter quantity."
            },
            suggested_date: {
                required: "Suggested date is required."
            },
            quantity: {
                required: "Please enter quantity."
            },
            item_id: {
                required: "Please select product."
            }
        }
    });
});

$("#btn-loading").click(function (e) {
    e.preventDefault();
    var validator = $("#new_issue_form").validate();
    var quantity = $("#quantity").val();
    quantity = quantity.replace(/,/g, "");
    if (parseInt(quantity) <= 0) {
        validator.showErrors({
            "quantity": "Quantity should be greater than 0."
        });
    }
    else {
        $("#new_issue_form").submit();
    }
});

$('#quantity').priceFormat({
    prefix: '',
    thousandsSeparator: ',',
    suffix: '',
    centsLimit: 0,
    limit: 10
});

$("#print_requisition").click(function () {
    var id = $('#hdn_master_id').val();
    window.open(appName + '/stock-demand/print-requisition?id=' + id, '_blank', 'scrollbars=1,width=860,height=595');
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
                    window.location.href = appName + "/stock-demand/delete-requisition?id=" + id + "&p=issue";
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


var notification = [];
notification['confirm'] = 'Do you want to continue?';

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

function getProductPairByProduct(item_id) {
    $.ajax({
        type: "POST",
        url: appName + "/stock-demand/ajax-get-product-pair-by-product",
        data: {
            item_id: item_id
        },
        dataType: 'html',
        success: function (data) {
            $('#usage').html(data);
            $('#usage').css('backgroundColor', 'Green');
            $.cookie('blink_div_background_color', "usage");
            setTimeout(changeColor, 500);
        }
    });
}

function getProductAllocatedQtyAction(item_id, period) {
    $.ajax({
        type: "POST",
        url: appName + "/stock-demand/ajax-get-product-allocated-qty",
        data: {
            item_id: item_id,
            period: period
        },
        dataType: 'json',
        success: function (data) {
            $('#allocated_qty').val(data.allocated);
            $('#remaining_balance').val(data.remaining);
        }
    });
}




