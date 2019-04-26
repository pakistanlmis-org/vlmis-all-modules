$(function() {
   if($('#ccm_make_id').val()!="") {
        $('#ccm_model_id').html('');
        if ($('#ccm_make_id').val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/cold-chain/ajax-get-models",
                data: {make: $('#ccm_make_id').val()},
                dataType: 'html',
                success: function(data) {
                    $('#ccm_model_id').html(data);
                    $('#ccm_model_id').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "ccm_model_id");
                    setTimeout(changeColor, 1000);
                    $('#ccm_model_id').val($('#model_hidden').val());
                }
            });
        }
    }

});

