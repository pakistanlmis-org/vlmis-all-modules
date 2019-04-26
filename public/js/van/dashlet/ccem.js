$(document).ready(function () {


    $("#van-filters").validate({
        rules: {
            province: {
                required: true
            },
            district: {
                required: true
            }
        },
        messages: {
            province: {
                required: "Please select province"
            },
            district: {
                required: "Please select district"
            }
        }
    });
});


