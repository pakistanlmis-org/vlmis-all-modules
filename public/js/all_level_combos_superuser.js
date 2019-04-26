$(function () {

    var level = $("#clevel").html();
    var prov = $("#cprov").html();
    var dist = $("#cdist").html();
    var wh_id = $("#cwh").html();

    $('#office').change(function () {

        $.ajax({
            type: "POST",
            url: appName + "/ajax/ajax-save-session-values",
            data: {office: $(this).val()},
            dataType: 'html',
            success: ''
        });

        $('#loader').show();
        $('#combo1').empty();
        $('#combo2').empty();
        $('#warehouse').empty();
        $('#div_combo1').hide();
        $('#div_combo2').hide();
        $('#wh_combo').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function (data) {
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
                    if (typeof (wh_id) != "undefined") {
                        $("#warehouse").val(wh_id);
                    }
                } else {
                    if (typeof (prov) != "undefined") {
                        $("#combo1").val(prov);
                    }
                    $("#combo1").trigger("change");
                }

            }
        });
    });

    $('#combo1').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/ajax/ajax-save-session-values",
            data: {combo1: $(this).val()},
            dataType: 'html',
            success: ''
        });

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
            success: function (data) {
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
                    if (typeof (dist) != "undefined") {
                        $("#combo2").val(dist);
                    }
                    $("#combo2").trigger("change");
                } else {
                    if (typeof (wh_id) != "undefined") {
                        $("#warehouse").val(wh_id);
                    }
                }

            }
        });
    });

    $('#combo2').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/ajax/ajax-save-session-values",
            data: {combo2: $(this).val()},
            dataType: 'html',
            success: ''
        });

        $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-three",
            data: {combo2: $(this).val(), office: $('#office').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader').hide();
                $('#wh_combo').show();
                $('#wh_1').html('Store');
                $('#warehouse').html(data);
                
                if (typeof (wh_id) != "undefined") {
                    $("#warehouse").val(wh_id);
                }
            }
        });
    });

    populateAllLevelCombo();

    $('#warehouse').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/ajax/ajax-save-session-values",
            data: {warehouse: $(this).val()},
            dataType: 'html',
            success: ''
        });
    });

});

function populateAllLevelCombo() {
    var level = $("#clevel").html();

    if (typeof (level) != "undefined" && level !== null && level != 0) {
        $("#office").val(level);
        $("#office").trigger("change");
    }

    $.ajax({
        type: "POST",
        url: appName + "/ajax/ajax-get-office",
        data: {},
        dataType: 'html',
        success: function (level) {
            $("#office").val(level);
            $("#office").trigger("change");
        }
    });
}