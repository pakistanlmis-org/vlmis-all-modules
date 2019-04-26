$(function () {
    $('#new_suggested_date').datepicker({
        minDate: 0,
        maxDate: "+5Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
});

$('#reset').click(function () {
    window.location.href = appName + '/stock-demand/requisition-search';
});

$(function () {
    var startDateTextBox = $('#from_date');
    var endDateTextBox = $('#to_date');

    startDateTextBox.datepicker({
        minDate: "-5Y",
        maxDate: "+5Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    endDateTextBox.datepicker('setDate', testStartDate);
            }
            else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    });
    endDateTextBox.datepicker({
        minDate: "-5Y",
        maxDate: "+5Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.datepicker('setDate', testEndDate);
            }
            else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    });
});


var notification = [];
notification['confirm'] = 'Do you want to delete requisition?';

$('.future_arrival_details').click(function () {
    $("#future_arrival_details").hide('slow');

    Metronic.startPageLoading('Please wait...');
    $.ajax({
        type: "POST",
        url: appName + "/stock-demand/ajax-store-requisitions-details",
        data: {id: $(this).attr("id")},
        dataType: 'html',
        success: function (data) {
            $('#future_arrival_details').html(data);
            Metronic.stopPageLoading();
            $("#future_arrival_details").show('slow');

            $('#received').click(function () {
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
            });
        }
    });
});

$('[data-toggle="notyfy"]').click(function ()
{
    $.notyfy.closeAll();
    var self = $(this);
    notyfy({
        text: notification[self.data('type')],
        type: self.data('type'),
        dismissQueue: true,
        layout: self.data('layout'),
        buttons: (self.data('type') != 'confirm') ? false : [{
                addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                text: '<i></i> Ok',
                onClick: function ($notyfy) {
                    var id = self.attr("id");
                    $notyfy.close();
                    window.location.href = appName + "/stock-demand/delete-requisition-master?id=" + id + "&p=requisition";
                }
            }, {
                addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                text: '<i></i> Cancel',
                onClick: function ($notyfy) {
                    $notyfy.close();
                }
            }]
    });
    return false;
});









