$(function() {
    $('#adjustment_date').datepicker({
        minDate: "-2Y",
        maxDate: 0,
        dateFormat: "dd/mm/yy",
        constrainInput: false,
        changeMonth: true,
        changeYear: true
    });

    $('#adjustment_date').mask('00/00/0000');

});

$('#add-rows').hide();
//var form_clean;
var interval = null;

$(function() {

    refreshDateFields();

    $("select[id$='-number']").click(function() {
        prev_val = $(this).val();
    }).change(function() {
        var str = $(this).attr("id");
        var row = str.replace("-number", "");

        if ($("#transaction_date").val() == '') {
            alert("Please select transaction date");
            $(this).val(prev_val);
            return false;
        }

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-available-batch-quantity",
            data: {batch: $(this).val(), tr_date: $("#transaction_date").val(), type: 'json'},
            dataType: 'json',
            success: function(data) {
                $("#" + row + "-ava_qty").val(data.qty);
                $("#" + row + "-expiry_date").val(data.date);
            }
        });
    });

    $("select[id$='-item_id']").change(function() {
        var str = $(this).attr("id");
        var row = str.replace("-item_id", "");

        $("#" + row + "-ava_qty").val('');
        $("#" + row + "-expiry_date").val('');
        $("#" + row + "-number").html('');

        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/stock-batch/ajax-running-batches",
                data: {item_id: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $("#" + row + "-number").html(data);
                }
            });
        }
    });

    $('#activity_id').change(function(e) {
        $('#number').empty();
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
                success: function(data) {
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

    //form_clean = $("#stock_issue").serialize();

    // Auto Save function call
    //interval = setInterval(autoSave, 20000);

    $('#transaction_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'd/m/Y h:i A',
        maxDate: 0
    });

    $("#default_counter").priceFormat({
        prefix: '',
        thousandsSeparator: '',
        suffix: '',
        centsLimit: 0,
        limit: 2
    });

    // validate signup form on keyup and submit
    $("#stock_issue").validate({
        rules: {
            office: {
                required: true
            },
            combo1: {
                required: true
            },
            combo2: {
                required: true
            },
            warehouse: {
                required: true
            },
            stakeholder_activity_id: {
                required: true
            },
            transaction_date: {
                required: true
            }
        },
        messages: {
            office: {
                required: "Please select office"
            },
            combo1: {
                required: "Please select province"
            },
            combo2: {
                required: "Please select district"
            },
            warehouse: {
                required: "Please select store"
            },
            stakeholder_activity_id: {
                required: "Please select purpose"
            },
            transaction_date: {
                required: "Please select transaction date"
            }
        }
    });

    $("input[id$='-quantity']").change(function() {
        var str = $(this).attr("id");
        var row = str.replace("-quantity", "");

        var aval_qty = $("#" + row + "-ava_qty").val();
        aval_qty = aval_qty.replace(/,/g, "");

        var quantity = $(this).val();
        quantity = quantity.replace(/,/g, "");

        //var qty123 = new String($(this).attr("name"));

        if (parseInt(quantity) <= 0) {
            alert("Quantity should greater then 0");
            $(this).focus();
        } else if (parseInt(aval_qty) < parseInt(quantity)) {
            alert("Quantity not available");
            $(this).focus();
        }
    });


    var prev_val;
    $("#stakeholder_activity_id").click(function() {
        prev_val = $(this).val();
    }).change(function() {
        var sel_opt = $("#stakeholder_activity_id option:selected").text();
        if (confirm('Do you want to continue with ' + sel_opt + ' products.')) {
            Metronic.startPageLoading('Please wait...');
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-get-products-by-stakeholder-activity",
                data: {activity_id: $(this).val(), type: 1},
                dataType: 'html',
                success: function(data) {
                    data = "<option val=''>Select</option>" + data;
                    $('.products').html(data);
                    $('.manufaturers').html("<option val=''>Select</option>");
                    Metronic.stopPageLoading();
                }
            });
        } else {
            $("#stakeholder_activity_id").val(prev_val);
        }
    });

    $("#add_more").click(function() {
        Metronic.startPageLoading('Please wait...');

        var start = 0;
        var end = 0;
        var default_cntr = $("#default_counter").val();

        $("#add-rows").show();
        start = $('.table tr.dynamic-rows').length;
        end = parseInt(start) + parseInt(default_cntr);

        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-add-more-adjustment-rows",
            data: {start: start, end: end},
            dataType: 'html',
            success: function(data) {
                $('tbody').append(data);
                $("#counter").val(end);
                $.ajax({
                    type: "POST",
                    url: appName + "/stock/ajax-get-products-by-stakeholder-activity",
                    data: {
                        activity_id: $("#stakeholder_activity_id").val(), type: 1
                    },
                    dataType: 'html',
                    success: function(combodata) {
                        combodata = "<option val=''>Select</option>" + combodata;
                        for (var i = start; i < end; i++) {
                            $("#rows" + i + "-item_pack_size_id").html(combodata);
                        }
                    }
                });
                Metronic.stopPageLoading();
                refreshDateFields();
            }
        });
    });
});

function refreshDateFields() {

    $("input[id$='-quantity']").priceFormat({
        prefix: '',
        thousandsSeparator: ',',
        suffix: '',
        centsLimit: 0,
        limit: 10
    });

    $("#add_stock").click(function() {

        if ($("#stock_issue").valid()) {
            clearInterval(interval);
        }

        /*$("input[id$='-quantity']").each(function () {
         var str = $(this).attr("id");
         var row = str.replace("-quantity", "");
         
         var aval_qty = $("#" + row + "-ava_qty").val();
         aval_qty = aval_qty.replace(/,/g, "");
         
         var quantity = $(this).val();
         quantity = quantity.replace(/,/g, "");
         
         var qty123 = new String($(this).attr("name"));
         
         if (parseInt(quantity) <= 0) {
         alert("Quantity should greater then 0");
         $(this).focus();
         return false;
         } else if (parseInt(aval_qty) < parseInt(quantity)) {
         alert("Quantity not available");
         $(this).focus();
         return false;
         }
         return true;
         });*/
    });
}

function autoSave() {
    var form_dirty = $("#future_arrival").serialize();
    if (form_clean != form_dirty)
    {
        $('#add_stock').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-pipeline-consignments-draft",
            data: $('#future_arrival').serialize(),
            cache: false,
            success: function(data) {
                if (data == true) {
                    $('#notific8_show').trigger('click');
                }
                $('#add_stock').removeAttr('disabled');
            }
        });
        form_clean = form_dirty;
    }
}

function getProductsByStakeholder(activity_id) {
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-get-products-by-stakeholder-activity",
        data: {
            activity_id: activity_id, type: 2
        },
        dataType: 'html',
        success: function(data) {
            $('#item_id').html(data);
            $('#item_id').css('backgroundColor', 'Green');
            $.cookie('blink_div_background_color', "item_id");
            setTimeout(changeColor, 500);
        }
    });
}