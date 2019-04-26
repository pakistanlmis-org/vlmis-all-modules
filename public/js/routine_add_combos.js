$(function () {
    var role_id = $('#hdn_role_id').val();
    if (role_id == 38 || role_id == 39) {
        if ($('#combo1_add').val() != "") {
            $('#combo2_add').empty();
            $('#div_combo2_add').hide();
            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#combo1_add').val(), office: 6},
                dataType: 'html',
                success: function (data) {


                    var val = '6';
                    switch (val)
                    {
                        case '4':
                            $('#div_combo2_add').show();
                            $('#combo2_add').html(data);
                            break;
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
        }
    } else {

        $('#combo1_add').change(function () {

            $('#combo2_add').empty();
            $('#div_combo2_add').hide();
            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $(this).val(), office: 6},
                dataType: 'html',
                success: function (data) {


                    var val = '6';
                    switch (val)
                    {
                        case '4':
                            $('#div_combo2_add').show();
                            $('#combo2_add').html(data);
                            break;
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
    }
    var role_id = $('#hdn_role_id').val();
    if (role_id == 39) {
        if ($('#combo2_add').val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-three",
                data: {combo2: $('#combo2_add').val(), office: 6},
                dataType: 'html',
                success: function (data) {

                    var val = '6';
                    switch (val)
                    {
                        case '5':
                            $('#div_combo3_add').show();
                            $('#combo3_add').html(data);
                            break;
                        case '6':
                            $('#div_combo3_add').show();
                            $('#combo3_add').html(data);
                            break;
                    }
                }
            });
        }
    } else {
        $('#combo2_add').change(function () {

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-three",
                data: {combo2: $(this).val(), office: 6},
                dataType: 'html',
                success: function (data) {

                    var val = '6';
                    switch (val)
                    {
                        case '5':
                            $('#div_combo3_add').show();
                            $('#combo3_add').html(data);
                            break;
                        case '6':
                            $('#div_combo3_add').show();
                            $('#combo3_add').html(data);
                            break;
                    }
                }
            });
        });
    }
    $('#combo3_add').change(function () {

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-four",
            data: {combo3: $(this).val(), office: 6},
            dataType: 'html',
            success: function (data) {

                var val = '6';
                switch (val)
                {


                    case '6':
                        $('#div_combo4_add').show();
                        $('#combo4_add').html(data);
                        break;
                }
            }
        });
    });
});