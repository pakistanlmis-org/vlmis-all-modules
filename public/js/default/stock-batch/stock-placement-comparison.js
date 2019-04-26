$(document).ready(function () {
    $("#office option[value='60']").remove();

    $("#stock_compare").validate({
        rules: {
            office: {
                required: true
            },
            combo1: {
                required: true
            },
            combo2: {
                required: true
            },
            warehouse: {
                required: true
            }
        },
        messages: {
            office: {
                required: "Please select office"
            },
            combo1: {
                required: "Please select province"
            },
            combo2: {
                required: "Please select district"
            },
            warehouse: {
                required: "Please select store"
            }
        }
    });
}); 