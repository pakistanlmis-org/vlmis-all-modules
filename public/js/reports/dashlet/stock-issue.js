$(function() {
    $("#date4, #item4").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-stock-issue",
            data: {date: $("#date4").val(), item: $("#item4").val(),province: $('#combo1').val(),level: $('#office').val(), district: $('#combo2').val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter4").html(data);
            }
        });
    });
});