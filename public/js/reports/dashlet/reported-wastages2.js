$(function() {
    $("#date, #items").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-reported-wastages",
            data: {date: $("#date").val(),item: $("#items").val(),province: $('#combo1').val(), district: $('#combo2').val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter").html(data);
            }
        });
    });
});