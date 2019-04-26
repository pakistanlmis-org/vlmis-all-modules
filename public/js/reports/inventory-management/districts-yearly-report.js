
$("#year_sel").change(function() {

    $.ajax({
        type: "POST",
        url: appName + "/reports/inventory-management/ajax-get-months",
        data: {year_id: $('#year_sel').val()},
        dataType: 'html',
        success: function(data) {
            $('#ending_month').html(data);
            
        }
    });
});


 