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

    $("select[id$='-district']").change(function() {
        var str = $(this).attr("id");
        var row = str.replace("-district", "");

        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-get-ucs",
                data: {district_id: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $("#" + row + "-uc").html(data);
                }
            });
        }
    });

//    if ($("select[id$='-district']").val() != "") {
//
//        var str = $("select[id$='-district']").attr("id");
//        var row = str.replace("-district", "");
//
//        $.ajax({
//            type: "POST",
//            url: appName + "/stock/ajax-get-ucs",
//            data: {district_id: $("select[id$='-district']").val()},
//            dataType: 'html',
//            success: function(data) {
//                $("#" + row + "-uc").html(data);
//            }
//        });
//    }

    //form_clean = $("#stock_issue").serialize();

    // Auto Save function call
//    var month = $('#mm').val();
//    var year = $('#yy').val();
//
//    $("input[id$='vaccination']").datepicker({
//        dateFormat: 'dd/mm/yy',
//        minDate: minDate,
//        maxDate: maxDate,
//        changeMonth: false,
//    });
    // $("#vaccination_date").datepicker({dateFormat: 'dd/mm/yy'});

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
            'vaccination_date': {
                required: true
            },
            'name[]': {
                number: false
            },
            'father_name[]': {
                number: false
            },
            'age[]': {
                number: true
            },
            'contact[]': {
                number: true
            },
            'dose_no[]': {
                number: true
            }
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
            url: appName + "/stock/ajax-add-more-log-rows",
            data: {start: start, end: end},
            dataType: 'html',
            success: function(data) {

                $('tbody').append(data);
                $("#counter").val(end);
                $("select[id$='-district']").change(function() {
                    var str = $(this).attr("id");
                    var row = str.replace("-district", "");

                    if ($(this).val() != "") {
                        $.ajax({
                            type: "POST",
                            url: appName + "/stock/ajax-get-ucs",
                            data: {district_id: $(this).val()},
                            dataType: 'html',
                            success: function(data) {
                                $("#" + row + "-uc").html(data);
                            }
                        });
                    }
                });

                Metronic.stopPageLoading();
                //  refreshDateFields();
            }
        });
    });
$("#monthly_report").change(function() {

        var action = $(this).val();

        var url = appName + '/stock/log-book?do=' + action;
        window.location.href = url;
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