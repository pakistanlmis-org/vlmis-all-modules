$(function () {
    $('#records').change(function () {
        var counter = $(this).val();

        document.location.href = appName + '/iadmin/manage-products/vvm-groups?counter=' + counter;
    });
    
    
    

    // validate signup form on keyup and submit
    $("#update-vvm-groups").validate({
        rules: {
            vvm_type_name: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-products/check-vvm-groups",
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
                required: "Please enter VVM Group Id.",
                remote: "VVM Group Id already exists."
            },
//            item_pack_size_id: "Select Item."
        }
    });

    // validate signup form on keyup and submit
    $("#add-vvm-groups").validate({
        rules: {
            vvm_group_id: {
                required: true,
                remote: {
                    url: appName + "/iadmin/manage-products/check-vvm-groups",
                    type: "post",
                    data: {
                        vvm_group_id_hidden: function () {
                            return '';
                        }
                    }
                }
            }
        },
        messages: {
            vvm_group_id: {
                required: "Please enter VVM Group Id.",
                remote: "VVM Group Id already exists."
            }
        }
    });

    $(".update-vvm-group").click(function () {
        
        var stage = $(this).attr('stages').split(",");
        
        $.ajax({
            type: "POST",
            url: appName + "/iadmin/manage-products/ajax-edit-vvm-group",
            data: {
                vvm_group_id: $(this).attr('itemid'),
            },
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
                if ($.inArray('0', stage) !== -1) {
                    $('#chkbox0').prop('checked', true);
                }
                if ($.inArray('1', stage) !== -1) {
                    $('#chkbox1').prop('checked', true);
                }
                if ($.inArray('2', stage) !== -1) {
                    $('#chkbox2').prop('checked', true);
                }
                if ($.inArray('3', stage) !== -1) {
                    $('#chkbox3').prop('checked', true);
                }
                if ($.inArray('4', stage) !== -1) {
                    $('#chkbox4').prop('checked', true);
                }
            }
        });
    });


    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function (e) {
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
        } else {
            document.location = appName + '/iadmin/manage-products/vvm-type/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page;
        }
    });
    
    $('#save').click(function(e) {
        var btn = $(this);
        btn.button('loading');
        setTimeout(function() {
            btn.button('reset');
        }, 1000);
        
        e.preventDefault();

        if ($('#div_stages').find('input[type=checkbox]:checked').length == 0) {
            alert('Please select atleast one checkbox');
        } else{
            $('#add-vvm-groups').submit();
        }
    });
    
    $('#update').click(function(e) {
        var btn = $(this);
        btn.button('loading');
        setTimeout(function() {
            btn.button('reset');
        }, 1000);
        
        e.preventDefault();

        if ($('#div_stages1').find('input[type=checkbox]:checked').length == 0) {
            alert('Please select atleast one checkbox');
        } else{
            $('#update-vvm-groups').submit();
        }
    });
});
