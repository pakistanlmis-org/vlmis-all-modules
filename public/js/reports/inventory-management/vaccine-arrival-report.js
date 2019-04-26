$(function () {
    
    
    
    $("#report_date").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: $("#defaultdate").val()
    });
    $("#date_of_inspection").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: $("#defaultdate").val()
    });
    
    
    $("input[id$='date_of_inspection1']").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: new Date()
    });
    $("#pre_advice_date").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: $("#defaultdate").val()
    });
    $("#shipping_notification_date").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: $("#defaultdate").val()
    });
    $("#date_entered_cold_store").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: $("#defaultdate").val()
    });


    $('#eta_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'd/m/Y h:i A',
        maxDate: 0,
        minDate: "2013/01/01"
    });
    $('#actual_time_arrival').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'd/m/Y h:i A',
        maxDate: 0,
        minDate: "2013/01/01"
    });

    $("#print_var").click(function () {
        var id = $('#shipment_id').val();
        window.open(appName + '/reports/inventory-management/print-var-detail?id=' + id, '_blank', 'scrollbars=1,width=960,height=595');
    });
    
    $("#print").click(function() {
        printCont();
    });

});

function printCont()
{
    $("a").hide();
    $('#print').hide();
    window.print();
    setTimeout(function() {
        // Do something after 5 seconds
        $('#print').show();
        $("a").show();
    }, 5000);
}