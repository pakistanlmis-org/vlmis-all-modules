$('#product').change(function () {
    $("#batch_tracking-div").hide();
    Metronic.startPageLoading('Please wait...');
    $.ajax({
        type: "POST",
        url: appName + "/reports/inventory-management/ajax-get-warehouses-by-product",
        data: {product: $(this).val()},
        dataType: 'html',
        success: function (data) {
            $('#from_wh').html(data);
            Metronic.stopPageLoading();
        }
    });
});

$('#from_wh').change(function () {
    $("#batch_tracking-div").hide();
    Metronic.startPageLoading('Please wait...');
    $.ajax({
        type: "POST",
        url: appName + "/reports/inventory-management/ajax-get-to-warehouses-by-product",
        data: {product: $('#product').val(), from_wh: $(this).val()},
        dataType: 'html',
        success: function (data) {
            $('#to_wh').html(data);
            Metronic.stopPageLoading();
        }
    });
});

$('#to_wh').change(function () {
    $("#batch_tracking-div").hide();
});

$("a[id$='-batchlist']").click(function () {
    var id = $(this).attr("id");
    var batch = id.replace("-batchlist", "");

    //$("a[id$='-batchlist']").switchClass("btn-danger","btn-active");
    //$("a[id$='-batchlist']").removeClass("btn-danger");
    $(this).addClass("btn-inverse");
    $(this).removeClass("btn-success");

    Metronic.startPageLoading('Please wait...');
    $.ajax({
        type: "POST",
        url: appName + "/reports/inventory-management/ajax-get-batch-data",
        data: {product: $('#product').val(), from_wh: $("#from_wh").val(), to_wh: $("#to_wh").val(), batch_id: batch},
        dataType: 'html',
        success: function (data) {
            $("#batch_tracking-div").show();
            $('#batch_detail').html(data);

            $(".linkit").change(function () {
                var receive_detail = $(this).attr("id");
                Metronic.startPageLoading('Please wait...');
                $.ajax({
                    type: "POST",
                    url: appName + "/reports/inventory-management/ajax-link-receive-with-issue",
                    data: {issue_detail_id: $(this).val(), receive_detail_id: receive_detail},
                    dataType: 'html',
                    success: function (data) {
                        $("#" + batch + "-batchlist").trigger("click");
                        Metronic.stopPageLoading();
                        $('#notific8_show').trigger('click');
                    }
                });
            });

            $(".go-top").trigger("click");
            Metronic.stopPageLoading();
        }
    });
});