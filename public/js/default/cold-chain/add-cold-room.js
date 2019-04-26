$(function() {
    $("#working_since").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $('#make').change(function() {
        $('#model').html('');
        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/cold-chain/ajax-get-models",
                data: {make: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $('#ccm_model_id').html(data);
                    $('#ccm_model_id').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "ccm_model_id");
                    setTimeout(changeColor, 500);
                }
            });
        }
    });

    if ($('#make').val() != "") {
        $('#model').html('');
        if ($('#make').val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/cold-chain/ajax-get-models",
                data: {make: $('#make').val()},
                dataType: 'html',
                success: function(data) {
                    $('#ccm_model_id').html(data);
                    $('#ccm_model_id').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "ccm_model_id");
                    setTimeout(changeColor, 500);
                }
            });
        }
    }


    $('#ccm_status_list_id').change(function() {
        $('#reason').html("");
      //  $('#loader').show();
        if ($(this).val() == "3") {
            $.ajax({
                type: "POST",
                url: appName + "/cold-chain/ajax-get-reasons",
                data: {working_status: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $('#loader').hide();
                    $('#reason').html(data);
                }
            });
        }
    });

    /*$('#ccm_status_list_id').change(function() {
     $('#utilization').html("");
     if ($(this).val() == "3") {
     $.ajax({
     type: "POST",
     url: appName + "/cold-chain/ajax-get-utilizations",
     data: {working_status: $(this).val()},
     dataType: 'html',
     success: function(data) {
     $('#utilization').html(data);
     }
     });
     }
     });*/


});
$.validator.addMethod("custom_alphanumeric", function (value, element) {
    return this.optional(element) || value === "NA" || value.match(/^[a-zA-Z0-9-_]+$/);
}, "Letters, numbers, hyphen and underscores only please");

// validate signup form on keyup and submit
$("#add_cold_room").validate({
    rules: {
        source_id:{
          required: true  }
        ,
         asset_id:{
         custom_alphanumeric: true   
        },
        ccm_status_list_id: {
            required: true
        },
        ccm_asset_sub_type_id: {
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
        gross_capacity: {
            required: true,
            number: true,
            min: 0
        },
        net_capacity: {
            required: true,
            number: true,
            min: 0
        },
        backup_generator: {
            required: true
        },
        cooling_system: {
           number: true,
            min: 0  
        },
        asset_dimension_length:{
           number: true,
           min: 0
        },
        asset_dimension_width:{
           number: true,
           min: 0     
        },
        asset_dimension_height:{
            number: true,
           min: 0    
        }
        
    },
    messages: {
        'asset_id':{
            required: "Please enter  Asset Id / Equipment Code"
        },
        'ccm_status_list_id': {
            required: "Please select working status"
        },
        'ccm_asset_sub_type_id': {
            required: "Cold chain type is required"
        },
        'make': {
            required: "Please select make"
        },
        'ccm_model_id': {
            required: "Please select model"
        },
        'gross_capacity': {
            required: "Please enter gross capacity"
        },
        'net_capacity': {
            required: "Please enter net capacity"
        }
    }
});

