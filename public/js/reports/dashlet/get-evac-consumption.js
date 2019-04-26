$(function () {
    $("#spinner134").hide();
    $("#uc-combo").change(function () {
        $("#spinner134").show();
        $("#after-filter134").hide();
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-get-evac-consumption",
            data: {date: $("#date").val(), item: $("#items").val(), province: $('#combo1').val(), district: $('#combo2').val(), level: $('#office').val(), uc_id: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $("#spinner134").hide();
                $("#after-filter134").show();
                $("#after-filter134").html(data);
            }
        });
    });
});