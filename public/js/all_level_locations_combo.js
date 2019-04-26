$(function() {
    $('#office').change(function() {
        $('#combo1').empty();
        $('#combo2').empty();
        $('#div_combo1').hide();
        $('#div_combo2').hide();
        if($('#office').val() > 1){
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();
                var val1 = $('#office').val();
                switch (val1) {
                    case '2':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        break;
                    case '3':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        break;
                    case '4':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        break;
                    case '5':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        break;
                    case '6':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        break;
                }
            }
        });
    }
    });

});