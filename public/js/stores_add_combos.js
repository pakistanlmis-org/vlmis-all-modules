$(function () {
    $('#office_type_add').change(function () {


        $('#combo1_add').empty();
        $('#combo2_add').empty();
        $('#combo3_add').empty();
        $('#combo4_add').empty();

        $('#div_combo1_add').hide();
        $('#div_combo2_add').hide();
        $('#div_combo3_add').hide();
        $('#div_combo4_add').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function (data) {

                var val1 = $('#office_type_add').val();
                var warehouse_id = $('#hdn_warehouse_id').val();
                var role_id = $('#hdn_role_id').val();
                if (role_id == 38) {
                    switch (val1) {
                        case '2':

                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1_add').val(data);
                            break;
                        case '3':
                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1_add').val(data);
                            break;
                        case '4':
                            $('#combo1_add').val(data);
                            break;
                        case '5':
                            $('#combo1_add').val(data);
                            break;
                        case '6':
                            $('#combo1_add').val(data);
                            break;
                    }
                } else if (role_id == 39) {
                    switch (val1) {
                        case '2':

                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1_add').val(data);
                            break;
                        case '3':
                            //  $('#lblcombo1').text('Province');
                            //  $('#div_combo1').show();
                            $('#combo1_add').val(data);
                            break;
                        case '4':
                            $('#combo1_add').val(data);
                            break;
                        case '5':
                            $('#combo1_add').val(data);
                            break;
                        case '6':
                            $('#combo1_add').val(data);
                            break;
                    }
                } else {
                    switch (val1) {
                        case '2':
                            $('#lblcombo1_add').text('Province');
                            $('#div_combo1_add').show();
                            $('#combo1_add').html(data);
                            break;
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

            $('#combo2_add').empty();

            $('#div_combo2_add').hide();


            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#combo1_add').val(), office: $('#office_type_add').val()},
                dataType: 'html',
                success: function (data) {


                    var val = $('#office_type_add').val();

                    switch (val)
                    {
                        case '3':
                            $('#div_combo2_add').show();
                            $('#combo2_add').html(data);
                            break;
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
        
        
          if (role_id == 39) {

            $('#combo2_add').empty();



            $('#div_combo2_add').hide();


            $.ajax({
                type: "POST",
                url: appName + "/index/locations-combos-two",
                data: {combo1: $('#province_id').val(), office: $('#office_type_add').val()},
                dataType: 'html',
                success: function (data) {


                    var val = $('#office_type_add').val();

                    switch (val)
                    {
                        case '4':
                       //     $('#div_combo2_add').show();
                            $('#combo2_add').val(data);
                            break;
                        case '5':
                          //  $('#div_combo2_add').show();
                            $('#combo2_add').val(data);
                            break;
                        case '6':
                         //   $('#div_combo2_add').show();
                            $('#combo2_add').val(data);
                            break;
                    }
                }
            });
            
              $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-three",
            data: {combo2: $('#district_id').val(), office: $('#office_type_add').val()},
            dataType: 'html',
            success: function (data) {

                var val = $('#office_type_add').val();
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
    });

    $('#combo1_add').change(function () {

        $('#combo2_add').empty();



        $('#div_combo2_add').hide();


        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $(this).val(), office: $('#office_type_add').val()},
            dataType: 'html',
            success: function (data) {


                var val = $('#office_type_add').val();

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

    $('#combo2_add').change(function () {

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-three",
            data: {combo2: $(this).val(), office: $('#office_type_add').val()},
            dataType: 'html',
            success: function (data) {

                var val = $('#office_type_add').val();
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
    $('#combo3_add').change(function () {

        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-four",
            data: {combo3: $(this).val(), office: $('#office_type_add').val()},
            dataType: 'html',
            success: function (data) {

                var val = $('#office_type_add').val();
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