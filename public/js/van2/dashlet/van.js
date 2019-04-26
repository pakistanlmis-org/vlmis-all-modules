$(document).ready(function () {


    $("#van-filters").validate({
        rules: {
            province: {
                required: true
            },
            district: {
                required: true
            },
            year: {
                required: true
            },
            month: {
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
            year: {
                required: "Please select year"
            },
            month: {
                required: "Please select month"
            }
        }
    });


    $("#items").change(function () {
        Metronic.startPageLoading('Please wait...');
        var selectedVccine = this.value;
        var selectedMonth = $("#month").val();
        var selectedYear = $("#year").val();
        var selectedDistrict = $("#district").val();

        $.ajax({
            type: "POST",
            url: appName + "/van2/dashlet/ajax-get-stock-status",
            data: {item: selectedVccine, month: selectedMonth, year: selectedYear, district: selectedDistrict},
            dataType: 'html',
            success: function (data) {
                Metronic.stopPageLoading();
                $("#ss").html(data);
            }
        });
    });
});


