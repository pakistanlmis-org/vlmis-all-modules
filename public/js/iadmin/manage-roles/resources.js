$(function() {

    $("#resource").select2();
    // validate signup form on keyup and submit
    $("#add-resource").validate({
        rules: {
            resource: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-roles/check-role-resource",
                    type: "post",
                    data: {
                        resource_id: function() {
                            return $("#resource").val();
                        },
                        role_id: function() {
                            return $("#role").val();
                        }
                    }
                }
            }
        },
        messages: {
            resource: {
                required: "Please select Resource Name",
                remote: jQuery.validator.format("This resource is already assigned to this Role!")
            }
        }
    });

    $('[data-toggle="notyfy"]').click(function() {
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
                            url: appName + "/iadmin/manage-roles/delete-role-resource",
                            data: {rr_id: self.data('bind')},
                            dataType: 'html',
                            success: function(data) {
                                notyfy({
                                    force: true,
                                    text: 'Role resource has been deleted!',
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

    // GRID Sorting Start
    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function(e) {
        e.preventDefault();

        var self = $(this);

        var role = '';
        var description = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        role = $('#role').val();
        description = $('#description').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (role.length > 0 && description.length > 0) {
            document.location = appName + '/iadmin/manage-roles/resources/?order=' + order + '&sort=' + sort + '&role=' + role + '&description=' + description + '&counter=' + counter + '&page=' + page;
        } else if (role.length > 0) {
            document.location = appName + '/iadmin/manage-roles/resources/?order=' + order + '&sort=' + sort + '&role=' + role + '&counter=' + counter + '&page=' + page;
        } else if (description.length > 0) {
            document.location = appName + '/iadmin/manage-roles/resources/?order=' + order + '&sort=' + sort + '&description=' + description + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/iadmin/manage-roles/resources/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
    // GRID Sorting End

    // GRID Counter Start
    $('#records').change(function(e) {
        e.preventDefault();

        var role = '';
        var description = '';

        role = $('#role').val();
        description = $('#description').val();
        counter = $(this).val();
        page = $('#current').val();

        if (role.length > 0 && description.length > 0) {
            document.location = appName + '/iadmin/manage-roles/resources/?role=' + role + '&description=' + description + '&counter=' + counter + '&page=' + page;
        } else if (role.length > 0) {
            document.location = appName + '/iadmin/manage-roles/resources/?role=' + role + '&counter=' + counter + '&page=' + page;
        } else if (description.length > 0) {
            document.location = appName + '/iadmin/manage-roles/resources/?description=' + description + '&counter=' + counter + '&page=' + page;
        } else {
            document.location = appName + '/iadmin/manage-roles/resources/?counter=' + counter + '&page=' + page;
        }
    });
    // GRID Counter End
});