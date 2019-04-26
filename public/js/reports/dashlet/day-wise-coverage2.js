$(function() {
    $("#prov, #camp").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-day-wise-coverage",
            data: {prov: $("#prov").val(),camp: $("#camp").val(),province: $('#combo1').val(), district: $('#combo2').val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter8").html(data);
            }
        });
    });
});