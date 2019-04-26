$(function() {

    // validate signup form on keyup and submit
    $("#add-resource").validate({
        rules: {
            resource_name: {
                required: true,
                remote: appName + "/iadmin/manage-resources/check-resource"
            },
            description: "required",
            resource_type: "required"
        },
        messages: {
            resource_name: {
                required: "Please enter Resource Name",
                remote: jQuery.validator.format("Resource Name is already taken!")
            },
            description: "Please enter description",
            resource_type: "Please select resource type"
        }
    });

    // validate signup form on keyup and submit
    $("#update-resource").validate({
        rules: {
            description: "required",
            resource_type: "required"
        },
        messages: {
            description: "Please enter description",
            resource_type: "Please select resource type"
        }
    });

    $(".update-resource").click(function() {
        $('#modal-body-contents').html("<div style='text-align: center; '><img src='" + appName + "/images/loader.gif' style='margin:30px;'  /></div>");
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-resources/ajax-edit",
            data: {resource_id: $(this).attr('itemid')},
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
                            url: appName + "/iadmin/manage-resources/delete",
                            data: {resource_id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {
                                notyfy({
                                    force: true,
                                    text: 'Resource has been deleted!',
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

        var resource_name = '';
        var resource_type = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        resource_name = $('#resource_name').val();
        resource_type = $('#resource_type').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (resource_name.length > 0 && resource_type.length > 0) {
            document.location = appName + '/iadmin/manage-resources/?order=' + order + '&sort=' + sort + '&resource_name=' + resource_name + '&resource_type=' + resource_type + '&counter=' + counter + '&page=' + page;
        } else if (resource_name.length > 0) {
            document.location = appName + '/iadmin/manage-resources/?order=' + order + '&sort=' + sort + '&resource_name=' + resource_name + '&counter=' + counter + '&page=' + page;
        } else if (resource_type.length > 0) {
            document.location = appName + '/iadmin/manage-resources/?order=' + order + '&sort=' + sort + '&resource_type=' + resource_type + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/iadmin/manage-resources/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
    // GRID Sorting End

    // GRID Counter Start
    $('#records').change(function(e) {
        e.preventDefault();

        var resource_name = '';
        var resource_type = '';

        resource_name = $('#resource_name').val();
        resource_type = $('#resource_type').val();
        counter = $(this).val();
        page = $('#current').val();

        if (resource_name.length > 0 && resource_type.length > 0) {
            document.location = appName + '/iadmin/manage-resources/?resource_name=' + resource_name + '&resource_type=' + resource_type + '&counter=' + counter + '&page=' + page;
        } else if (resource_name.length > 0) {
            document.location = appName + '/iadmin/manage-resources/?resource_name=' + resource_name + '&counter=' + counter + '&page=' + page;
        } else if (resource_type.length > 0) {
            document.location = appName + '/iadmin/manage-resources/?resource_type=' + resource_type + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/iadmin/manage-resources/?counter=' + counter + '&page=' + page;
        }
    });
    // GRID Counter End
});