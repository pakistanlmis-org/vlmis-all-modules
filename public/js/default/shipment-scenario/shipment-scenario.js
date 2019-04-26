$(function () {

    $("#product").change(function () {

        var item_id = $('#product').val();

        $.ajax({
            type: "POST",
            url: appName + "/stock-batch/ajax-placement-locations",
            data: {product: item_id},
            dataType: 'html',
            success: function (data) {
                $('#cold_chain').html(data);
            }
        });
    });



});