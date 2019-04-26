$(function()
{
    $('#reset').click(function () {
    window.location.href = appName + '/iadmin/manage-geo-colors/geo-colors';
});

   $('#records').change(function()
   {
      var counter = $(this).val();
      document.location.href = appName + '/iadmin/manage-periods/periods/?counter=' + counter;
   });
   
   $('#add-colorCode').validate(
           {
               rules: {
                   color_code_name: {
                       required: true,
                       remote: {
                           url: appName + "/iadmin/manage-geo-colors/check-geo-colors",
                           type: "post",
                           data:
                            {
                                color_code_name: function(){
                                    return $("#add-colorCode #color_code_name").val();
                                }
                            }
                       }
                   }
               },
               messages: {
                   color_code_name: {
                       required: "Please enter color code value",
                       remote: "Color Code Already Exists"
                   }
               }
           });
           
           $("#update-asset-sub-types").validate({
               rules: {
                   color_code_name: {
                       required: true,
                       remote: {
                           url: appName + "/iadmin/manage-geo-colors/check-geo-colors",
                           type: "post",
                           data: {
                               color_code_name: function ()
                               {
                                   return $("#update-asset-sub-types #color_code_name").val();
                               }
                           }
                       }
                   }
               },
               messages: {
                   color_code_name: {
                       required: "Please enter color code value",
                       remote: "Color Code already exists"
                   }
               }
           });
           
           $(".update-asset-sub-type").click(function () {
               $.ajax({
                   type: "POST",
                   url: appName + "/iadmin/manage-geo-colors/ajax-geo-color-edit",
                   data: {color_code_id: $(this).attr('id')},
                   dataType: 'html',
                   success: function(data)
                   {
                       $('#modal-body-contents').html(data);
                       $('#update-button').show();
                   }
               });
           });
           
           $('th.sorting, th.sorting_asc, th.sorting_desc').click(function(e) {
                e.preventDefault();

                var self = $(this);

                var asset_sub_type = '';
                var order = '';
                var sort = '';
                var counter = '';
                var page = '';

                order = self.data('order');
                sort = self.data('sort');

                asset_sub_type = $('#name').val();
                counter = $('#records').val();
                page = $('#current').val();

                if (asset_sub_type.length > 1) {
                    document.location = appName + '/cadmin/manage-asset-sub-types/?order=' + order + '&sort=' + sort + '&name=' + asset_sub_type + '&counter=' + counter + '&page=' + page;
                }
                else {
                    document.location = appName + '/cadmin/manage-asset-sub-types/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
                }
    });
});