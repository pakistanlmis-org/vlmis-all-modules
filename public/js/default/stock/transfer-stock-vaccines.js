$(function () {
    $("#transfer_stock_vaccines").validate({
        rules: {
            asset_id: {
                required: true
            },
            quantity: {
                required: true,
                min: 1,
                max: $("#available_qty").val()
            }
        },
        messages: {
            asset_id: {
                required: "Enter locations."
            },
            quantity: {
                required: "Enter quantity."
            }
        }
    });

    $('#asset_id').change(function () {
        var bin_id = $("#bin_location_id").val();
        if ($('#asset_id').val() == bin_id) {
            alert("Already on this location, please choose another");
            $('#asset_id').prop('selectedIndex',0);
        }
    });
});