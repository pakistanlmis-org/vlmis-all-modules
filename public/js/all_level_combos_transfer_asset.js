$(function() {

    var level = $("#clevel").html();
    //select user province
    var prov = $('#user_province_id').val();
    var dist = $("#cdist").html();
    var wh_id = $("#cwh").html();

    $('#office').change(function() {

        $('#loader').show();
        $('#combo1').empty();
        $('#combo2').empty();
        $('#combo3').empty();
        $('#warehouse').empty();
        $('#div_combo1').hide();
        $('#div_combo2').hide();
        $('#div_combo3').hide();
        $('#wh_combo').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();
                var val1 = $('#office').val();
                $('#wh_l').html('Store');
                switch (val1) {
                    case '1':
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
                        $('#wh_l').html('Health Facility');
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        break;
                    case '60':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
                }

                if (val1 == 1 || val1 == 60) {
                    $("#warehouse").val(wh_id);
                } else {
                    $("#combo1").val(prov);
                    $('#combo1').prop("disabled", true);
                    $("#combo1").trigger("change");
                }
            }
        });
    });

    $('#combo1').change(function() {
        $('#loader').show();
        $('#combo2').empty();

        $('#warehouse').empty();

        $('#div_combo2').hide();
        $('#wh_combo').hide();

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


                }

                if (val == 5 || val == 6) {
                    $("#combo2").val(dist);
                    $("#combo2").trigger("change");
                } else {
                    $("#warehouse").val(wh_id);
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


                var val = $('#office').val();
                switch (val)
                {
                    case '1':
                    case '2':
                    case '3':
                    case '3':
                    case '5':
                    case '5':
                        $('#wh_combo').show();
                        $('#wh_1').html('Store');
                        $('#warehouse').html(data);
                        $("#warehouse").val(wh_id);
                        break;
                    case '6':
                        $('#lblcombo3').text('UCs');
                        $('#div_combo3').show();
                        $('#combo3').show();
                        $('#combo3').html(data);
                        break;
                }


            }
        });
    });

    $('#combo3').change(function() {
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-four",
            data: {combo3: $(this).val(), office: $('#office').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();
                $('#wh_combo').show();
                $('#wh_1').html('Store');
                $('#warehouse').html(data);
                $("#warehouse").val(wh_id);
            }
        });
    });

    populateAllLevelCombo();

});

function populateAllLevelCombo() {
    var level = $("#clevel").html();

    if (typeof (level) != "undefined" && level !== null && level != 0) {
        $("#office").val(level);
        $("#office").trigger("change");
    }
}