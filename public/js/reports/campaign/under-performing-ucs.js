$(function() {


    $('#combo1').change(function() {
     //   $('#loader').show();
        $('#combo2').empty();
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
                       
                        $('#combo2').html(data);
                        break;
                    case '5':
                      
                        $('#combo2').html(data);
                        break;
                    case '6':
                  
                        $('#combo2').html(data);
                        break;
                }
            }
        });
    });

    if ($('#combo1').val() != "") {
        $.ajax({
            type: "POST",
            url: appName + "/index/locations-combos-two",
            data: {combo1: $('#combo1').val(), office: 6},
            dataType: 'html',
            success: function(data) {
                $('#loader').hide();

                var val = '6';

                switch (val)
                {
                    case '4':
                     
                        $('#combo2').html(data);
                        break;
                    case '5':
                        
                        $('#combo2').html(data);
                        break;
                    case '6':
                       
                        $('#combo2').html(data);
                        $('#combo2').val($('#district_id_hidden').val());

                        break;
                }
            }
        });

    }
 $("#office").change(function () {
        $("#campaign").empty();
        var level = $("#office").val();
        if (level == 1) {
            $.ajax({
                type: "POST",
                url: appName + "/reports/campaign/ajax-get-campaigns",
                data: {district: $('#combo2').val(), level: 1},
                dataType: 'html',
                success: function (data) {
                    $("#campaign").html(data);
                }
            });
        }
    });

    $("#combo1").change(function () {
        $("#campaign").empty();
        var prov = $("#combo1").val();
        $.ajax({
            type: "POST",
            url: appName + "/reports/campaign/ajax-get-campaigns",
            data: {province: prov, level: 2},
            dataType: 'html',
            success: function (data) {
                $("#campaign").html(data);
            }
        });
    });

    $("#combo2").change(function () {
        $("#campaign").empty();
        var dist = $("#combo2").val();
        $.ajax({
            type: "POST",
            url: appName + "/reports/campaign/ajax-get-campaigns",
            data: {district: dist, level: 6},
            dataType: 'html',
            success: function (data) {
                $("#campaign").html(data);
            }
        });
    });
});