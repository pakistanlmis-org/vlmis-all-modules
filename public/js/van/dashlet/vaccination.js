$(document).ready(function () {


    $("#van-filters").validate({
        rules: {
            province: {
                required: true
            },
            district: {
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
            'year-to': {
                required: "Please select year"
            },
            'month-to': {
                required: "Please select month"
            }
        }
    });
});


