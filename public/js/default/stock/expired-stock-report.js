$(function () {
    $('#date_to').css('position','relative');
    $('#date_to').css('zIndex',3000);
    
    var startDateTextBox = $('#date_from');
    var endDateTextBox = $('#date_to');

    startDateTextBox.datepicker({
        minDate: "-3Y",
        maxDate: 0,
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
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.datepicker('setDate', testEndDate);
            } else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    });

    $("#expired_stock").validate({
        rules: {
            date_from: {
                required: true
            },
            date_to: {
                required: true
            }
        },
        messages: {
            date_from: {
                required: "Please select From Date"
            },
            date_to: {
                required: "Please select To Date"
            }
        }
    });
});

$('#print_stock').click(function () {
    var product, date_from, date_to, all_arguments;
    adjustment_type = $('#adjustment_type').val();
    date_from = $('#date_from').val();
    date_to = $('#date_to').val();
    all_arguments = "?adjustment_type=" + adjustment_type + "&date_from=" + date_from + "&date_to=" + date_to;
    window.open('expired-stock-print' + all_arguments, '_blank', 'scrollbars=1,width=860,height=595');
});