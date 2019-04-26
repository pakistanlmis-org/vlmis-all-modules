$('#ccm_asset_type_id_update').change(function() {
    $('#ccm_asset_sub_type_update').html('');
    if ($(this).val() != "") {
        $('#ccm_asset_type_id_update').removeClass("span3");
        $('#ccm_asset_type_id_update').addClass("span2");
        $('#loader_asset_type_update').show();
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-models/ajax-get-asset-subtypes-by-asset-type",
            data: {type_id: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#loader_asset_type_update').hide();
                $('#ccm_asset_type_id_update').removeClass("span2");
                $('#ccm_asset_type_id_update').addClass("span3");
                $('#ccm_asset_sub_type_update').html(data);
                $('#ccm_asset_sub_type_update').css('backgroundColor', 'Green');
                $.cookie('blink_div_background_color', "ccm_asset_sub_type_update");
                setTimeout(changeColor, 1000);
            }
        });
    }
});

// validate form on keyup and submit
$("#update-model").validate({
    rules: {
        'ccm_asset_type_update': {
            required: true
        },
        'ccm_asset_sub_type_update': {
            required: true
        },
        'ccm_make_id': {
            required: true
        },
        'ccm_model_name': {
            required: true
        }
    },
    messages: {
        ccm_asset_type_update: "Please select asset type",
        ccm_asset_sub_type_update: "Please select asset sub type",
        ccm_make_id: "Please select make",
        ccm_model_name: "Please enter model name"
    }
});

