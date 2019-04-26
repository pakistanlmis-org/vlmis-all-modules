$(function () {
    $('#stock_master_id').select2();
    $('#stock_master_id').change(function () {
        // if ($(this).val() != "") {
        //alert("here");
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-detail-data-issueno",
            data: {stock_master_id: $(this).val()},
            dataType: 'html',
            success: function (data) {
                //alert(data);
                $('#ajax_tbl_data').html(data);
            }
        });
        // }
    });
//         $('#pick').click(function() {
//          alert("here");
//        varform = $('#stockpickdata').serialize();
//        alert(varform);
//      
//            $.ajax({
//                type: "POST",  
//                url: appName + "/stock/pick-stock-quantity?"+varform,
//                data: {},
//                dataType: 'html',
//                success: function(data) {
//                   $('#success').show();       
//                }
//            });
//        
//    });
});
