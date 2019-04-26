$(function() {
    $('#records').change(function() {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-users/?counter=' + counter;
    });

   
    $("#province").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-users/ajax-get-district",
            data: {province_id: $(this).val()},
            dataType: 'html',
            success: function(data) {
           $('#district').html(data);
            }

        });
  });
  
   $("#district").change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-users/ajax-get-users",
            data: {district_id: $(this).val()},
            dataType: 'html',
            success: function(data) {
           $('#user').html(data);
            }

        });
  });
  
  $('#user').change(function(e) {
		$('#wh').html('');
        if ($('#user').val() != '')
		{
                    Metronic.startPageLoading('Please wait...');
                    
			$.ajax({
			        url: appName + "/iadmin/manage-users/ajax-get-warehouses",
				data: 'userId='+$('#user').val()+'&district='+$('#district').val(),
				type: 'POST',
				success: function(data){
                                        $('#wh').html(data);
					
                                         Metronic.stopPageLoading();
                                         $("#submit").css("display","block");
				}
			})
		}
    });

    $('[data-toggle="notyfy"]').click(function()
    {
        $.notyfy.closeAll();
        var self = $(this);

        notyfy({
            text: 'Do you want to continue?',
            type: self.data('type'),
            dismissQueue: true,
            layout: self.data('layout'),
            buttons: (self.data('type') != 'confirm') ? false : [{
                    addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                    text: '<i></i> Ok',
                    onClick: function($notyfy) {
                        $notyfy.close();
                        $.ajax({
                            type: "POST",
                            url: appName + "/cadmin/manage-makes/delete",
                            data: {make_id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {
                                notyfy({
                                    force: true,
                                    text: 'Make has been deleted!',
                                    type: 'success',
                                    layout: self.data('layout')
                                });
                                self.closest("tr").remove();
                            }
                        });
                    }
                }, {
                    addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                    text: '<i></i> Cancel',
                    onClick: function($notyfy) {
                        $notyfy.close();
//                        notyfy({
//                            force: true,
//                            text: '<strong>You clicked "Cancel" button<strong>',
//                            type: 'error',
//                            layout: self.data('layout')
//                        });
                    }
                }]
        });
        return false;
    });

    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function(e) {
        e.preventDefault();

        var self = $(this);

        var make_name = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        make_name = $('#name').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (make_name.length > 1) {
            document.location = appName + '/cadmin/manage-makes/?order=' + order + '&sort=' + sort + '&name=' + make_name + '&counter=' + counter + '&page=' + page;
        }
        else {
            document.location = appName + '/cadmin/manage-makes/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
});