$("#from_month_sel").change(function () {
    $('#month_sel').html('<option value="">Loading...</option>');
    $.ajax({
        type: "POST",
        url: appName + "/reports/inventory-management/ajax-get-to-months",
        data: {from_month_id: $('#from_month_sel').val()},
        dataType: 'html',
        success: function (data) {
            $('#month_sel').html(data);
        }
    });
});
$("#wh_type").change(function () {
    if ($(this).val() == '2')
    {
        $('#prod_sel').html("");
        $('#prod_sel').append($('<option />').val('all').text('All'));
        $('#prod_sel').append($('<option />').val('2').text('PCV-IPV'));
        $('#prod_sel').append($('<option />').val('3').text('TT'));

    }
    if ($(this).val() == '4')
    {
        $('#prod_sel').html("");
        $('#prod_sel').append($('<option />').val('all').text('All'));

        $('#prod_sel').append($('<option />').val('3').text('TT'));
    }
});

$(document).ready(function () {
    var h = $(".dashboard-header").css('height');
    if (h != "51px") {
        $(".sidebar-toggler").trigger("click");
    }
})