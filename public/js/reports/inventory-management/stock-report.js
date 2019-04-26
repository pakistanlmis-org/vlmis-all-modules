$(function () {
    setOffice();
    var office = $("#cmbo1").val();
    if (office != 1) {
        setTimeout(setProv, 1000);
    }
    setTimeout(setWh, 3000);

    $("#product_plac").validate({
        rules: {
            office: {
                required: true
            },
            warehouse: {
                required: true
            },
            combo1: {
                required: true
            }
        },
        messages: {
            office: {
                required: "Please select office."
            },
            warehouse: {
                required: "Please select store name."
            },
            combo1: {
                required: "Please select province."
            }
        }
    });
});

function setOffice() {
    var office = $("#cmbo1").val();
    if (office != '') {
        $("#office").val(office);
        $("#office").trigger("change");
    }
}

function setProv() {
    var province = $("#cmbo2").val();
    if (province != '') {
        $("#combo1").val(province);
        $("#combo1").trigger("change");
    }
}

function setWh() {
    var wh = $("#cmbo3").val();
    if (wh != '') {
        $("#warehouse").val(wh);
    }
}