$(function() {
    var val = $('#office').val();
    if (val == 4 || val == 8 || val == 9)
    {
        $('#warehouse option[value=""]').remove();
        $('#warehouse').prepend('<option value="">All</option>');
        $('#warehouse option[value=""]').attr('selected', 'selected');
        $('#compulsory').removeClass("red");
        $('#compulsory').empty();
    }
    $('#office').change(function() {
        $('#loader').show();
        $('#combo1').empty();
        $('#combo2').empty();
        $('#warehouse').empty();
        $('#div_combo1').hide();
        $('#div_combo2').hide();


        //$('#wh_combo').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();
                var val1 = $('#office').val();
                switch (val1) {
                    case '1':
                        $('#wh_l').html('Warehouse');
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
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
                    case '8':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        break;
                    case '9':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        break;
                }
            }
        });
    });

    $('#combo1').change(function() {
        $('#loader').show();
        $('#combo2').empty();
        $('#warehouse').empty();
        $('#div_combo2').hide();
        //$('#wh_combo').hide();

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
                    case '2':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
                    case '3':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
                    case '4':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        $('#warehouse option[value=""]').remove();
                        $('#warehouse').prepend('<option value="">All</option>');
                        $('#warehouse option[value=""]').attr('selected', 'selected');
                        break;
                    case '5':
                        $('#lblcombo2').text('Districts');
                        $('#div_combo2').show();
                        $('#combo2').show();
                        $('#combo2').html(data);
                        break;
                    case '6':
                        $('#lblcombo2').text('Districts');
                        $('#div_combo2').show();
                        $('#combo2').show();
                        $('#combo2').html(data);
                        break;
                    case '8':
                        $('#lblcombo2').text('Districts');
                        $('#div_combo2').show();
                        $('#combo2').show();
                        $('#combo2').html(data);
                        $('#warehouse option[value=""]').remove();
                        $('#warehouse').prepend('<option value="">All</option>');
                        $('#warehouse option[value=""]').attr('selected', 'selected');
                        break;
                    case '9':
                        $('#lblcombo2').text('Districts');
                        $('#div_combo2').show();
                        $('#combo2').show();
                        $('#combo2').html(data);
                        $('#warehouse option[value=""]').remove();
                        $('#warehouse').prepend('<option value="">All</option>');
                        $('#warehouse option[value=""]').attr('selected', 'selected');
                        break;
                }
            }
        });
    });

    $('#combo2').change(function() {
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-three",
            data: {combo2: $(this).val(), office: $('#office').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();
                //$('#wh_combo').show();

                $('#wh_1').html('Store');
                $('#warehouse').html(data);
                var val = $('#office').val();
                if (val == 4 || val == 8 || val == 9)
                {
                    $('#warehouse option[value=""]').remove();
                    $('#warehouse').prepend('<option value="">All</option>');
                    $('#warehouse option[value=""]').attr('selected', 'selected');
                }
            }
        });
    });

    $("#go").click(function(e) {

        //e.preventDefault();
        var level = $("#office").val();
        var lvl = parseInt(level);
        if (lvl == 1 || lvl == 2 || lvl == '' || isNaN(lvl)) {
            $("#warehouse").rules("add", {
                required: true,
                messages: {
                    required: "Please select store"

                }
            });
        }
        else if (lvl == 4) {
            $("#combo1").rules("add", {
                required: true,
                messages: {
                    required: "Please select province"

                }
            });
            $("#warehouse").rules("remove");
        } else if (lvl == 8 || lvl == 9) {
            $("#combo1").rules("add", {
                required: true,
                messages: {
                    required: "Please select province"

                }
            });
            $("#warehouse").rules("remove");
            $("#combo2").rules("add", {
                required: true,
                messages: {
                    required: "Please select district"

                }
            });
            $("#warehouse").rules("remove");
        }
        else {
            $("#search").submit();
        }
    });

    $("#search").validate({
        rules: {
            warehouse: {
                //required: true
            }
        },
        messages: {
            'warehouse': {
                //required: "Please select store"
            }
        },
        submitHandler: function(form) {
            // $('#btn-loading')
            $('#go').attr('disabled', 'disabled');
            form.submit();
        }
    });

});



