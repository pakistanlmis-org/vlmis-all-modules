$('#month').change(function() {
    $("#main_div").css("visibility", "hidden");

});
$('#year').change(function() {
    $("#main_div").css("visibility", "hidden");

});



$(".stock-issue").click(function() {
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-stock-issue",
        data: {item_id: $(this).attr('id')},
        dataType: 'html',
        success: function(data) {
            $('#modal-body-contents').html(data);
            $('#update-button').show();
            $('#transaction_date').datetimepicker({
                dayOfWeekStart: 1,
                lang: 'en',
                format: 'd/m/Y h:i A',
                maxDate: 0
            });
        }
    });
});
