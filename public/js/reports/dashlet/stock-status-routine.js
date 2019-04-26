$(function() {
    $("#date7, #item7").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-stock-status-routine",
            data: {date: $("#date7").val(), item: $("#item7").val(),province: $('#combo1').val(), district: $('#combo2').val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter7").html(data);
            }
        });
    });
});