$(function() {
    $('#reset').click(function () {
    window.location.href = appName + '/iadmin/manage-products/item-categories';
});
    
    $('#records').change(function() {
        var counter = $(this).val();
        document.location.href = appName + '/iadmin/manage-products/item-categories/?counter=' + counter;
    });

    $("#add-stakeholder").validate({
        rules: {
            item_category_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-products/check-item-category",
                    type: "post",
                    data: {
                        item_category_name_hidden: function () {
                            return '';
                        }
                    }
                }
            }
        },
        messages: {
            item_category_name: {
                required: "Please enter category name.",
                remote: "Category already exists."
            }
        }
    });


    // validate signup form on keyup and submit
    $("#update-asset-sub-types").validate({
        rules: {
            item_category_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-products/check-item-category",
                    type: "post",
                    data: {
                        item_category_name_hidden: function () {
                            return $("#item_category_name_hidden").val();
                        }
                    }
                }
            }
        },
        messages: {
            item_category_name: {
                required: "Please enter category name.",
                remote: "Category already exists."
            }
        }
    });

    $(".update-asset-sub-type").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-products/ajax-item-categories-edit",
            data: {item_category_id: $(this).attr('id')},
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
                    /*    notyfy({
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