$.ajax({
    type: "POST",
    url: appName + "/cold-chain/ajax-ice-packs-unallocated",
    data: {unallocated: 1},
    dataType: 'html',
    success: function (data) {
        if (data === "") {
            $('#show_data').hide();
            $('#hide_data').show();
            $('#hide_data').css("visibility", "visible");
            $('#add_ice_packs').css("visibility", "visible");
            $('#add_ice_packs').show();
        } else {
            $('#add_ice_packs').hide();
            $('#hide_data').hide();
            $('#hide_data').css("visibility", "hidden");
            $('#add_ice_packs').css("visibility", "hidden");
            $('#show_data').show();
            $('#show_data').html(data);
        }
    }
});

$("#all_level_combo").hide();
$("#placed_at-0").click(function () {
    $("#all_level_combo").hide();
});
$("#placed_at-1").click(function () {
    $("#all_level_combo").show();
});

$("#ice_pack_form").validate({
    rules: {
        office: {
            required: true

        },
        combo1: {
            required: true
        },
        warehouse: {
            required: true
        }
    }
});

//Working Status Validation
$('input.quantity').each(function () {
    $(this).rules('add', {
        required: true,
        number: true,
        min: 0
    });
});

$("#placed_at-0").click(function () {
    $.ajax({
        type: "POST",
        url: appName + "/cold-chain/ajax-ice-packs-unallocated",
        data: {unallocated: 1},
        dataType: 'html',
        success: function (data) {
            if (data === "") {
                $('#show_data').hide();
                $('#hide_data').show();
                $('#hide_data').css("visibility", "visible")
                $('#add_ice_packs').css("visibility", "visible");
                $('#add_ice_packs').show();
                $('#submit_button').show();

            } else {
                $('#add_ice_packs').hide();
                $('#hide_data').hide();
                $('#hide_data').css("visibility", "hidden");
                $('#add_ice_packs').css("visibility", "hidden");
                $('#show_data').show();
                $('#show_data').html(data);
                $('#submit_button').hide();
            }
        }
    });
});
$("#placed_at-1").click(function () {
    $('#show_data').hide();
    $('#hide_data').show();
});
$('#warehouse').change(function () {
    $.ajax({
        type: "POST",
        url: appName + "/cold-chain/ajax-ice-packs",
        data: {wh_id: $('#warehouse').val(), unallocated: 0},
        dataType: 'html',
        success: function (data) {
            if (data === "") {
                $('#show_data').hide();
                $('#hide_data').show();
                $('#hide_data').css("visibility", "visible");
                $('#add_ice_packs').show();
                $('#add_ice_packs').css("visibility", "visible");
                $('#submit_button').show();
            } else {
                $('#add_ice_packs').hide();
                $('#hide_data').hide();
                $('#hide_data').css("visibility", "hidden");
                $('#add_ice_packs').css("visibility", "hidden");
                $('#show_data').show();
                $('#show_data').html(data);
                $('#submit_button').hide();
            }
        }
    });
});

