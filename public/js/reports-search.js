$(function() {
//    $('#combo1').change(function() {
//        $('#loader').show();
//        $('#combo2').empty();
//        $('#div_combo2').hide();
//        $.ajax({
//            type: "POST",
//            url: appName + "/index/locations-combos-two",
//            data: {combo1: $(this).val(), office: 6},
//            dataType: 'html',
//            success: function(data) {
//                $('#loader').hide();
//
//                var val = '6';
//
//                switch (val)
//                {
//                    case '4':
//                        $('#div_combo2').show();
//                        $('#combo2').html(data);
//                        break;
//                    case '5':
//                        $('#div_combo2').show();
//                        $('#combo2').html(data);
//                        break;
//                    case '6':
//                       // $('#div_combo2').show();
//                        $('#combo2_add').html(data);
//                        break;
//                }
//            }
//        });
//    });

    $('#combo1_add').change(function() {
     //   $('#loader').show();
        $('#combo2_add').empty();
       // $('#div_combo2_add').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $(this).val(), office: 6},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();

                var val = '6';

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

    if ($('#combo1_add').val() != "") {
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $('#combo1_add').val(), office: 6},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();

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
                        $('#combo2_add').html(data);
                        $('#combo2_add').val($('#district_id_hidden').val());

                        break;
                }
            }
        });

    }

});