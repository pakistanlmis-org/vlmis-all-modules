$(function() {
    $("#date3, #item3").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-get-soh",
            data: {date: $("#date3").val(),item: $("#item3").val(),province: $('#combo1').val(), district: $('#combo2').val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter3").html(data);
            }
        });
    });
});