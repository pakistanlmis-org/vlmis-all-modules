$(function() {
    $("#stock-status-combo").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-stock-status",
            data: {level: $(this).val(),province: $('#combo1').val(), district: $('#combo2').val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter").html(data);
            }
        });
    });
});