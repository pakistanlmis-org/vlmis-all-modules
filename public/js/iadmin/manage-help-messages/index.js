$(function() {
    // validate signup form on keyup and submit
    $("#add-narrative").validate({
        rules: {
            page_name: {
                required: true,
            },
            description: "required"
        },
        messages: {
            page_name: {
                required: "Please select page name",
            },
            description: "Please enter description"
        }
    });

    // validate signup form on keyup and submit
    $("#update-narrative").validate({
        rules: {
            page_name: {
                required: true,
            },
            description: "required"
        },
        messages: {
            page_name: {
                required: "Please select page name",
            },
            description: "Please enter description"
        }
    });

    $(".update-narrative").click(function() {
        $('#modal-body-contents').html("<div style='text-align: center; '><img src='" + appName + "/images/loader.gif' style='margin:30px;'  /></div>");
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-help-messages/ajax-edit",
            data: {id: $(this).attr('pkid')},
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
                            url: appName + "/iadmin/manage-help-messages/delete",
                            data: {id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {
                                notyfy({
                                    force: true,
                                    text: 'Message has been deleted!',
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

    // GRID Sorting Start
    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function(e) {
        e.preventDefault();

        var self = $(this);

        var sr_no = '';
        var title = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        sr_no = $('#sr_no').val();
        title = $('#title').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (sr_no.length > 0 && title.length > 0) {
            document.location = appName + '/iadmin/manage-help-messages/?order=' + order + '&sort=' + sort + '&sr_no=' + sr_no + '&title=' + title + '&counter=' + counter + '&page=' + page;
        } else if (sr_no.length > 0) {
            document.location = appName + '/iadmin/manage-help-messages/?order=' + order + '&sort=' + sort + '&sr_no=' + sr_no + '&counter=' + counter + '&page=' + page;
        } else if (title.length > 0) {
            document.location = appName + '/iadmin/manage-help-messages/?order=' + order + '&sort=' + sort + '&title=' + title + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/iadmin/manage-help-messages/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
    // GRID Sorting End

    // GRID Counter Start
    $('#records').change(function(e) {
        e.preventDefault();

        var search_text = '';

        search_text = $('#search_text').val();
        counter = $(this).val();
        page = $('#current').val();

        if (search_text.length > 0) {
            document.location = appName + '/iadmin/manage-help-messages/?search_text=' + search_text + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/iadmin/manage-help-messages/?counter=' + counter + '&page=' + page;
        }
    });
    // GRID Counter End
});