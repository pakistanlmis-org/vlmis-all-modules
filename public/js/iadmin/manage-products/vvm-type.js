$(function() {
    $('#reset').click(function () {
    window.location.href = appName + '/iadmin/manage-products/vvm-type';
});
    $('#records').change(function() {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-products/vvm-type?counter=' + counter;
    });

    // validate signup form on keyup and submit
    $("#update-vvm-types").validate({
        rules: {
            vvm_type_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-products/check-vvm-types",
                    type: "post",
                    data: {
                        vvm_type_name_hidden: function () {
                            return $("#vvm_type_name_hidden").val();
                        }
                    }
                }
            },
//            item_pack_size_id: "required"
        },
        messages: {
            vvm_type_name: {
                required: "Please enter VVM type name.",
                remote: "VVM type already exists."
            },
//            item_pack_size_id: "Select Item."
        }
    });

    // validate signup form on keyup and submit
    $("#add-vvm-types").validate({
        rules: {
            vvm_type_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-products/check-vvm-types",
                    type: "post",
                    data: {
                        vvm_type_name_hidden: function () {
                            return '';
                        }
                    }
                }
            },
            item_pack_size_id: "required"

        },
        messages: {
            vvm_type_name: {
                required: "Please enter VVM type name.",
                remote: "VVM type already exists."
            },
            item_pack_size_id: "Select Item."
        }
    });

    $(".update-vvm-type").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-products/ajax-edit",
            data: {vvm_type_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

    $(document).on("click", "a.active", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-products/ajax-change-status",
            data: {id: id, ajaxaction: 'active'},
            dataType: 'html',
            success: function(data) {
                $('#' + id).html('Deactivate');
                $('#'+ id + '-status').html('Active');
                $('#' + id).removeClass("active");
                $('#' + id).addClass("deactive");
            }
        });
    });

    $(document).on("click", "a.deactive", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-products/ajax-change-status",
            data: {id: id, ajaxaction: 'deactive'},
            dataType: 'html',
            success: function(data) {
                $('#' + id).html('Activate');
                $('#'+ id + '-status').html('Inactive');
                $('#' + id).removeClass("deactive");
                $('#' + id).addClass("active");
            }
        });
    });


    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function(e) {
        e.preventDefault();

        var self = $(this);

        var vvm_type_name = '';
        var order = '';
        var sort = '';
        var counter = '';
        var page = '';

        order = self.data('order');
        sort = self.data('sort');

        vvm_type_name = $('#vvm_type_name').val();
        counter = $('#records').val();
        page = $('#current').val();

        if (make_name.length > 1) {
            document.location = appName + '/iadmin/manage-products/vvm-type/?order=' + order + '&sort=' + sort + '&vvm_type_name=' + vvm_type_name + '&counter=' + counter + '&page=' + page;
        }
        else {
            document.location = appName + '/iadmin/manage-products/vvm-type/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
});