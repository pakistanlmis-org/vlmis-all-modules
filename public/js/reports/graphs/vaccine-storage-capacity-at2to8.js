$('#search').click(function() {
    var qry_str = "?office=" + $('#office').val() + "&combo1=" + $('#combo1').val() + "&combo2=" + $('#combo2').val();
    $('#gen-chart-render').html("<img src='" + appName + "/images/loader.gif' style='margin:100px;'  />");
    $.ajax({
        type: "POST",
        url: appName + "/reports/graphs/ajax-vaccine-storage-capacity-at2to8" + qry_str,
        data: {},
        dataType: 'html',
        success: function(data) {
            $('#gen-chart-render').html(data);
        }
    });
});
