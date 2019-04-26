$(function() {//
//    $('#item_pack_size_id').change(function() {
//        $('#stock_batch_id').html('');
//        if ($(this).val() != "") {
//            $.ajax({
//                type: "POST",
//                url: appName + "/stock/ajax-get-batch-number",
//                data: {item_pack_size_id: $(this).val()},
//                dataType: 'html',
//                success: function(data) {
//                    $('#stock_batch_id').html(data);
//                    $('#stock_batch_id').css('backgroundColor', 'Green');
//                    $.cookie('blink_div_background_color', "stock_batch_id");
//                    setTimeout(changeColor, 1000);
//                }
//            });
//        }
//    });

  // $('#batchid').change(function() {
  
//if($('#batchid').val() != ""){
//     // alert(here);
//        $('#unallocated_quantity').val('');
//      if ($(this).val() != "") {
//            //alert($(this).val());
//          //  alert('here');
//            $.ajax({
//                type: "POST",
//                url: appName + "/stock/ajax-get-total-quantity-by-batch",
//                data: {id: $('#batchid').val()},
//                dataType: 'json',
//                success: function(data) {
//                    $('#unallocated_quantity').val(data.unallocated_qty);
//                    $('#total_quantity').val(data.product_qty);
//                }
//            });
//       }
//       }
   
  //  });

   /*$("#add").click(function(e) {
     e.preventDefault();
     var validator = $("#product_location").validate();
     // var aval_qty = $("#available_quantity").val();
     var quantity = $("#quantity").val();
     var un_allocated_quantity = $("#unallocated_quantity").val();
     //aval_qty = aval_qty.replace(",", "");
     //  quantity = quantity.replace(",", "");
     if (parseInt(quantity) <= 0) {
     validator.showErrors({
     "quantity": "Carton quantity should greater then 0"
     });
     }
     else if (parseInt(quantity) > un_allocated_quantity) {
     validator.showErrors({
     "quantity": "Carton quantity should less than un allocated quantity."
     });
     }
     else {
     $("#product_location").submit();
     }
     });*/

    $("#product_location").validate({
        rules: {      
            quantity: {
                required: true,
                remote: {
                    type: "POST",
                    url: appName + '/stock/ajax-check-quantity',
                    data: {
                        batchId: function() {
                           return $("#batchId").val()
                       },
                        quantity: function() {
                            return $("#quantity").val()
                       },
                        item_id: function() {
                            return $.GetUrlParam["itemid"];

                        }
                    }
                }
            }
        },
        messages: {  
            quantity: {
                required: "Enter carton quantity.",
                remote: "Quantity should greater then 0 and less then unallocated quantity"
            }
        }
    });
});