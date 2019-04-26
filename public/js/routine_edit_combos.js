$(function() {
    

    $('#combo1_edit').change(function() {
        
        $('#combo2').empty();
        $('#div_combo2_edit').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $(this).val(), office: 6},
            dataType: 'html',
            success: function(data) {
               

                var val = '6';

                switch (val)
                {
                    case '4':
                        $('#div_combo2_edit').show();
                        $('#combo2_edit').html(data);
                        break;
                    case '5':
                        $('#div_combo2_edit').show();
                        $('#combo2_edit').html(data);
                        break;
                    case '6':
                        $('#div_combo2_edit').show();
                        $('#combo2_edit').html(data);
                        break;
                }
            }
        });
    });

    $('#combo2_edit').change(function() {
      
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-three",
            data: {combo2: $(this).val(), office: 6},
            dataType: 'html',
            success: function(data) {
               
                var val = '6';
                switch (val)
                {
                    case '5':
                        $('#div_combo3_edit').show();
                        $('#combo3_edit').html(data);
                        break;

                    case '6':
                        $('#div_combo3_edit').show();
                        $('#combo3_edit').html(data);
                        break;

                }
            }
        });
    });
    $('#combo3_edit').change(function() {
        
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-four",
            data: {combo3: $(this).val(), office: 6},
            dataType: 'html',
            success: function(data) {
                
                var val = '6';
                switch (val)
                {


                    case '6':
                        $('#div_combo4_edit').show();
                        $('#combo4_edit').html(data);
                        break;

                }
            }
        });
    });



    
    if ($('#combo1_edit').val() != "") {
        
        $('#combo2_edit').empty();
        
        $('#div_combo2_edit').hide();
       $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $('#province_id_edit').val(), office: 6, district_id: $('#district_id_edit').val()},
            dataType: 'html',
            success: function(data) {
               

                var val = '6';

                switch (val)
                {
                    case '4':
                        $('#div_combo2_edit').show();
                        $('#combo2_edit').html(data);
                        break;
                    
                    case '5':
                        $('#div_combo2_edit').show();
                        $('#combo2_edit').html(data);
                        break;
                    case '6':
                        $('#div_combo2_edit').show();
                        $('#combo2_edit').html(data);
                        break;
                }
            }
        });
    }
    ;

    if ($('#combo1_edit').val() != "") {
        
         $('#div_combo3_edit').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-three",
            data: {combo2: $('#district_id_edit').val(), office: 6, tehsil_id: $('#tehsil_id_edit').val()},
            dataType: 'html',
            success: function(data) {
                
                var val = '6';
                switch (val)
                {
                   
                    case '6':
                        $('#div_combo3_edit').show();
                        $('#combo3_edit').html(data);
                        break;

                }
            }
        });
    }
 if ($('#combo1_edit').val() != "") {
        
         $('#div_combo4_edit').hide();
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-four",
            data: {combo3: $('#tehsil_id_edit').val(), office: 6,uc_id:$('#parent_id_edit').val()},
            dataType: 'html',
            success: function(data) {
              
                var val = '6';
                switch (val)
                {
                        case '6':
                        $('#div_combo4_edit').show();
                        $('#combo4_edit').html(data);
                        break;

                }
            }
        });
    }








});