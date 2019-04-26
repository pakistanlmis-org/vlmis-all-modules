$(function() {
    $("#date2, #item2").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-get-mos",
            data: {date: $("#date2").val(),item: $("#item2").val(),province: $('#combo1').val(), district: $('#combo2').val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter2").html(data);
            }
        });
    });
});