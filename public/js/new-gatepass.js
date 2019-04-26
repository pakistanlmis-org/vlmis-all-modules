$(function() {
    if ($('#vehicle_type').val() != "") {
        $('#loader_make').show();
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-get-vehicle",
            data: {vehicletype: $('#vehicle_type').val(), vehicle: $('#model_id').val()},
            dataType: 'html',
            success: function(data) {
                $('#loader_make').hide();
                $('#div_model').show('slow');
                $('#vehicle').html(data);
                $('#vehicle').val($('#model_id').val());
            }
        });
    }
    $('#vehicle_type').change(function() {
        $('#vehicle').html('');
        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-get-vehicle",
                data: {make: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $('#vehicle').html(data);
                    $('#vehicle').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "vehicle");
                    setTimeout(changeColor, 1000);
                }
            });
        }
    });
    
       var startDateTextBox = $('#date_from');
    var endDateTextBox = $('#date_to');

    startDateTextBox.datepicker({
        minDate: "-3Y",
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


});
