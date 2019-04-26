$(function() {
    $('#office').change(function() {
        $('#combo1').empty();
        $('#combo2').empty();
        $('#div_combo1').hide();
        $('#div_combo2').hide();
        if ($('#office').val() > 1) {
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

    $('#combo1').change(function() {
        $('#combo2').empty();
        $('#warehouse').empty();
        $('#div_combo2').hide();
        $('#wh_combo').hide();

        if ($('#office').val() > 2) {
            $('#loader').show();
            $.ajax({
                type: "POST",
                url: appName + "/index/all-level-combos-two",
                data: {combo1: $(this).val(), office: $('#office').val()},
                dataType: 'html',
                success: function(data) {
                    $('#loader').hide();

                    var val = $('#office').val();
                    switch (val)
                    {
                        case '6':
                            $('#lblcombo2').text('Districts');
                            $('#div_combo2').show();
                            $('#combo2').show();
                            $('#combo2').html(data);
                            break;
                        case '7':
                            $('#lblcombo2').text('Districts');
                            $('#div_combo2').show();
                            $('#combo2').show();
                            $('#combo2').html(data);
                            break;
                    }
                }
            });
        }
    });

});