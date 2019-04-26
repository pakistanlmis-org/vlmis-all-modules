$(function() {

    $("#combo2_add").change(function() {
        $("#campaign").empty();
        var dist = $("#combo2_add").val();
        $.ajax({
            type: "POST",
            url: appName + "/reports/campaign/ajax-get-campaigns-by-district",
            data: {district: dist, level: 4},
            dataType: 'html',
            success: function(data) {
                $("#campaign").html(data);
            }
        });
    });



});