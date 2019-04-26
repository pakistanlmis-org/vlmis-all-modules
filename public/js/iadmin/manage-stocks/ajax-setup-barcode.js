/*$("#barcode_type").change(function() {
    var bar_id = $("#barcode_type").val();

    //if (bar_id == '48') {
    /*$('#gtin').attr("disabled", false);
     $('#batch').attr("disabled", false);
     $('#expiry').attr("disabled", false);
     $('#gtin_start_position').attr("readonly", false);
     $('#batch_no_start_position').attr("readonly", false);
     $('#expiry_date_start_position').attr("readonly", false);
     $('#gtin_end_position').attr("readonly", false);
     $('#batch_no_end_position').attr("readonly", false);
     $('#expiry_date_end_position').attr("readonly", false);
     $('#gtin').val('1');
     $('#batch').val('1');
     $('#expiry').val('1');
     $('#gtin_start_position').val('3');
     $('#batch_no_start_position').val('28');
     $('#expiry_date_start_position').val('19');
     $('#batch_no_end_position').val('16');
     $('#gtin_end_position').val('36');
     $('#expiry_date_end_position').val('25');* /

    $.ajax({
        type: "POST",
        url: appName + "/iadmin/manage-stocks/ajax-setup-barcode",
        data: {barcode_type_id: bar_id, number: $('#item_pack_size_id').val()},
        dataType: 'html',
        success: function(data) {
            $('#ajax_barcode').html(data);
        }
    });
    /*} else {
     $('#gtin').attr("disabled", true);
     $('#batch').attr("disabled", true);
     $('#expiry').attr("disabled", true);
     $('#gtin_start_position').attr("readonly", true);
     $('#batch_no_start_position').attr("readonly", true);
     $('#expiry_date_start_position').attr("readonly", true);
     $('#gtin_end_position').attr("readonly", true);
     $('#batch_no_end_position').attr("readonly", true);
     $('#expiry_date_end_position').attr("readonly", true);
     $('#gtin').val('1');
     $('#batch').val('1');
     $('#expiry').val('1');
     $('#gtin_start_position').val('3');
     $('#batch_no_start_position').val('28');
     $('#expiry_date_start_position').val('19');
     $('#batch_no_end_position').val('16');
     $('#gtin_end_position').val('36');
     $('#expiry_date_end_position').val('25');
     }* /
});*/