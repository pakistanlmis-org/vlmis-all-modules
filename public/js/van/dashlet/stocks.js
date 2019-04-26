

$(document).ready(function () {

    $('#district').change(function () {
        if ($(this).val() == "") {
            $("#tehsil").empty();
        }
        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/van/dashlet/ajax-get-tehsils",
                data: {district_id: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    $("#tehsil").html(data);
                }
            });
        }
    });

    $("#van-filters").validate({
        rules: {
            province: {
                required: true
            },
            district: {
                required: true
            },
            tehsil: {
                required: true
            },
            'year-to': {
                required: true
            },
            'month-to': {
                required: true
            }
        },
        messages: {
            province: {
                required: "Please select province"
            },
            district: {
                required: "Please select district"
            },
            tehsil: {
                required: "Please select tehsil"
            },
            'year-to': {
                required: "Please select year"
            },
            'month-to': {
                required: "Please select month"
            }
        }
    });
});


