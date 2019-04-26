$(function() {
    $("#date6, #item6").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-wastages-rate",
            data: {date: $("#date6").val(), item: $("#item6").val(),province: $('#combo1').val(), district: $('#combo2').val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter6").html(data);
            }
        });
    });
});