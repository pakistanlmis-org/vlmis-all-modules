$(function () {//
    $(".transfer-stock-vaccines").click(function () {
        $.ajax({
            type: "POST",
            url: appName + "/stock/transfer-stock-vaccines",
            data: {placement_id: $(this).attr('editid')}, //asset name
            //data: {barcode_type_id: bar_id, number: $('#item_pack_size_id').val()},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);
                $('#transfer-stock-vaccines').show();
            }
        });
    });
    
    $("a[id$='-batchdetail']").click(function () {
        $('#batchdetailbody').html('');
        var batch_id = $(this).data("id");

        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-batch-detail-body",
            data: {id: batch_id},
            dataType: 'html',
            success: function (data) {
                $('#batchdetailbody').html(data);
            }
        });
    });

    // GRID Sorting Start
    $('th.sorting, th.sorting_asc, th.sorting_desc').click(function (e) {
        e.preventDefault();

        var self = $(this);

        var order = '';
        var sort = '';
        var counter = '';
        var page = '';
        var id = $("#bin_id").val();

        order = self.data('order');
        sort = self.data('sort');

        counter = $('#records').val();
        page = $('#current').val();

        document.location = appName + '/stock/stock-in-bin-vaccines/?order=' + order + '&sort=' + sort + '&counter=' + counter + '&page=' + page + '&id=' + id;
    });
    // GRID Sorting End

    // GRID Counter Start
    $('#records').change(function (e) {
        e.preventDefault();

        var counter = $(this).val();
        var page = $('#current').val();
        var id = $("#bin_id").val();
        var asset_name = $("#asset_name").val();

        document.location = appName + '/stock/stock-in-bin-vaccines/?counter=' + counter + '&page=' + page + '&id=' + id+'&asset='+asset_name;
    });
    // GRID Counter End

    $('[data-toggle="notyfy"]').click(function () {
        $.notyfy.closeAll();
        var self = $(this);

        notyfy({
            text: notification[self.data('type')],
            type: self.data('type'),
            dismissQueue: true,
            layout: self.data('layout'),
            buttons: (self.data('type') != 'confirm') ? false : [
                {
                    addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                    text: '<i></i> Ok',
                    onClick: function ($notyfy) {
                        Metronic.startPageLoading('Please wait...');
                        var id = self.attr("id");
                        $notyfy.close();
                        $.ajax({
                            type: "POST",
                            url: appName + "/stock/delete-stock-placement",
                            data: {p: 'stock', placement_id: id},
                            success: function (data) {
                                Metronic.stopPageLoading();
                                if (data == 1) {
                                    self.closest("tr").remove();
                                    notyfy({
                                        force: true,
                                        text: '<strong>Deleted successfully!<strong>',
                                        type: 'success',
                                        layout: self.data('layout')
                                    });
                                } else {
                                    notyfy({
                                        force: true,
                                        text: '<strong>An error occur! Try later.<strong>',
                                        type: 'error',
                                        layout: self.data('layout')
                                    });
                                }
                            }
                        });

                        //window.location.href = appName + '/stock/delete-issue?p=stock&id=' + id;
                    }
                },
                {
                    addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                    text: '<i></i> Cancel',
                    onClick: function ($notyfy) {
                        $notyfy.close();
                        /*   notyfy({
                         force: true,
                         text: '<strong>You clicked "Cancel" button<strong>',
                         type: 'error',
                         layout: self.data('layout')
                         });*/
                    }
                }
            ]
        });
        return false;
    });


    var notification = [];
    notification['confirm'] = 'Do you want to continue?';

});