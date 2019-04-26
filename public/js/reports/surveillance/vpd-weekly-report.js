$(document).ready(function () {
  var h = $(".dashboard-header").css('height');
    if (h != "51px") {
        $(".sidebar-toggler").trigger("click");
    }
    })

$("#from_month_sel").change(function () {

    $.ajax({
        type: "POST",
        url: appName + "/reports/surveillance/ajax-get-to-weeks",
        data: {from_month_id: $('#from_month_sel').val()},
        dataType: 'html',
        success: function (data) {
            $('#month_sel').html(data);
        }
    });
});

 