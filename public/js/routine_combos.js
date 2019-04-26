$(function () {


    $('#combo1').change(function () {

        $('#combo2').empty();
        $('#div_combo2').hide();
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
                        $('#div_combo2').show();
                        $('#combo2').html(data);
                        break;
                    case '5':
                        $('#div_combo2').show();
                        $('#combo2').html(data);
                        break;
                    case '6':
                        $('#div_combo2').show();
                        $('#combo2').html(data);
                        break;
                }
            }
        });
    });

    $('#combo2').change(function () {

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
                        $('#div_combo3').show();
                        $('#combo3').html(data);
                        break;

                    case '6':
                        $('#div_combo3').show();
                        $('#combo3').html(data);
                        break;

                }
            }
        });
    });
    $('#combo3').change(function () {

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
                        $('#div_combo4').show();
                        $('#combo4').html(data);
                        break;

                }
            }
        });
    });




    if ($('#combo1').val() != "") {

        $('#combo2').empty();

        $('#div_combo2').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $('#province_id').val(), office: 6, district_id: $('#district_id').val()},
            dataType: 'html',
            success: function (data) {


                var val = '6';

                switch (val)
                {
                    case '4':
                        $('#div_combo2').show();
                        $('#combo2').html(data);
                        break;

                    case '5':
                        $('#div_combo2').show();
                        $('#combo2').html(data);
                        break;
                    case '6':
                        $('#div_combo2').show();
                        $('#combo2').html(data);
                        break;
                }
            }
        });
    }
    ;

    if ($('#combo1').val() != "") {

        $('#div_combo3').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-three",
            data: {combo2: $('#district_id').val(), office: 6, tehsil_id: $('#tehsil_id').val()},
            dataType: 'html',
            success: function (data) {

                var val = '6';
                switch (val)
                {

                    case '6':
                        $('#div_combo3').show();
                        $('#combo3').html(data);
                        break;

                }
            }
        });
    }
    if ($('#combo1').val() != "") {

        $('#div_combo4').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-four",
            data: {combo3: $('#tehsil_id').val(), office: 6, uc_id: $('#parent_id').val()},
            dataType: 'html',
            success: function (data) {

                var val = '6';
                switch (val)
                {
                    case '6':
                        $('#div_combo4').show();
                        $('#combo4').html(data);
                        break;

                }
            }
        });
    }








});