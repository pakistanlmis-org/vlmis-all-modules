$(function () {
    $("#placement").validate({
        rules: {
            area: {
                required: true
            },
            row: {
                required: true
            },
            rack: {
                required: true
            },
            rack_information_id: {
                required: true
            },
            pallet: {
                required: true
            },
            level: {
                required: true
            }

        },
        messages: {
            area: {
                required: "Please select store."
            },
            row: {
                required: "Please select row."
            },
            rack: {
                required: "Please select rack."
            },
            rack_information_id: {
                required: "Please select rack type."
            },
            pallet: {
                required: "Please select shelf."
            },
            level: {
                required: "Please select bin."
            }
        }
    });
    $(".update-placement").click(function () {
        $.ajax({
            type: "POST",
            url: appName + "/stock/update-placement",
            data: {placement_id: $(this).attr('editid')},
            dataType: 'html',
            success: function (data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

    $('[data-toggle="notyfy"]').click(function () {
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
                    onClick: function ($notyfy) {
                        $notyfy.close();
                        $.ajax({
                            type: "POST",
                            url: appName + "/stock/delete-placement",
                            data: {placement_id: self.data('bind')},
                            dataType: 'html',
                            success: function (data) {
                                if (data == "true") {
                                    notyfy({
                                        force: true,
                                        text: 'Location has been deleted!',
                                        type: 'success',
                                        layout: self.data('layout')
                                    });
                                    self.closest("tr").remove();
                                } else if (data == "exists") {
                                    notyfy({
                                        force: true,
                                        text: 'Location can not be deleted because this id using in placement!',
                                        type: 'error',
                                        layout: self.data('layout')
                                    });
                                }
                            }
                        });
                    }
                }, {
                    addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                    text: '<i></i> Cancel',
                    onClick: function ($notyfy) {
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

    $('#print_locations').click(function () {
        window.open(appName + '/stock/print-locations', '_blank', 'scrollbars=1,width=860,height=595');
    });
});