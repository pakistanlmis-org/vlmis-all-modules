$(function() {

    var startDateTextBox = $('#date_from');
    var endDateTextBox = $('#date_to');
    startDateTextBox.datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd-mm-yy',
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
        dateFormat: 'dd-mm-yy',
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
    $("#gatepass").validate({
        rules: {
            date_from: {
                required: true
            },
            date_to: {
                required: true
            },
        },
        messages: {
            date_from: {
                required: "Please select date from."
            },
            date_to: {
                required: "Please select date to."
            }
        }
    });
    $("#gatepasspost").validate({
        rules: {
            vehicle_type_id: {
                required: true
            },
            gatepass_vehicle_id: {
                required: true
            },
            vehicle_other: {
                required: true
            },
            transaction_date: {
                required: true
            }
        },
        messages: {
            vehicle_type_id: {
                required: "Select vehicle type."
            },
            gatepass_vehicle_id: {
                required: "Select vehicle."
            },
            vehicle_other: {
                required: "Select vehicle."
            },
            transaction_date: {
                required: "Select transaction date."
            }
        }
    });
    $("#transaction_date").datepicker({
        dateFormat: 'dd/mm/yy',
        constrainInput: false,
        changeMonth: true,
        changeYear: true
    });
    $('#transaction_date').mask('00/00/0000');
    $('#vehicle_type_id').change(function() {
        $('#gatepass_vehicle_id').html('');
        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-vehicle-number",
                data: {vehicle_type_id: $(this).val()},
                dataType: 'html',
                success: function(data) {
                    $('#gatepass_vehicle_id').html(data);
                    $('#gatepass_vehicle_id').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "gatepass_vehicle_id");
                    setTimeout(changeColor, 1000);
                }
            });
        }
    });
    /*$("#search").click(function() {
     $.ajax({
     type: "POST",
     url: appName + "/stock/ajax-new-gatepass",
     data: {datefrom: $('#date_from').val(), dateto: $('#date_to').val()},
     dataType: 'html',
     success: function(data) {
     $('#ajax_call').show();
     $("#stock_master_id").html(data);
     
     if ($.trim($("#stock_master_id").html()) == '')
     {
     
     $("#vehicle_type_id").attr('disabled', true);
     $("#gatepass_vehicle_id").attr('disabled', true);
     $("#transaction_date").attr('disabled', true);
     }
     else {
     $("#vehicle_type_id").attr('disabled', false);
     $("#gatepass_vehicle_id").attr('disabled', false);
     $("#transaction_date").attr('disabled', false);
     }
     
     
     
     
     }
     });
     });*/

    $('#stock_master_id').change(function() {
// if ($(this).val() != "") {
//alert("here");
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-quantity-data-issueno",
            data: {stock_master_id: $(this).val()},
            dataType: 'html',
            success: function(data) {
                $('#ajax_tbl_data').html(data);
                $("#button_gatepass").show();
            }
        });
    });

    $('#other').click(function() {
        if ($(this).is(':checked')) {
            $("#other_span").show();
            $("#vehicle_span").hide();
        } else {
            $("#other_span").hide();
            $("#vehicle_span").show();
        }
    });

    $('#btn-loading').click(function(e) {
        e.preventDefault();
        var q = 0;
        var inp = $('.qty');
        for (var i = 0; i < inp.length; i++)
        {
            // alert(inp[i].value);
            if (inp[i].value != '')
            {
                q++;
                if (parseInt(inp[i].value) > parseInt(inp[i].getAttribute('max'))) {
                    alert('Quantity can not be greater than ' + inp[i].getAttribute('max'));
                    inp[i].focus();
                    return false;
                }
            }
        }

        if (q == 0) {
            alert('Please enter at least one quantity');
            return false;
        } else {
            if ($('#gatepasspost').valid()) {
                varform = $('#gatepasspost').serialize();
                $.ajax({
                    type: "POST",
                    url: appName + "/stock/ajax-new-gatepass1?" + varform,
                    data: {},
                    dataType: 'html',
                    success: function(data) {
                        window.location.href = appName + "/stock/gatepass-list";
                    }
                });
            }
        }
    });
});