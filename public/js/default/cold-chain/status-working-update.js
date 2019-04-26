

$("select[id$='-working_status']").change(function () {

    var value = $(this).attr("id");

    var id = value.split('-');
    var working_status_id = $(this).val();
    if (working_status_id === '3') {
        
       
        //$("#" + id[0] + "-utilization").attr("disabled", "disabled");
        $("#" + id[0] + "-reason").removeAttr("disabled");
    } else {
        $("#" + id[0] + "-reason").attr("disabled", "disabled");
        //  $("#" + id[0] + "-utilization").removeAttr("disabled");

    }
});

$("input[id$='-work_quantity']").keyup(function (e) {
    var value = $(this).attr("id");
    var id = value.replace("-work_quantity", "");
    var qty = $(this).val();
    var ava_qty = $('#' + id + '-total_quantity').val();
    if (parseInt(qty) > parseInt(ava_qty)) {
        alert("Quantity should not be greater than " + ava_qty + ".");
        $(this).focus();

    }
});