$(function () {
    $("#transfer_stock").validate({
        rules: {
            non_ccm_location_id: {
                required: true
            },
            quantity: {
                required: true,
                min: 1,
                max: $("#totqty").val()
            }
        },
        messages: {
            non_ccm_location_id: {
                required: "Enter locations."
            },
            quantity: {
                required: "Enter quantity."
            }
        }
    });
    
//    $('#quantity').keyup(function (e) {
//        var ava_qty = $("#totqty").val();
//
//        ava_qty = parseInt(ava_qty.replace(/,/g, ""), 10);
//
//        var qty = $(this).val();
//        qty = parseInt(qty.replace(/,/g, ""), 10);
//
//        if (qty > ava_qty) {
//            alert("Quantity should not be greater than " + ava_qty + ".");
//            // $('#error').show();
//            $(this).focus();
//        }
//
//    });

    $('#non_ccm_location_id').change(function () {
        var non_ccm_id = $("#non_ccm_id").val();
        if ($('#non_ccm_location_id').val() == non_ccm_id)
        {
            alert("Already on this location, please choose another");
            $(this).prop('selectedIndex', 0);
            $(this).focus();
        }

    });
});



/*$("#btn-transfer").click(function() {
 // $("#transfer").click(function() {  
 varform = $('#transfer-stock').serialize();
 //alert(varform);
 $.ajax({
 type: "POST",
 url: appName + "/stock/transfer-stock?"+varform,
 //url: appName + "/stock/transfer-stock?"+varform+'item_id'+$(this).attr('editid')+'locid'+$('#locid').val(),
 
 //data: {barcode_type_id: bar_id, number: $('#item_pack_size_id').val()},
 dataType: 'html',
 
 success: function(data) {
 //alert(url);
 $('#modal-body-contents').html(data);
 $('#transfer-stock').show();
 }
 //});
 });});*/
