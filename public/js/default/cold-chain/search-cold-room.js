$(function () {
    $("#working_since_from").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#working_since_to").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    if ($("#placed_at-1").is(":checked")) {
        $("#all_level_combo").show();
        $('#office').val($('#office_id').val());
    }
    if ($("#placed_at-0").is(":checked")) {
        $("#all_level_combo").hide();
    }

    if ($('#office').val() != "") {
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
            data: {office: $('#office_id').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader').hide();
                var val1 = $('#office').val();
                switch (val1) {
                    case '1':
                        $('#wh_l').html('Warehouse');
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        $('#warehouse').val($('#warehouse_id').val());
                        break;
                    case '2':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        $('#combo1').val($('#combo1_id').val());

                        break;
                    case '3':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        $('#combo1').val($('#combo1_id').val());
                        break;
                    case '4':
                        $('#lblcombo1').text('Province');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        $('#combo1').val($('#combo1_id').val());
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
        });
    }

    if ($('#combo1').val() != "") {
        $('#loader').show();
        $('#combo2').empty();
        $('#warehouse').empty();
        $('#div_combo2').hide();
        $('#wh_combo').hide();

        $.ajax({
            type: "POST",
            url: appName + "/index/all-level-combos-two",
            data: {combo1: $('#combo1_id').val(), office: $('#office_id').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader').hide();

                var val = $('#office').val();
                switch (val)
                {
                    case '2':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        $('#warehouse').val($('#warehouse_id').val());
                        break;
                    case '3':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        $('#warehouse').val($('#warehouse_id').val());
                        break;
                    case '4':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        $('#warehouse').val($('#warehouse_id').val());
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
            }
        });
    }

    if ($('#make').val() != "") {
        $('#loader_make').show();
        $.ajax({
            type: "POST",
            url: appName + "/cold-chain/ajax-get-models",
            data: {make: $('#make').val(), model: $('#model_id').val()},
            dataType: 'html',
            success: function (data) {
                $('#loader_make').hide();
                $('#div_model').show('slow');
                $('#ccm_model_id').html(data);
                $('#ccm_model_id').val($('#model_id').val());
            }
        });
    }
    $('#make').change(function () {
        $('#model').html('');
        $('#div_model').hide();
        if ($(this).val() != "") {
            $('#loader_make').show();
            $.ajax({
                type: "POST",
                url: appName + "/cold-chain/ajax-get-models",
                data: {make: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    $('#loader_make').hide();
                    $('#div_model').show('slow');
                    $('#ccm_model_id').html(data);
                    $('#ccm_model_id').css('backgroundColor', 'Green');
                    setTimeout(changeColor, 1000);
                }
            });
        }
    });
});

$("#placed_at-0").click(function () {
    $("#all_level_combo").hide();
    $('#warehouse').attr("disabled", true);
});
$("#placed_at-1").click(function () {
    $('#warehouse').attr("disabled", false);
    $("#all_level_combo").show();
    $('#office').css('backgroundColor', 'Green');
    $.cookie('blink_div_background_color', "office");
    setTimeout(changeColor, 500);
});

$('#report_type-0').click(function () {
    $('#summary_div').hide();
    $("#details_div").show();
});
$('#report_type-1').click(function () {
    $("#details_div").hide();
    $('#summary_div').removeClass('hidden');
    $('#summary_div').show();
});


//$('#last-updated').click(function () {
 $('#l_update').html('loading...');
    $.ajax({
        type: "POST",
        url: appName + "/cold-chain/ajax-get-last-update",
        data: {id: $('#last-updated').attr("pkId")},
        dataType: 'html',
        success: function (data) {
            $('#l_update').html(data);
        }
    });
//});


$(document).on("click", "a[id$='print_button']", function () {
    var value = this.id;
    var pk_id = value.replace("print_button", "");
    window.open(appName + '/cold-chain/print-cold-room?id=' + pk_id, '_blank', 'scrollbars=1,width=700,height=595');
});
