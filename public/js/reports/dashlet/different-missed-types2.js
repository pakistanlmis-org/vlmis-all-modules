$(function() {
    $("#camp2").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-different-missed-types",
            data: {camp: $("#camp2").val(),province: $('#combo1').val(), district: $('#combo2').val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter9").html(data);
            }
        });
    });
});