$(function() {
    $('#records').change(function() {
        var counter = $(this).val();

        document.location.href = appName + '/cadmin/manage-ccm-status-lists/?counter=' + counter;
    });

    // validate signup form on keyup and submit
    $("#update-status-lists").validate({
        rules: {
            ccm_status_list_name: "required",
            type: "required"
        },
        messages: {
            ccm_status_list_name: "Please enter status list name",
            type: "Please enter status list type"
        }
    });

 // validate signup form on keyup and submit
    $("#add-status-lists").validate({
        rules: {
            ccm_status_list_name: "required",
            type: "required"
        },
        messages: {
            ccm_status_list_name: "Please enter status list name",
            type: "Please enter status list type"
        }
    });
    

    $(".update-status-list").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-ccm-status-lists/ajax-edit",
            data: {status_list_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });
    
    
    
    $( document ).on( "click", "a.active", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-ccm-status-lists/ajax-change-status",
            data: {id: id, ajaxaction: 'active'},
            dataType: 'html',
            success: function(data) {
                $('#' + id).html(data);
                $('#' + id).removeClass("active");
                $('#' + id).addClass("deactive");
            }
        });
    });

     $( document ).on( "click", "a.deactivate", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-ccm-status-lists/ajax-change-status",
            data: {id: id, ajaxaction: 'deactive'},
            dataType: 'html',
            success: function(data) {
                $('#' + id).html(data);
                $('#' + id).removeClass("deactive");
                $('#' + id).addClass("active");
            }
        });
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
                            url: appName + "/cadmin/manage-ccm-status-lists/delete",
                            data: {status_list_id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {
                                notyfy({
                                    force: true,
                                    text: 'Status list has been deleted!',
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
                      /*  notyfy({
                            force: true,
                            text: '<strong>You clicked "Cancel" button<strong>',
                            type: 'error',
                            layout: self.data('layout')
                        });*/
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

        ccm_status_list_name = $('#ccm_status_list_name').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (make_name.length > 1) {
            document.location = appName + '/cadmin/manage-ccm-status-lists/?order=' + order + '&sort=' + sort + '&ccm_status_list_name=' + ccm_status_list_name + '&counter=' + counter + '&page=' + page;
        }
        else {
            document.location = appName + '/cadmin/manage-ccm-status-lists/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
});