$(function() {
    //select user province
    var prov = $('#user_province_id').val();

    $('#office2').change(function() {
        $('#loader2').show();
        $('#combo12').empty();
        $('#combo22').empty();
        $('#warehouse2').empty();
        $('#div_combo12').hide();
        $('#div_combo22').hide();
        $('#wh_combo2').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#loader2').hide();
                var val1 = $('#office2').val();
                switch (val1) {
                    case '1':
                        $('#wh_l2').html('Warehouse');
                        $('#wh_combo2').show();
                        $('#warehouse2').html(data);
                        break;
                    case '2':
                        $('#lblcombo12').text('Province');
                        $('#div_combo12').show();
                        $('#combo12').html(data);
                        break;
                    case '3':
                        $('#lblcombo12').text('Province');
                        $('#div_combo12').show();
                        $('#combo12').html(data);
                        break;
                    case '4':
                        $('#lblcombo12').text('Province');
                        $('#div_combo12').show();
                        $('#combo12').html(data);
                        break;
                    case '5':
                        $('#lblcombo12').text('Province');
                        $('#div_combo12').show();
                        $('#combo12').html(data);
                        break;
                    case '6':
                        $('#lblcombo12').text('Province');
                        $('#div_combo12').show();
                        $('#combo12').html(data);
                        break;
                }
                
                if (val1 == 1 || val1 == 60) {
                    $("#warehouse2").val(wh_id);
                } else {                    
                    $("#combo12").val(prov);
                    $('#combo12').prop("disabled", true);
                    $("#combo12").trigger("change");
                }           
                
            }
        });
    });

    $('#combo12').change(function() {
        $('#loader2').show();
        $('#combo22').empty();

        $('#warehouse2').empty();

        $('#div_combo22').hide();
        $('#wh_combo2').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-two",
            data: {combo1: $(this).val(), office: $('#office2').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader2').hide();

                var val = $('#office2').val();
                switch (val)
                {
                    case '2':
                        $('#wh_combo2').show();
                        $('#warehouse2').html(data);
                        break;
                    case '3':
                        $('#wh_combo2').show();
                        $('#warehouse2').html(data);
                        break;
                    case '4':
                        $('#wh_combo2').show();
                        $('#warehouse2').html(data);
                        break;
                    case '5':
                        $('#lblcombo22').text('Districts');
                        $('#div_combo22').show();
                        $('#combo22').show();
                        $('#combo22').html(data);
                        break;
                    case '6':
                        $('#lblcombo22').text('Districts');
                        $('#div_combo22').show();
                        $('#combo22').show();
                        $('#combo22').html(data);
                        break;


                }
            }
        });
    });

    $('#combo22').change(function() {
        $('#loader2').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-three",
            data: {combo2: $(this).val(), office: $('#office2').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader2').hide();
                $('#wh_combo2').show();
                $('#wh_12').html('Store');
                $('#warehouse2').html(data);
            }
        });
    });
});