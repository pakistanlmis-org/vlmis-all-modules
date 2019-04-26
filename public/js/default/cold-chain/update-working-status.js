$("#working_status_form").validate({
});

//Working Status Validation
$('select.working_status').each(function () {
    $(this).rules('add', {
        required: true
    });
});

// Reason Utilization Validation
$('select.reason_utilization').each(function () {
    $(this).rules('add', {
        required: true
    });
});


// working Quantity Validation
$('input.working_quantity').each(function () {
    $(this).rules('add', {
        required: true,
        number: true,
        min: 0
    });
});



// temperature_alarm  Validation
$('input.temperature_alarm').each(function () {
    $(this).rules('add', {

        number: true,
        min: 0,
        max: 1000
    });
});


$(".update-locations").click(function () {

    $.ajax({
        type: "POST",
        url: appName + "/cold-chain/ajax-get-working-status",
        data: {id: $(this).attr('id')},
        dataType: 'html',
        success: function (data) {
            $('#modal-body-contents').html(data);

            $('#update-button').show();
        }
    });
});


$("select[id$='-working_status']").change(function () {

    var value = $(this).attr("id");

    var id = value.split('-');
    var working_status_id = $(this).val();
    if (working_status_id === '3') {

        if ($('#' + id[0] + '-placed_quantity').val() > 0) {
            alert("Please remove the stock from the cold chain asset");
            $(this).val(' ');
        }
        //  $("#" + id[0] + "-utilization").attr("disabled", "disabled");
        $("#" + id[0] + "-reason").removeAttr("disabled");
    } else {
        $("#" + id[0] + "-reason").attr("disabled", "disabled");
        //   $("#" + id[0] + "-utilization").removeAttr("disabled");

    }
});

$("input[id$='-work_quantity']").keyup(function (e) {
    var value = $(this).attr("id");
    var id = value.replace("-work_quantity", "");
    var qty = $(this).val();
    var ava_qty = $('#' + id + '-total_quantity').val();
    if (parseInt(qty) > parseInt(ava_qty)) {
        alert("Quantity should not be greater than " + ava_qty + ".");
        $(this).focus();

    }
});