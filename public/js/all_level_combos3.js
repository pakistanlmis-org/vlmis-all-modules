$(function() {
    $('#office3').change(function() {
        $('#loader3').show();
        $('#combo13').empty();
        $('#combo23').empty();
        $('#warehouse3').empty();
        $('#div_combo13').hide();
        $('#div_combo23').hide();
        $('#wh_combo3').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#loader3').hide();
                var val1 = $('#office3').val();
                switch (val1) {
                    case '1':
                        $('#wh_l3').html('Warehouse');
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        break;
                    case '2':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        break;
                    case '3':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        break;
                    case '4':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        break;
                    case '5':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        break;
                    case '6':
                        $('#lblcombo13').text('Province');
                        $('#div_combo13').show();
                        $('#combo13').html(data);
                        break;
                }
            }
        });
    });

    $('#combo13').change(function() {
        $('#loader3').show();
        $('#combo23').empty();

        $('#warehouse3').empty();

        $('#div_combo23').hide();
        $('#wh_combo3').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-two",
            data: {combo1: $(this).val(), office: $('#office3').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader3').hide();

                var val = $('#office3').val();
                switch (val)
                {
                    case '2':
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        break;
                    case '3':
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        break;
                    case '4':
                        $('#wh_combo3').show();
                        $('#warehouse3').html(data);
                        break;
                    case '5':
                        $('#lblcombo23').text('Districts');
                        $('#div_combo23').show();
                        $('#combo23').show();
                        $('#combo23').html(data);
                        break;
                    case '6':
                        $('#lblcombo23').text('Districts');
                        $('#div_combo23').show();
                        $('#combo23').show();
                        $('#combo23').html(data);
                        break;


                }
            }
        });
    });

    $('#combo23').change(function() {
        $('#loader3').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-three",
            data: {combo2: $(this).val(), office: $('#office3').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader3').hide();
                $('#wh_combo3').show();
                $('#wh_13').html('Store');
                $('#warehouse3').html(data);
            }
        });
    });
});