$(function() {
 
    
    $('#facility_type').change(function() {
        if ($('#facility_type').val() == 1) {
            $('#div_office_combo').hide();
            $('#combo1').empty();
            $('#combo2').empty();
            $('#div_combo1').hide();
            $('#div_combo2').hide();
        } else if ($('#facility_type').val() == 2) {
            $('#div_office_combo').hide();
            $('#combo1').empty();
            $('#combo2').empty();
            $('#div_combo1').hide();
            $('#div_combo2').hide();

            $('#loader').show();
            $.ajax({
                type: "POST",
                url: appName + "/index/all-level-combos-one",
                data: {office: '2'},
                dataType: 'html',
                success: function(data) {
                    $('#loader').hide();
                    var val1 = '2';
                    switch (val1) {
                        case '2':
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
        } else {
        //alert($('#facility_type').val());
            $('#div_office_combo').show();
            $('#office').val();
            $('#combo1').empty();
            $('#combo2').empty();
            $('#div_combo1').hide();
            $('#div_combo2').hide();
        }

    });

    $('#office').change(function() {
        $('#combo1').empty();
        $('#combo2').empty();
        $('#div_combo1').hide();
        $('#div_combo2').hide();
        if ($('#office').val() > 1) {
            $('#loader').show();
            $.ajax({
                type: "POST",
                url: appName + "/index/all-level-combos-one",
                data: {office: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $('#loader').hide();
                    var val1 = $('#office').val();
                    switch (val1) {
                        case '2':
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
    });

    $('#combo1').change(function() {
        $('#combo2').empty();
        $('#warehouse').empty();
        $('#div_combo2').hide();
        $('#wh_combo').hide();

        if ($('#office').val() > 2) {
            $('#loader').show();
            $.ajax({
                type: "POST",
                url: appName + "/index/all-level-combos-two",
                data: {combo1: $(this).val(), office: $('#office').val()},
                dataType: 'html',
                success: function(data) {
                    $('#loader').hide();

                    var val = $('#office').val();
                    switch (val){
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
    });

});

 if ($('#facility_type').val() == 1 )  {
       $('#div_office_combo').hide();
      }
       else if ($('#facility_type').val() == 2 )  {
       $('#div_office_combo').hide();
      }
        else  {
       $('#div_office_combo').show();
      }