/**
 * Created by JSI on 5/21/14.
 */


$("#add-list").validate({
    rules: {
        list_master: "required",
        list_value: "required"
    },
    messages: {
        list_master: "Required",
        list_value: "Required"
    }
});


$("#update-list").validate({
    rules: {
        list_master: "required",
        list_value: "required"
    },
    messages: {
        list_master: "Required",
        list_value: "Required"
    }
});

$(".update-list").click(function() {

    $.ajax({
        type: "POST",
        url: appName + "/cadmin/manage-lists/ajax-edit",
        data: {user_id: $(this).attr('itemid')},
        dataType: 'html',
        success: function(data) {
            $('#modal-body-contents').html(data);
            $('#update-button').show();
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
                        url: appName + "/cadmin/manage-lists/delete",
                        data: {user_id: self.data('bind')},
                        dataType: 'html',
                        success: function(data) {

                            notyfy({
                                force: true,
                                text: 'User has been deleted!',
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
                    }); */
                }
            }]
    });
    return false;
});

$(function() {
    // GRID Sorting Start
    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function(e) {
        e.preventDefault();

        var self = $(this);

        var list_master = '';
        var list_value = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        list_master = $('#list_master').val();
        list_value = $('#list_value').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (list_master.length > 0 && list_value.length > 0) {
            document.location = appName + '/cadmin/manage-lists/?order=' + order + '&sort=' + sort + '&list_value=' + list_value + '&list_master=' + list_master + '&counter=' + counter + '&page=' + page;
        } else if (list_master.length > 0) {
            document.location = appName + '/cadmin/manage-lists/?order=' + order + '&sort=' + sort + '&list_master=' + list_master + '&counter=' + counter + '&page=' + page;
        } else if (list_value.length > 0) {
            document.location = appName + '/cadmin/manage-lists/?order=' + order + '&sort=' + sort + '&list_value=' + list_value + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/cadmin/manage-lists/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
    // GRID Sorting End

    // GRID Counter Start
    $('#records').change(function(e) {
        e.preventDefault();

        var list_master = '';
        var role = '';

        list_master = $('#list_master').val();
        list_value = $('#list_value').val();
        counter = $(this).val();
        page = $('#current').val();

        if (list_master.length > 0 && list_value.length > 0) {
            document.location = appName + '/cadmin/manage-lists/?list_master=' + list_master + '&list_value=' + list_value + '&counter=' + counter + '&page=' + page;
        } else if (list_master.length > 0) {
            document.location = appName + '/cadmin/manage-lists/?list_master=' + list_master + '&counter=' + counter + '&page=' + page;
        } else if (list_value.length > 0) {
            document.location = appName + '/cadmin/manage-lists/?list_value=' + list_value + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/cadmin/manage-lists/?counter=' + counter + '&page=' + page;
        }
    });
    // GRID Counter End
});