
$("#number").select2();
$('#add-rows').hide();
var form_clean;
var interval = null;

$("input[id$='-quantity']").each(function () {
    var str = $(this).attr("id");
    var row = str.replace("-quantity", "");
    if ($(this).val() > 0 && $(this).val().length != '') {
        //  alert($(this).val());
        // $("input[id$='-quantity']").attr('readonly', false);
    } else {
        $('#' + row + '-quantity').attr('readonly', true);
    }
});

$(function () {

    // Auto Save function call
    interval = setInterval(autoSave, 20000);
    // validate signup form on keyup and submit
    $("#add-stock-issue").validate({
        rules: {
            'transaction_date': {
                required: true
            }
        },
        messages: {
            'transaction_date': {
                required: "Please select Date"
            }
        }
    });



    $("select[id$='-number']").change(function () {


        var str = $(this).attr("id");
        var row = str.replace("-number", "");
        $('#' + row + '-quantity').val("");
        $('#' + row + '-expiry_date').val("");

        if ($(this).val() == "") {
            $('#' + row + '-quantity').val("");
            $('#' + row + '-expiry_date').val("");

            $('#' + row + '-quantity').attr('readonly', true);
        } else {
            $('#' + row + '-quantity').attr('readonly', false);
        }
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-available-batch-quantity-expiry",
            data: {batch: $(this).val(), tr_date: $("#transaction_date").val()},
            dataType: 'html',
            success: function (data) {

                $('#' + row + '-expiry_date').val(data);

            }
        });
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-issue-available-vvm-stages",
            data: {batch: $(this).val(), tr_date: $("#transaction_date").val()},
            dataType: 'html',
            success: function (data) {
                var result = data.split('|');
                $('#' + row + '-hdn_available_quantity').val(result[2]);
            }
        });
        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-issue-available-vvm-stages",
            data: {batch: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#' + row + '-hdn_vvm_stage').val(data);
            }
        });

    });



    $("#add_more").click(function () {
        Metronic.startPageLoading('Please wait...');

        var start = 0;
        var end = 0;
        var default_cntr = $("#default_counter").val();

        $("#add-rows").show();
        start = $('.table tr.dynamic-rows').length;
        end = parseInt(start) + parseInt(default_cntr);

        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-add-issue-more-rows",
            data: {start: start, end: end},
            dataType: 'html',
            success: function (data) {
                $('tbody').append(data);
                $("#counter").val(end);
                $("select[id$='-number']").change(function () {


                    var str = $(this).attr("id");
                    var row = str.replace("-number", "");
                    $('#' + row + '-quantity').val("");
                    $('#' + row + '-expiry_date').val("");

                    if ($(this).val() == "") {
                        $('#' + row + '-quantity').val("");
                        $('#' + row + '-expiry_date').val("");

                        $('#' + row + '-quantity').attr('readonly', true);
                    } else {
                        $('#' + row + '-quantity').attr('readonly', false);
                    }
                    $.ajax({
                        type: "POST",
                        url: appName + "/stock-batch/ajax-available-batch-quantity-expiry",
                        data: {batch: $(this).val(), tr_date: $("#transaction_date").val()},
                        dataType: 'html',
                        success: function (data) {

                            $('#' + row + '-expiry_date').val(data);

                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: appName + "/stock-batch/ajax-issue-available-vvm-stages",
                        data: {batch: $(this).val(), tr_date: $("#transaction_date").val()},
                        dataType: 'html',
                        success: function (data) {
                            var result = data.split('|');
                            $('#' + row + '-hdn_available_quantity').val(result[2]);
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: appName + "/stock-batch/ajax-issue-available-vvm-stages",
                        data: {batch: $(this).val()},
                        dataType: 'html',
                        success: function (data) {
                            $('#' + row + '-hdn_vvm_stage').val(data);
                        }
                    });

                });
                Metronic.stopPageLoading();
                refreshDateFields();
            }
        });
    });
});
//  }
$("input[id$='-quantity']").priceFormat({
    prefix: '',
    thousandsSeparator: ',',
    suffix: '',
    centsLimit: 0,
    limit: 10
});
$('#transaction_date').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    format: 'd/m/Y h:i A',
    changeMonth: false,
    changeYear: false
});
function refreshDateFields() {

    $("select[id$='-item_pack_size_id']").change(function () {
        var str = $(this).attr("id");
        var row = str.replace("-item_pack_size_id", "");

        var data = [];
        var res;
        $("select[id$='-number']").each(function () {
            if ($.trim($(this).val()) == "") {
                res = 0;
            } else {
                res = $(this).val();
            }

            data.push(res);


        });

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-issue-running-batches",
            data: {
                item_id: $(this).val(), transaction_date: $('#transaction_date').val(), batch_no: data
            },
            dataType: 'html',
            success: function (data) {
                $('#' + row + '-number').html(data);
            }
        });
    });

    $("input[id$='-quantity']").priceFormat({
        prefix: '',
        thousandsSeparator: ',',
        suffix: '',
        centsLimit: 0,
        limit: 10
    });

    $.validator.addMethod("custom_alphanumeric", function (value, element) {
        return this.optional(element) || value === "NA" || value.match(/^[a-zA-Z0-9-_]+$/);
    }, "Letters, numbers, hyphen and underscores only please");

    $('.number').keyup(function () {
        $(this).val($(this).val().toUpperCase());
    });

    $.validator.addClassRules("number", {
        nowhitespace: true,
        custom_alphanumeric: true
    });

    $("#add_stock").click(function () {
        if ($("#add_stock").valid()) {
            clearInterval(interval);
        }
    });
}
//if ($('#hdn_stock_master_id').val() == "") {
//    $("select[id$='-item_pack_size_id']").each(function() {
//
//        var str = $(this).attr("id");
//        var row = str.replace("-item_pack_size_id", "");
//        $.ajax({
//            type: "POST",
//            url: appName + "/stock-batch/ajax-running-batches",
//            data: {
//                item_id: $(this).val()
//            },
//            dataType: 'html',
//            success: function(data) {
//                $('#' + row + '-number').html(data);
//            }
//        });
//
//
//    });
//}

// still to work

function autoSave() {
    var form_dirty = $("#add-stock-issue").serialize();
    if (form_clean != form_dirty)
    {
        $('#add_stock').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-stock-issue-temp",
            data: $('#add-stock-issue').serialize(),
            cache: false,
            success: function (data) {
               
                if (data == 1) {
                    $('#notific8_heading').html('Your Data');
                    $('#notific8_text').html('has been saved as draft.');
                    $('#notific8_show').trigger('click');
                } else {
                    $('#notific8_heading').html('Your Data');
                    $('#notific8_text').html('has not saved as draft.Please Enter Correct Quantity');
                    $('#notific8_show').trigger('click');
                }
                $('#add_stock').removeAttr('disabled');
            }
        });
        form_clean = form_dirty;
    }
}

$("#add_stock_issue").click(function (e) {

    Metronic.startPageLoading('Please wait...');
    e.preventDefault();
    var flag = true;

    var q = 0;
    $("input[id$='-quantity']").each(function () {

        var str = $(this).attr("id");
        var row = str.replace("-quantity", "");


        var available_quantity = $('#' + row + '-hdn_available_quantity').val();
        var item_pack_size_id = $('#' + row + '-item_pack_size_id  option:selected').text();
        var quantity = $(this).val();
        quantity = quantity.replace(/,/g, "");

        if (quantity != '') {
            q++;
        }
        if (parseInt(available_quantity) < parseInt(quantity)) {
            Metronic.stopPageLoading();
            alert(item_pack_size_id + " Quantity (" + quantity + ") Should not be greater than Available Quantity (" + available_quantity + ")");

            $(this).focus();

            flag = false;
            return false;
        }
    });

    if (q == 0) {
        alert('Please enter at least one quantity to issue');
        Metronic.stopPageLoading();
        return false;
    }
    if (flag == true) {
        clearInterval(interval);
        $("#add-stock-issue").submit();
    } else {
        Metronic.stopPageLoading();
    }

});

$("select[id$='-item_pack_size_id']").change(function () {
    var str = $(this).attr("id");
    var row = str.replace("-item_pack_size_id", "");

    var data = [];
    var res;
    $("select[id$='-number']").each(function () {
        if ($.trim($(this).val()) == "") {
            res = 0;
        } else {
            res = $(this).val();
        }

        data.push(res);


    });

    $.ajax({
        type: "POST",
        url: appName + "/stock-batch/ajax-issue-running-batches",
        data: {
            item_id: $(this).val(), transaction_date: $('#transaction_date').val(), batch_no: data
        },
        dataType: 'html',
        success: function (data) {
            $('#' + row + '-number').html(data);
        }
    });
});