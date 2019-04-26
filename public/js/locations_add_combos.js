$(function () {
    $('#location_level_add').change(function () {

        $('#loader_add').show();
        $('#combo1_add').empty();
        $('#combo2_add').empty();

        $('#div_combo1_add').hide();
        $('#div_combo2_add').hide();
        $('#div_combo3_add').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#loader_add').hide();
                var val1 = $('#location_level_add').val();
                var role_id = $('#hdn_role_id').val();

                if (role_id == 38) {
                    switch (val1) {

                        case '3':
                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1_add').val($.trim(data));
                            break;
                        case '4':
                            $('#combo1_add').val($.trim(data));
                            break;
                        case '5':
                            $('#combo1_add').val($.trim(data));
                            break;
                        case '6':
                            $('#combo1_add').val($.trim(data));
                            break;
                    }
                } else if (role_id == 39) {
                    switch (val1) {

                        case '3':
                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1_add').val($.trim(data));
                            break;
                        case '4':
                            $('#combo1_add').val($.trim(data));
                            break;
                        case '5':
                            $('#combo1_add').val($.trim(data));
                            break;
                        case '6':
                            $('#combo1_add').val($.trim(data));
                            break;
                    }
                } else {
                    switch (val1) {

                        case '3':
                            $('#lblcombo1_add').text('Province');
                            $('#div_combo1_add').show();
                            $('#combo1_add').html(data);
                            break;
                        case '4':
                            $('#lblcombo1_add').text('Province');
                            $('#div_combo1_add').show();
                            $('#combo1_add').html(data);
                            break;
                        case '5':
                            $('#lblcombo1_add').text('Province');
                            $('#div_combo1_add').show();
                            $('#combo1_add').html(data);
                            break;
                        case '6':
                            $('#lblcombo1_add').text('Province');
                            $('#div_combo1_add').show();
                            $('#combo1_add').html(data);
                            break;
                    }
                }
            }
        });

        var role_id = $('#hdn_role_id').val();
        if (role_id == 38) {
            $('#loader_add').show();
            $('#combo2_add').empty();

            $('#warehouse_add').empty();

            $('#div_combo2_add').hide();
            $('#wh_combo_add').hide();

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#combo1').val(), office: $('#location_level_add').val()},
                dataType: 'html',
                success: function (data) {
                    $('#loader_add').hide();

                    var val = $('#location_level_add').val();

                    switch (val)
                    {
                        case '5':
                            $('#div_combo2_add').show();
                            $('#combo2_add').html(data);
                            $('#combo1_add').val($('#combo1_add').val());
                            break;
                        case '6':
                            $('#div_combo2_add').show();
                            $('#combo2_add').html(data);
                            $('#combo1_add').val($('#combo1_add').val());
                            break;
                    }
                }
            });
        } else if (role_id == 39) {
            $('#loader_add').show();
            $('#combo2_add').empty();

            $('#warehouse_add').empty();

            $('#div_combo2_add').hide();
            $('#wh_combo_add').hide();

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#combo1').val(), office: $('#location_level_add').val()},
                dataType: 'html',
                success: function (data) {
                    $('#loader_add').hide();

                    var val = $('#location_level_add').val();

                    switch (val)
                    {
                        case '5':
                            $('#div_combo2_add').show();
                            $('#combo2_add').val(data);
                            $('#combo1_add').val($('#combo1_add').val());
                            break;
                        case '6':
                            $('#div_combo2_add').show();
                            $('#combo2_add').val(data);
                            $('#combo1_add').val($('#combo1_add').val());
                            break;
                    }
                }
            });
            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-three",
                data: {combo2: $('#combo2').val(), office: $('#location_level_add').val()},
                dataType: 'html',
                success: function (data) {
                    $('#loader_add').hide();
                    var val = $('#location_level_add').val();
                    switch (val)
                    {
                        case '6':
                            $('#div_combo3_add').show();
                            $('#combo3_add').html(data);
                            break;

                    }
                }
            });
        }
    });

    $('#combo1_add').change(function () {
        $('#loader_add').show();
        $('#combo2_add').empty();


        $('#div_combo2_add').hide();


        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $(this).val(), office: $('#location_level_add').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader_add').hide();

                var val = $('#location_level_add').val();
                switch (val)
                {
                    case '5':
                        $('#div_combo2_add').show();
                        $('#combo2_add').html(data);
                        break;
                    case '6':
                        $('#div_combo2_add').show();
                        $('#combo2_add').html(data);
                        break;
                }
            }
        });
    });

    $('#combo2_add').change(function () {
        $('#loader_add').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-three",
            data: {combo2: $(this).val(), office: $('#location_level_add').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader_add').hide();
                var val = $('#location_level_add').val();
                switch (val)
                {
                    case '6':
                        $('#div_combo3_add').show();
                        $('#combo3_add').html(data);
                        break;

                }
            }
        });
    });
});