$(function() {
    $('#records').change(function() {
        var counter = $(this).val();
        document.location.href = appName + '/cadmin/manage-asset-sub-types/?counter=' + counter;
    });

    $("#add-asset-sub-types").validate({
        rules: {
            asset_type: "required",
            asset_sub_type: "required"
        },
        messages: {
            asset_type: "Please select asset type",
            asset_sub_type: "Please enter asset sub type"
        }
    });


    // validate signup form on keyup and submit
    $("#update-asset-sub-types").validate({
        rules: {
            asset_type: "required",
            asset_sub_type: "required"
        },
        messages: {
            asset_type: "Please select asset type",
            asset_sub_type: "Please enter asset sub type"
        }
    });

    $(".update-asset-sub-type").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/cadmin/manage-asset-sub-types/ajax-edit",
            data: {asset_id: $(this).attr('assetid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

     $( document ).on( "click", "a.active", function() {
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

     $( document ).on( "click", "a.deactivate", function() {
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