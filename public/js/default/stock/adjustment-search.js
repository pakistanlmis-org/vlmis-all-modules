$(function() {
    $('#date_to').css('position', 'relative');
    $('#date_to').css('zIndex', 3000);

    $("#expiry_date").datepicker({
        minDate: "-3Y",
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    var startDateTextBox = $('#date_from');
    var endDateTextBox = $('#date_to');

    startDateTextBox.datepicker({
        minDate: "01/01/2013",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function(dateText, inst) {
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
        onSelect: function(selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    });
    endDateTextBox.datepicker({
        minDate: "01/01/2013",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function(dateText, inst) {
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
        onSelect: function(selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    });

    $(document).on("click", "[data-toggle='notyfy']", function() {

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
                    onClick: function($notyfy) {
                        Metronic.startPageLoading('Please wait...');
                        var id = self.data("id");
                        $notyfy.close();
                        $.ajax({
                            type: "POST",
                            url: appName + "/stock/delete-adjustment",
                            data: {id: id},
                            success: function(data) {
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
                    onClick: function($notyfy) {
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
$('#product').change(function() {
    $("#available").val('');

    $.ajax({
        type: "POST",
        //url: appName + "/stock-batch/ajax-running-batches",
        url: appName + "/stock/ajax-adjusted-batches",
        data: {item_id: $('#product').val()},
        dataType: 'html',
        success: function(data) {

            $('#batch_no').html(data);
        }
    });

});

if ($('#product').val() !== "") {
    $.ajax({
        type: "POST",
        //url: appName + "/stock-batch/ajax-running-batches",
        url: appName + "/stock/ajax-adjusted-batches",
        data: {item_id: $('#product').val(), page: "adjustment"},
        dataType: 'html',
        success: function(data) {

            $('#batch_no').html(data);
            $('#batch_no').val($('#hdn_batch_no').val());
        }
    });
}

$('#print_stock').click(
        function() {
            var adjustment_no, adjustment_type, product, batch_no, date_from, date_to, all_arguments;
            adjustment_no = $('#adjustment_no').val();
            adjustment_type = $('#adjustment_type').val();
            product = $('#product').val();
            batch_no = $('#batch_no').val();
            date_from = $('#date_from').val();
            date_to = $('#date_to').val();
            all_arguments = "?adjustment_no=" + adjustment_no + "&adjustment_type=" + adjustment_type + "&product=" + product + "&batch_no=" + batch_no + "&date_from=" + date_from + "&date_to=" + date_to;
            window.open('stock-adjustment-print' + all_arguments, '_blank', 'scrollbars=1,width=860,height=595');
        }
);

$('#reset').click(function() {
    window.location.href = appName + '/stock/adjustment-search';
});