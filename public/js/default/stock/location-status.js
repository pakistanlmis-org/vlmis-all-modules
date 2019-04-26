$(function() {
    $("#location_status").validate({
        rules: {
            area: {
                required: true
            },
            level:{
                required: true
            }
        },
        messages: {
            area: {
                required: "Please select store."
            },
            level: {
                required: "Please select row."
            }
        }
    });

    $(".product-location").click(function() {

        var self = $(this);
        var itemid = self.attr('itemid');
        //alert('itemid=='+itemid);

        $("#iframeurl").attr('src', appName + '/stock/product-location/?id=' + itemid);

        /*$.ajax({
         type: "POST",
         url: appName + "/stock/product-location",
         data: {pl_id: $(this).attr('id')},
         dataType: 'html',
         success: function(data) {
         $('#modal-body-contents').html(data);
         $('#update-button').show();
         }
         });*/
    });
});