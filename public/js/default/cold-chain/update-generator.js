$(function() {
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
                    $('#ccm_model_id').val($('#model_hidden').val());
                }
            });
        }
    }

});