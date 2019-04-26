$("#from_date, #to_date").datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    defaultDate: 'dd/mm/yy',
    minDate: '01/01/2013',
    maxDate: 0
});

$('#reset').click(function () {
    window.location.href = appName + '/stock/search-planned-issue';
});

$('.future_arrival_details').click(function () {
    $("#future_arrival_details").hide('slow');
    //$("#future_arrival_details").html('');
    Metronic.startPageLoading('Please wait...');
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-planned-issue-details",
        data: {id: $(this).attr("id")},
        dataType: 'html',
        success: function (data) {
            $('#future_arrival_details').html(data);
            Metronic.stopPageLoading();
            $("#future_arrival_details").show('slow');

            $('[data-toggle="notyfy"]').click(function () {
                $.notyfy.closeAll();
                var self = $(this);
                var id = self.attr("id");
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
                                var id = self.attr("id");
                                $notyfy.close();
                                $.ajax({
                                    type: "POST",
                                    url: appName + '/stock/ajax-delete-pipeline-consignment?id=' + id,
                                    data: {id: self.data('bind')},
                                    dataType: 'html',
                                    success: function (data) {
                                        notyfy({
                                            force: true,
                                            text: 'Future consignment has been deleted!',
                                            type: 'success',
                                            layout: self.data('layout')
                                        });
                                        self.closest("tr").remove();
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

            $('#issued').click(function () {
                // Checking Picked Quantity 
                var valid = false;
                $("input[id$='-received']").each(function () {
                    if (isNaN($(this).val()))
                    {
                        alert('Quantity should be a number');
                        $(this).val("");
                        $(this).focus();
                        valid = false;
                    }
                    else {
                        valid = true;
                    }
                });

                if (valid)
                {
                    $.notyfy.closeAll();
                    notyfy({
                        text: notification["confirm"],
                        type: "confirm",
                        dismissQueue: true,
                        layout: "top",
                        buttons: ("confirm" != 'confirm') ? false : [
                            {
                                addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                                text: '<i></i> Ok',
                                onClick: function ($notyfy) {
                                    $notyfy.close();
                                    $("#pipeline-form").submit();
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
                }
            });

            var notification = [];
            notification['confirm'] = 'Do you want to continue?';
        }
    });
});

