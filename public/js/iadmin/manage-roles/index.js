$(function() {

    // validate signup form on keyup and submit
    $("#add-role").validate({
        rules: {
            role_name: {
                required: true,
                remote: appName + "/iadmin/manage-roles/check-role"
            },
            description: "required",
            category_id: "required",
            status: "required"
        },
        messages: {
            role_name: {
                required: "Please enter Role Name",
                remote: jQuery.validator.format("Role Name is already taken!")
            },
            description: "Please enter description",
            category_id: "Please select category_id",
            status: "Please select status"
        }
    });

    // validate signup form on keyup and submit
    $("#update-role").validate({
        rules: {
            description: "required",
            category_id: "required"
        },
        messages: {
            description: "Please enter description",
            category_id: "Please select category"
        }
    });

    $(".update-role").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-roles/ajax-edit",
            data: {role_id: $(this).attr('itemid')},
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
                            url: appName + "/iadmin/manage-roles/delete",
                            data: {role_id: self.data('bind')},
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
                    /*    notyfy({
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

    // GRID Sorting Start
    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function(e) {
        e.preventDefault();

        var self = $(this);

        var role_name = '';
        var description = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        role_name = $('#role_name').val();
        description = $('#description').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (role_name.length > 0 && description.length > 0) {
            document.location = appName + '/iadmin/manage-roles/?order=' + order + '&sort=' + sort + '&role_name=' + role_name + '&description=' + description + '&counter=' + counter + '&page=' + page;
        } else if (role_name.length > 0) {
            document.location = appName + '/iadmin/manage-roles/?order=' + order + '&sort=' + sort + '&role_name=' + role_name + '&counter=' + counter + '&page=' + page;
        } else if (description.length > 0) {
            document.location = appName + '/iadmin/manage-roles/?order=' + order + '&sort=' + sort + '&description=' + description + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/iadmin/manage-roles/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
    // GRID Sorting End

    // GRID Counter Start
    $('#records').change(function(e) {
        e.preventDefault();

        var login_id = '';
        var role = '';

        role_name = $('#role_name').val();
        description = $('#description').val();
        counter = $(this).val();
        page = $('#current').val();

        if (role_name.length > 0 && description.length > 0) {
            document.location = appName + '/iadmin/manage-roles/?role_name=' + role_name + '&description=' + description + '&counter=' + counter + '&page=' + page;
        } else if (role_name.length > 0) {
            document.location = appName + '/iadmin/manage-roles/?role_name=' + role_name + '&counter=' + counter + '&page=' + page;
        } else if (description.length > 0) {
            document.location = appName + '/iadmin/manage-roles/?description=' + description + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/iadmin/manage-roles/?counter=' + counter + '&page=' + page;
        }
    });
    // GRID Counter End
});