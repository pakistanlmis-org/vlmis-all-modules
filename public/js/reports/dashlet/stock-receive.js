$(function() {
    $("#date5, #item5").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-stock-receive",
            data: {date: $("#date5").val(), item: $("#item5").val(),province: $('#combo1').val(), district: $('#combo2').val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter5").html(data);
            }
        });
    });
});