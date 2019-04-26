$(function() {
    $("#spinner").hide();
    $("#mos-combo").change(function() {
        $("#spinner").show();
        $("#after-filter12").hide();
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-get-mos",
            data: {date: $("#date").val(), item: $("#items").val(),province: $('#combo1').val(), district: $('#combo2').val(), level: $('#office').val(), limit: $(this).val(), filter: $('#mos-combo option:selected').text()},
            dataType: 'html',
            success: function(data) {
                $("#spinner").hide();
                $("#after-filter12").show();
                $("#after-filter12").html(data);
            }
        });
    });
});