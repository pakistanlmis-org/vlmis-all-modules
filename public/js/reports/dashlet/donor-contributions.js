
function showSubGraphs(wh_id)
{


    $.ajax({
        type: "POST",
        url: appName + "/reports/dashlet/ajax-contribution-breakup",
        data: {wh_id: wh_id, to_date: $('#to_date').val()},
        dataType: 'html',
        success: function (data) {
            $('#div_1').html(data);
        }
    });
    $.ajax({
        type: "POST",
        url: appName + "/reports/dashlet/ajax-provincially-vaccination",
        data: {wh_id: wh_id, to_date: $('#to_date').val()},
        dataType: 'html',
        success: function (data) {
            $('#div_2').html(data);
        }
    });
    $.ajax({
        type: "POST",
        url: appName + "/reports/dashlet/ajax-product-wise-contribution",
        data: {wh_id: wh_id, to_date: $('#to_date').val()},
        dataType: 'html',
        success: function (data) {
            $('#div_3').html(data);
        }
    });
}