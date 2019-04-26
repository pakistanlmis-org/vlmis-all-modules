$('#office').change(function() {
    if ($(this).val() == 1) {
        $('#gen-chart-render').html("<img src='" + appName + "/images/loader.gif' style='margin:100px;'  />");
        $.ajax({
            type: "POST",
            url: appName + "/reports/graphs/ajax-refrigerator-by-working-status",
            data: {make: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#gen-chart-render').html(data);
            }
        });
    }
});

$('#combo1').change(function() {
    $('#gen-chart-render').html("<img src='" + appName + "/images/loader.gif' style='margin:100px;'  />");
    $.ajax({
        type: "POST",
        url: appName + "/reports/graphs/ajax-refrigerator-by-working-status",
        data: {make: $(this).val()},
        dataType: 'html',
        success: function(data) {
            $('#gen-chart-render').html(data);
        }
    });
});
