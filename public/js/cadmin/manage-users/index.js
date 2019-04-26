$(function() {

    // validate signup form on keyup and submit
    $("#add-user").validate({
        rules: {
            login_id: {
                required: true,
                remote: appName + "/cadmin/manage-users/check-user"
            },
            role: "required",
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            },
            phone: "required",
            office: "required",
            combo1: "required",
            warehouse: "required"
        },
        messages: {
            login_id: {
                required: "Please enter username",
                remote: jQuery.validator.format("Username is already taken!")
            },
            role: "Please select user role",
            email: {
                required: "Please enter email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please enter password"
            },
            confirm_password: {
                required: "Please enter confirm password",
                equalTo: "Confirm password should be equal to password"
            },
            phone: "Please enter phone",
            office: "Please select office",
            combo1: "Please select province",
            warehouse: "Please select store"
        }
    });

    // validate signup form on keyup and submit
    $("#update-user").validate({
        rules: {
            role: "required",
            phone: "required"
        },
        messages: {
            role: "Please select user role",
            phone: "Please enter phone"
        }
    });

    $(".update-user").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-users/ajax-edit",
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
                            url: appName + "/cadmin/manage-users/delete",
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
                     /*   notyfy({
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

        var login_id = '';
        var role = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        login_id = $('#login_id').val();
        role = $('#role').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (login_id.length > 0 && role.length > 0) {
            document.location = appName + '/cadmin/manage-users/?order=' + order + '&sort=' + sort + '&login_id=' + login_id + '&role=' + role + '&counter=' + counter + '&page=' + page;
        } else if (login_id.length > 0) {
            document.location = appName + '/cadmin/manage-users/?order=' + order + '&sort=' + sort + '&login_id=' + login_id + '&counter=' + counter + '&page=' + page;
        } else if (role.length > 0) {
            document.location = appName + '/cadmin/manage-users/?order=' + order + '&sort=' + sort + '&role=' + role + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/cadmin/manage-users/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
    // GRID Sorting End

    // GRID Counter Start
    $('#records').change(function(e) {
        e.preventDefault();

        var login_id = '';
        var role = '';

        login_id = $('#login_id').val();
        role = $('#role').val();
        counter = $(this).val();
        page = $('#current').val();

        if (login_id.length > 0 && role.length > 0) {
            document.location = appName + '/cadmin/manage-users/?login_id=' + login_id + '&role=' + role + '&counter=' + counter + '&page=' + page;
        } else if (login_id.length > 0) {
            document.location = appName + '/cadmin/manage-users/?login_id=' + login_id + '&counter=' + counter + '&page=' + page;
        } else if (role.length > 0) {
            document.location = appName + '/cadmin/manage-users/?role=' + role + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/cadmin/manage-users/?counter=' + counter + '&page=' + page;
        }
    });
    // GRID Counter End
});