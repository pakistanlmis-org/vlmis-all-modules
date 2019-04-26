$('#search').click(function() {
    var qry_str = "?facility_type=" + $('#facility_type').val() + "&office=" + $('#office').val() + "&combo1=" + $('#combo1').val() + "&combo2=" + $('#combo2').val();

    if ($('#facility_type').val() != "") {
        $('#gen-chart-render').html("<img src='" + appName + "/images/loader.gif' style='margin:100px;'  />");
        $.ajax({
            type: "POST",
            url: appName + "/reports/graphs/ajax-refrigerators-freezers-utilization" + qry_str,
            data: {},
            dataType: 'html',
            success: function(data) {
                $('#gen-chart-render').html(data);
            }
        });
    }else{
        // $('#facility_type').val() 
        // validation here...
    }
});
