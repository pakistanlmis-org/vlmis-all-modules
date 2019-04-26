$(function() {
    $('#reset').click(function () {
    window.location.href = appName + '/iadmin/manage-products/products';
});
    
    $('#records').change(function() {
        var counter = $(this).val();
        document.location.href = appName + '/iadmin/manage-products/products/?counter=' + counter;
    });
    $('#lbl_no_of_doses').hide()
    $('#number_of_doses').hide();
    $('#add-stakeholder #item_category').change(function() {
        if ($(this).val() == 1) {
            $('#add-stakeholder #lbl_no_of_doses').show()
            $('#add-stakeholder #number_of_doses').show();
        }
        else {
            $('#add-stakeholder #lbl_no_of_doses').hide()
            $('#add-stakeholder #number_of_doses').hide();
        }
    });

    $("#add-stakeholder").validate({
        rules: {
            item_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-products/check-product",
                    type: "post",
                    data: {
                        item_category: function() {
                            return $("#add-stakeholder #item_category").val();
                        },
                        item_unit: function() {
                            return $("#add-stakeholder #item_unit").val();
                        },
                        item: function() {
                            return $("#add-stakeholder #item").val();
                        },
                        item_name_hidden: function() {
                            return '';
                        }
                    }
                }
            },
            list_rank: "required",
            item_category: "required",
            item_unit: "required",
            item: "required",
            description: "required",
            number_of_doses: "required",
            percent_population_covered:
                    {
                        required: true,
                        range: [0, 100],
                        number: true
                    }
        },
        messages: {
            item_name: {
                required: "Please enter product name.",
                remote: "Product already exists."
            }
        }
    });

    
    // validate signup form on keyup and submit
    $("#update-asset-sub-types").validate({
        rules: {
//            item_name: 'required',
            item_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-products/check-product",
                    type: "post",
                    data: {
                        item_name_hidden: function() {
                            return $("#item_name_hidden").val();
                        }
                    }
                }
            },
            list_rank: "required",
            item_category: "required",
            item_unit: "required",
            item: "required",
            description: "required",
            number_of_doses: "required",
            
        },
        messages: {
            item_name: {
                required: "Please enter product name.",
                remote: "Product already exists."
            }
        }
    });

    $(".update-asset-sub-type").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-products/ajax-product-edit",
            data: {item_id: $(this).attr('id')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

    $(".active").click(function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-asset-sub-types/ajax-change-status",
            data: {id: id, ajaxaction: 'deactive'},
            dataType: 'html',
            success: function(data) {
                $('#' + id).html(data);
                $('#' + id).removeClass("active");
                $('#' + id).addClass("deactive");
            }
        });
    });

    $(".deactivate").click(function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-asset-sub-types/ajax-change-status",
            data: {id: id, ajaxaction: 'active'},
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