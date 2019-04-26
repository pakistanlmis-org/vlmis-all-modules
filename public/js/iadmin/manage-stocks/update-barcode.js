$(function() {

    if ($("#item_pack_size_id_hidden").val() != "") {

        $('#stakeholder_id').html('');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stocks/ajax-get-manufacturer-by-product",
            data: {item_id: $('#item_pack_size_id_hidden').val()},
            dataType: 'html',
            success: function(data) {
                $('#stakeholder_id_update').html(data);
                $('#stakeholder_id_update').val($('#stakeholder_id_update_hidden').val());
            }
        });
    }
    $("#item_pack_size_id_update").change(function() {
        $('#stakeholder_id').html('');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-stocks/ajax-get-manufacturer-by-product",
            data: {item_id: $('#item_pack_size_id_update').val()},
            dataType: 'html',
            success: function(data) {
                $('#stakeholder_id_update').html(data);
            }
        });
    });



    // alert($("#barcode_type").val());
    $("#barcode_type").change(function() {
        //alert("Hello");
        //alert($(this).val());
        if ($("#barcode_type").val() != "") {
            var bar_id = $("#barcode_type").val()
            //alert(bar_id);
            if (bar_id == '48') {
                $('#gtin').attr("disabled", false);
                $('#batch').attr("disabled", false);
                $('#expiry').attr("disabled", false);
                $('#gtin_start_position').attr("readonly", false);
                $('#batch_no_start_position').attr("readonly", false);
                $('#expiry_date_start_position').attr("readonly", false);
                $('#gtin_end_position').attr("readonly", false);
                $('#batch_no_end_position').attr("readonly", false);
                $('#expiry_date_end_position').attr("readonly", false);

                $.ajax({
                    type: "POST",
                    url: appName + "/iadmin/manage-stocks/ajax-update-barcode",
                    data: {barcode_type_id: bar_id},
                    dataType: 'html',
                    success: function(data) {
                        $('#ajax_barcode').html(data);
                    }
                });
            } else {
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
            }
        }
    });
    var complete;
    var isValid = false;
    $("#update-barcode").validate({
        rules: {
            item_pack_size_id_update: {
                required: true
            },
            stakeholder_id_update: {
                required: true
            },
            packaging_level_update: {
                required: true
            },
            batch_length: {
                required: true,
                number: true
            },
            item_gtin: {
                required: true
            },
            length: {
                required: true,
                number: true
            },
            width: {
                required: true,
                number: true
            },
            height: {
                required: true,
                number: true
            },
            quantity_per_pack: {
                required: true
            },
            volume_per_unit_net: {
                required: true,
                number: true
            }
        },
        messages: {
            item_pack_size_id_update: {
                required: "Select Product."
            },
            stakeholder_id_update: {
                required: "Select Manufacturer."
            },
            packaging_level_update: {
                required: "Select Packaging Level."
            },
            batch_length: {
                required: "Select Batch Length."
            },
            item_gtin: {
                required: "Enter Item GTIN."
            },
            length: {
                required: "Enter length."
            },
            width: {
                required: "Enter width."
            },
            height: {
                required: "Enter height."
            },
            quantity_per_pack: {
                required: "Enter Vials/Pcs."
            },
            volume_per_unit_net: {
                required: "Enter Volume."
            }
        },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: appName + "/iadmin/manage-stocks/check-setup-barcode-combination-update",
                data: {item_pack_size_id: $('#item_pack_size_id_update').val(), stakeholder_id: $('#stakeholder_id_update').val(), packaging_level: $('#packaging_level_update').val(), barcode_type: $('#barcode_id').val()},
                dataType: 'html',
                success: function(data) {
                    isValid = data;
                    complete = true;//mark ajax as complete
                    runOnComplete();//callback
                }
            });
            function runOnComplete() {
                if (complete) {//run when ajax completes and flag is true
                    if (isValid == 't')
                    {
                        form.submit();
                    }
                    else
                    {
                        alert('Item Pack Size Already Exits');
                        return false;
                    }
                } else {
                    setTimeout(runOnComplete, 25);//when ajax is not complete then loop
                }
            }

        }
    });
});


