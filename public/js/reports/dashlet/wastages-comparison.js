$(function() {
    $("#wastages-combo").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/reports/dashlet/ajax-wastages-comparison",
            data: {date: $("#date").val(), item: $("#items").val(),province: $('#combo1').val(), district: $('#combo2').val(), allowed: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $("#after-filter10").html(data);
            }
        });
    });
});