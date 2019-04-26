var flag = 0;
$("#batches").validate({
    rules: {
        product: {
            required: true
        },
        office: {
            required: true
        },
        combo1: {
            required: true
        },
        combo2: {
            required: true
        },
        warehouse: {
            required: true
        }
    },
    messages: {
        product: {
            required: "Please select product"
        },
        office: {
            required: "Please select office"
        },
        combo1: {
            required: "Please select Province"
        },
        combo2: {
            required: "Please select District"
        },
        warehouse: {
            required: "Please select Warehouse"
        }
    }
});

$('#submit').click(function (e) {
    if ($("#batches").valid()) {
        e.preventDefault();
        Metronic.startPageLoading('Please wait...');
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-merge-batch",
            data: {product: $("#product").val(), warehouse: $("#warehouse").val()},
            dataType: 'html',
            success: function (data) {
                $('#merge_batch').html(data);
                Metronic.stopPageLoading();

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
                                        url: appName + "/stock/ajax-update-batch-master",
                                        data: {id: id},
                                        success: function (data) {
                                            self.closest("td").html(data);
                                            Metronic.stopPageLoading();
                                            notyfy({
                                                force: true,
                                                text: '<strong>Updated successfully!<strong>',
                                                type: 'success',
                                                layout: self.data('layout')
                                            });
                                        }
                                    });
                                }
                            },
                            {
                                addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                                text: '<i></i> Cancel',
                                onClick: function ($notyfy) {
                                    $notyfy.close();
                                }
                            }
                        ]
                    });
                    return false;
                });

                var notification = [];
                notification['confirm'] = 'Do you want to continue?';

                $('#merge').click(function () {
                    $.notyfy.closeAll();
                    var selected = '';
                    $('.mergecheck:checked').each(function () {
                        selected = selected + $(this).val() + '|';
                    });

                    var mergeinto = $(".mergeinto:checked").val();

                    if (!$(".mergeinto:checked").length) {
                        alert("Please select merge into batch");
                        return false;
                    }

                    if ($('.mergecheck:checked').length < 2) {
                        alert("You must select 2 batches to merge");
                        return false;
                    }

                    $.ajax({
                        type: "POST",
                        url: appName + "/stock/ajax-validate-merge-batches",
                        data: {data: selected, merge: mergeinto},
                        dataType: 'html',
                        success: function (data) {
                            if (data >= 1) {
                                notyfy({
                                    force: true,
                                    text: '<strong>There are ' + data + ' batch number(s) is not similar. Do you want to continue?<strong>',
                                    type: 'error',
                                    layout: 'top',
                                    buttons: [
                                        {
                                            addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                                            text: '<i></i> Continue?',
                                            onClick: function ($notyfy) {
                                                $notyfy.close();
                                                mergeBatchQty(selected, mergeinto);
                                            }
                                        },
                                        {
                                            addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                                            text: '<i></i> Cancel',
                                            killer: true,
                                            onClick: function ($notyfy) {
                                                $notyfy.close();
                                            }
                                        }
                                    ]
                                });
                            } else {
                                mergeBatchQty(selected, mergeinto);
                            }
                        }
                    });
                });
            }
        });
    }
});

function mergeBatchQty(selected, mergeinto) {
    if (confirm("Are you sure?")) {
        Metronic.startPageLoading('Please wait...');
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-merge-batch-quantity",
            data: {data: selected, merge: mergeinto},
            dataType: 'html',
            success: function (data) {
                if (data == 1) {
                    notyfy({
                        force: true,
                        text: '<strong>Merged successfully!<strong>',
                        type: 'success',
                        layout: 'top'
                    });
                } else {
                    notyfy({
                        force: true,
                        text: '<strong>An error occur!<strong>',
                        type: 'error',
                        layout: 'top'
                    });
                }
                Metronic.stopPageLoading();
                $("#submit").trigger("click");
            }
        });
    }
}

$("#warehouse").change(function () {
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-get-products-by-wh-transactions",
        data: {wh_id: $("#warehouse").val()},
        dataType: 'html',
        success: function (data) {
            $('#product').html(data);
        }
    });
});