$(function() {
     $('#stock_master_id').change(function() {
       // if ($(this).val() != "") {
       //alert("here");
            $.ajax({
                type: "POST",  
                url: appName + "/stock/ajax-vaccines-detail-data-issueno",
                data: {stock_master_id: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    //alert(data);
                    $('#ajax_tbl_data').html(data);       
                }
            });
       // }
    });
});
