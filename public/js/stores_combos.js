$(function () {
    $('#office_type').change(function () {


        $('#combo1').empty();
        $('#combo2').empty();
        $('#combo3').empty();
        $('#combo4').empty();

        $('#div_combo1').hide();
        $('#div_combo2').hide();
        $('#div_combo3').hide();
        $('#div_combo4').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function (data) {

                var val1 = $('#office_type').val();
                var warehouse_id = $('#hdn_warehouse_id').val();
                var role_id = $('#hdn_role_id').val();

                if (role_id == 38) {
                    switch (val1) {
                        case '2':

                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1').val(data);
                            break;
                        case '3':
                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
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
                }
                if (role_id == 39) {
                    switch (val1) {
                        case '2':

                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1').val(data);
                            break;
                        case '3':
                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
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
            }
        });
        var role_id = $('#hdn_role_id').val();
        if (role_id == 38) {

            $('#combo2').empty();

            $('#warehouse').empty();

            $('#div_combo2').hide();
            $('#wh_combo').hide();

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#province_id').val(), office: $('#office_type').val(), district_id: $('#district_id').val()},
                dataType: 'html',
                success: function (data) {


                    var val = $('#office_type').val();

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

        if (role_id == 39) {

            $('#combo2').empty();

            $('#warehouse').empty();

            $('#div_combo2').hide();
            $('#wh_combo').hide();

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#province_id').val(), office: $('#office_type').val(), district_id: $('#district_id').val()},
                dataType: 'html',
                success: function (data) {


                    var val = $('#office_type').val();

                    switch (val)
                    {
                        case '5':
                            //     $('#div_combo2').show();
                            $('#combo2').val(data);
                            break;
                        case '6':
                            //    $('#div_combo2').show();
                            $('#combo2').val(data);
                            break;
                    }
                }
            });
        }
    });

    $('#combo1').change(function () {

        $('#combo2').empty();

        $('#warehouse').empty();

        $('#div_combo2').hide();
        $('#wh_combo').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $(this).val(), office: $('#office_type').val()},
            dataType: 'html',
            success: function (data) {


                var val = $('#office_type').val();

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
            data: {combo2: $(this).val(), office: $('#office_type').val()},
            dataType: 'html',
            success: function (data) {

                var val = $('#office_type').val();
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
    $('#combo3').change(function () {

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-four",
            data: {combo3: $(this).val(), office: $('#office_type').val()},
            dataType: 'html',
            success: function (data) {

                var val = $('#office_type').val();
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



    if ($('#office_type').val() != "") {


        $('#combo1').empty();
        $('#combo2').empty();
        $('#combo3').empty();
        $('#combo4').empty();

        $('#div_combo1').hide();
        $('#div_combo2').hide();
        $('#div_combo3').hide();
        $('#div_combo4').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-one",
            data: {office: $('#office_type').val(), province_id: $('#province_id').val()},
            dataType: 'html',
            success: function (data) {

                var val1 = $('#office_type').val();
                var warehouse_id = $('#hdn_warehouse_id').val();
                var role_id = $('#hdn_role_id').val();
                if (role_id == 38) {
                    switch (val1) {
                        case '2':

                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1').val(data);
                            break;
                        case '3':
                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
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
                        case '2':

                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1').val(data);
                            break;
                        case '3':
                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
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
            }
        });

        var warehouse_id = $('#hdn_warehouse_id').val();
        var role_id = $('#hdn_role_id').val();
        if (role_id == 38) {

            $('#combo2').empty();

            $('#warehouse').empty();

            $('#div_combo2').hide();
            $('#wh_combo').hide();

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#province_id').val(), office: $('#office_type').val(), district_id: $('#district_id').val()},
                dataType: 'html',
                success: function (data) {


                    var val = $('#office_type').val();

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
        if (role_id == 39) {

            $('#combo2').empty();

            $('#warehouse').empty();

            $('#div_combo2').hide();
            $('#wh_combo').hide();

            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#province_id').val(), office: $('#office_type').val(), district_id: $('#district_id').val()},
                dataType: 'html',
                success: function (data) {


                    var val = $('#office_type').val();

                    switch (val)
                    {


                        case '5':
                            //    $('#div_combo2').show();
                            $('#combo2').val(data);
                            break;
                        case '6':
                            //    $('#div_combo2').show();
                            $('#combo2').val(data);
                            break;
                    }
                }
            });
        }
    }

    if ($('#combo1').val() != "") {

        $('#combo2').empty();

        $('#warehouse').empty();

        $('#div_combo2').hide();
        $('#wh_combo').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $('#province_id').val(), office: $('#office_type').val(), district_id: $('#district_id').val()},
            dataType: 'html',
            success: function (data) {


                var val = $('#office_type').val();

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


    if ($('#combo2').val() != "") {

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-three",
            data: {combo2: $('#district_id').val(), office: $('#office_type').val(), tehsil_id: $('#tehsil_id').val()},
            dataType: 'html',
            success: function (data) {

                var val = $('#office_type').val();
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
    if ($('#combo3').val() != "") {

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-four",
            data: {combo3: $('#tehsil_id').val(), office: $('#office_type').val(), uc_id: $('#parent_id').val()},
            dataType: 'html',
            success: function (data) {

                var val = $('#office_type').val();
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