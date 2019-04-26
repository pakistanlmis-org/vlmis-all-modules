$(function () {//
    $("#gatepasslist").validate({
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
    if ($("#item_pack_size_id").val() != '') {
        getProductsByStakeholder($("#item_pack_size_id").val());

    }

    $('#item_pack_size_id').change(function () {
        $('#stock_batch_id').html('');
        if ($(this).val() != "") {
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-batch-number",
                data: {item_pack_size_id: $(this).val()},
                dataType: 'html',
                success: function (data) {
                    $('#stock_batch_id').html(data);
                    $('#stock_batch_id').css('backgroundColor', 'Green');
                    $.cookie('blink_div_background_color', "stock_batch_id");
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
        onClose: function (dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    endDateTextBox.datepicker('setDate', testStartDate);
            } else {
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

});
$('#reset').click(function () {
    window.location.href = appName + '/stock/gatepass-list';
});

function getProductsByStakeholder(item_id) {



    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-batch-number",
        data: {item_pack_size_id: item_id},
        dataType: 'html',
        success: function (data) {
            $('#stock_batch_id').html(data);
            $('#stock_batch_id').css('backgroundColor', 'Green');
            $.cookie('blink_div_background_color', "stock_batch_id");
            setTimeout(changeColor, 1000);
        }
    });


}