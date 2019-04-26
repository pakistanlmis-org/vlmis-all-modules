$(function() {
    var level = $("#clevel").html();
    var prov = $("#cprov").html();
    var dist = $("#cdist").html();
    var wh_id = $("#cwh").html();

    $('#office').change(function() {

        $('#div_combo1').hide();
        $('#div_combo2').hide();
         $('#div_combo3').hide();
        $('#wh_combo').hide();
        $('#loader').show();

        $.ajax({
            type: "POST",
            url: appName + "/index/level-combos-one",
            data: {office: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();
                var val1 = $('#office').val();
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
                        $('#lblcombo1').text('Division');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        break;
                    case '4':
                        $('#lblcombo1').text('District');
                        $('#div_combo1').show();
                        $('#combo1').html(data);
                        break;
                    case '5':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
                    case '6':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
                    case '7':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
                    case '8':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
                    case '9':
                        $('#lblcombo3').text('Tehsil / Town');
                        $('#div_combo3').show();
                        $('#combo3').html(data);
                        // $('#combo2').show();
                        // $('#warehouse').html(data);
                        break;
                    case '60':
                    case '84':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
                }

                if (val1 == 2 || val1 == 3 || val1 == 4) {
                    $("#combo1").val(prov);
                    $("#combo1").trigger("change");
                } else {
                    $("#warehouse").val(wh_id);
                }
            }
        });
    });

    $('#combo1').change(function() {
        $('#loader').show();
        $('#div_combo2').hide();
        $('#wh_combo').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/level-combos-two",
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
                    case '5':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
                    case '6':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;
                    case '7':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        break;

                    case '3':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        //$('#lblcombo2').text('Divisions');
                        //	$('#div_combo2').show();
                        //	$('#combo2').show();
//					$('#combo2').html(data);				
                        break;
                    case '4':
                        $('#wh_combo').show();
                        $('#warehouse').html(data);
                        /*					$('#lblcombo2').text('Districts');
                         $('#div_combo2').show();
                         $('#combo2').show();
                         $('#combo2').html(data);	*/
                        break;
                    case '8':
                        $('#div_combo2').show();
                        $('#combo2').show();
                        $('#combo2').html(data);
                        break;
                    case '9':
                        $('#div_combo2').show();
                        $('#combo2').show();
                        $('#combo2').html(data);
                        break;

                }

                if (val == 8 || val == 9) {
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
            url: appName + "/index/level-combos-three",
            data: {division: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();
                $('#wh_combo').show();
                $('#warehouse').html(data);
                $("#warehouse").val(wh_id);
            }
        });
    });
    
  $('#combo3').change(function() {
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/index/level-combos-four",
            data: {combo3: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();
                $('#wh_combo').show();
                $('#warehouse').html(data);
                $("#warehouse").val(wh_id);
            }
        });
    });  
    
});

function populateAllLevelCombo() {
    var level = $("#clevel").html();

    if (typeof (level) != "undefined" && level !== null) {
        $("#office").val(level);
        $("#office").trigger("change");
    }
}