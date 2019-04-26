$('#make').change(function () {
    $('#model').html('');
    if ($(this).val() != "") {
        $.ajax({
            type: "POST",
            url: appName + "/cold-chain/ajax-get-models",
            data: {make: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#ccm_model_id').html(data);
                $('#ccm_model_id').css('backgroundColor', 'Green');
                $.cookie('blink_div_background_color', "ccm_model_id");
                setTimeout(changeColor, 500);
            }
        });
    }
});

if ($('#use_for').val() == "90") {

    $('#coldroom_div').show();
    //  $('#coldroom_div').removeClass("hidden");
} else {
    $('#coldroom_div').hide();
    //  $('#coldroom_div').addClass("hidden");

}
$('#use_for').change(function () {
    $('#cold-room').html("");
    //$('#utilization').html("");

    if ($(this).val() == "90") {

        $('#coldroom_div').show();
        //  $('#coldroom_div').removeClass("hidden");
    } else {
        $('#coldroom_div').hide();
        //  $('#coldroom_div').addClass("hidden");

    }
});

$(function () {
    $("#working_since").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
});

$.validator.addMethod("custom_alphanumeric", function (value, element) {
    return this.optional(element) || value === "NA" || value.match(/^[a-zA-Z0-9-_]+$/);
}, "Letters, numbers, hyphen and underscores only please");

// validate signup form on keyup and submit
$("#add_generator").validate({
    rules: {
        asset_id: {
            required: true,
            custom_alphanumeric: true
        },
        source_id: {
            required: true
        },
        ccm_status_list_id: {
            required: true
        },
        make: {
            required: true
        },
        ccm_model_id: {
            required: true
        },
        office: {
            required: true
        },
        combo1: {
            required: true
        },
        warehouse: {
            required: true
        },
        reason: {
            required: true
        },
        utilization: {
            required: true
        },
        serial_number: {
            required: true,
            alphanumeric: true
        },
        power_rating: {
            required: true,
            alphanumeric: true
        },
        power_source: {
            required: true
        },
        cold_room: {
            required: true
        }

    },
    messages: {
        asset_id: {
            required: "Please enter  Asset Id / Equipment Code"
        },
        'ccm_status_list_id': {
            required: "Please select working status"
        },
        'source_id': {
            required: "Please select working status"
        },
        'make': {
            required: "Please select make"
        },
        'ccm_model_id': {
            required: "Please select model"
        },
        'serial_number': {
            required: "Please enter serial number"
        },
        'power_source': {
            required: "Please select power source"
        },
        'power_rating': {
            required: "Please enter power rating"
        }
    }
});
