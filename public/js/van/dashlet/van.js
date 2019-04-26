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
});


