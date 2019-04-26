$(function () {
    $('#location_level').change(function () {

        //  $('#loader').show();
        $('#combo1').empty();
        $('#combo2').empty();
        $('#warehouse').empty();
        $('#div_combo1').hide();
        $('#div_combo2').hide();
        $('#div_combo3').hide();
        $('#wh_combo').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $('#loader').hide();
                var val1 = $('#location_level').val();
                var warehouse_id = $('#hdn_warehouse_id').val();
                var role_id = $('#hdn_role_id').val();

                if (role_id == 38) {
                    switch (val1) {

                        case '3':
                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1').val($.trim(data));
                            break;
                        case '4':
                            $('#combo1').val($.trim(data));
                            break;
                        case '5':
                            $('#combo1').val($.trim(data));
                            break;
                        case '6':
                            $('#combo1').val($.trim(data));
                            break;
                    }
                } else if (role_id == 39) {
                    switch (val1) {

                        case '3':
                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1').val($.trim(data));
                            break;
                        case '4':
                            $('#combo1').val($.trim(data));
                            break;
                        case '5':
                            $('#combo1').val($.trim(data));
                            break;
                        case '6':
                            $('#combo1').val($.trim(data));
                            break;
                    }
                } else {
                    switch (val1) {

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

            }
        });
        var role_id = $('#hdn_role_id').val();
        if (role_id == 38) {
            $('#loader').show();
            $('#combo2').empty();

            $('#warehouse').empty();

            $('#div_combo2').hide();
            $('#wh_combo').hide();

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#combo1').val(), office: $('#location_level').val()},
                dataType: 'html',
                success: function (data) {
                    $('#loader').hide();

                    var val = $('#location_level').val();

                    switch (val)
                    {
                        case '5':
                            $('#div_combo2').show();
                            $('#combo2').html(data);
                            $('#combo1').val($('#combo1').val());
                            break;
                        case '6':
                            $('#div_combo2').show();
                            $('#combo2').html(data);
                            $('#combo1').val($('#combo1').val());
                            break;
                    }
                }
            });
        } else if (role_id == 39) {
            $('#loader').show();
            $('#combo2').empty();

            $('#warehouse').empty();

            $('#div_combo2').hide();
            $('#wh_combo').hide();

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#combo1').val(), office: $('#location_level').val()},
                dataType: 'html',
                success: function (data) {
                    $('#loader').hide();

                    var val = $('#location_level').val();

                    switch (val)
                    {
                        case '5':
                            $('#div_combo2').show();
                            $('#combo2').val(data);
                            $('#combo1').val($('#combo1').val());
                            break;
                        case '6':
                            $('#div_combo2').show();
                            $('#combo2').val(data);
                            $('#combo1').val($('#combo1').val());
                            break;
                    }
                }
            });

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-three",
                data: {combo2: $('#combo2').val(), office: $('#location_level').val()},
                dataType: 'html',
                success: function (data) {
                    $('#loader').hide();
                    var val = $('#location_level').val();
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
    });

    $('#combo1').change(function () {
        $('#loader').show();
        $('#combo2').empty();

        $('#warehouse').empty();

        $('#div_combo2').hide();
        $('#wh_combo').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $(this).val(), office: $('#location_level').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader').hide();

                var val = $('#location_level').val();

                switch (val)
                {
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
        //  $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-three",
            data: {combo2: $(this).val(), office: $('#location_level').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader').hide();
                var val = $('#location_level').val();
                switch (val)
                {
                    case '6':
                        $('#div_combo3').show();
                        $('#combo3').html(data);
                        break;

                }
            }
        });
    });

    if ($('#location_level').val() != "") {

        //  $('#loader').show();
        $('#combo1').empty();
        $('#combo2').empty();
        $('#warehouse').empty();
        $('#div_combo1').hide();
        $('#div_combo2').hide();
        $('#div_combo3').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-one",
            data: {office: $('#location_level').val(), province_id: $('#province_id').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader').hide();
                var warehouse_id = $('#hdn_warehouse_id').val();
                var role_id = $('#hdn_role_id').val();
                var val1 = $('#location_level').val();
                if (role_id == 38) {
                    switch (val1) {

                        case '3':
                            $('#combo1').val(data);
                            break;
                        case '4':
                            $('#combo1').val(data);
                            break;
                        case '5':
                            $('#combo1').val(data);
                            break;
                        case '6':
                            $('#combo1').val(data);
                            break;
                    }
                } else if (role_id == 39) {
                    switch (val1) {

                        case '3':
                            $('#combo1').val(data);
                            break;
                        case '4':
                            $('#combo1').val(data);
                            break;
                        case '5':
                            $('#combo1').val(data);
                            break;
                        case '6':
                            $('#combo1').val(data);
                            break;
                    }
                } else {
                    switch (val1) {

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
            }
        });
        var role_id = $('#hdn_role_id').val();
        if (role_id == 38) {

            $('#loader').show();
            $('#combo2').empty();

            $('#warehouse').empty();

            $('#div_combo2').hide();
            $('#wh_combo').hide();

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#province_id').val(), office: $('#location_level').val(), district_id: $('#district_id').val()},
                dataType: 'html',
                success: function (data) {
                    $('#loader').hide();

                    var val = $('#location_level').val();

                    switch (val)
                    {
                        case '5':
                            $('#div_combo2').show();
                            $('#combo2').html(data);
                            $('#combo1').val($('#combo1').val());
                            break;
                        case '6':
                            $('#div_combo2').show();
                            $('#combo2').html(data);
                            $('#combo1').val($('#combo1').val());
                            break;
                    }
                }
            });
        } else if (role_id == 39) {
            $('#loader').show();
            $('#combo2').empty();

            $('#warehouse').empty();

            $('#div_combo2').hide();
            $('#wh_combo').hide();

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#province_id').val(), office: $('#location_level').val(), district_id: $('#district_id').val()},
                dataType: 'html',
                success: function (data) {
                    $('#loader').hide();

                    var val = $('#location_level').val();

                    switch (val)
                    {
                        case '5':
                            $('#div_combo2').show();
                            $('#combo2').val(data);
                            $('#combo1').val($('#combo1').val());
                            break;
                        case '6':
                            $('#div_combo2').show();
                            $('#combo2').val(data);
                            $('#combo1').val($('#combo1').val());
                            break;
                    }
                }
            });

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-three",
                data: {combo2: $('#district_id').val(), office: $('#location_level').val(), tehsil_id: $('#parent_id').val()},
                dataType: 'html',
                success: function (data) {
                    $('#loader').hide();
                    var val = $('#location_level').val();
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

    }

    if ($('#combo1').val() != "") {

        //  $('#loader').show();
        $('#combo2').empty();

        $('#warehouse').empty();

        $('#div_combo2').hide();
        $('#wh_combo').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $('#province_id').val(), office: $('#location_level').val(), district_id: $('#district_id').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader').hide();

                var val = $('#location_level').val();

                switch (val)
                {
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

    if ($('#combo2').val() != "") {
        //  $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-three",
            data: {combo2: $('#district_id').val(), office: $('#location_level').val(), tehsil_id: $('#parent_id').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader').hide();
                var val = $('#location_level').val();
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









});