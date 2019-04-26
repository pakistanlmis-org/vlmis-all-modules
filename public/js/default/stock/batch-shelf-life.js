$("#expiry_date").datepicker({
    minDate: "-3Y",
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
});



$("#ledger").validate({
    rules: {
        product: {
            required: true
        },
        from_date: {
            required: true
        },
        to_date: {
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
        from_date: {
            required: "Please select from date"
        },
        to_date: {
            required: "Please select to date"
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

$('#submit').click(function(e) {
    if ($("#ledger").valid()) {
        e.preventDefault();
        var formdata = $("#ledger").serialize();
        Metronic.startPageLoading('Please wait...');
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-batch-shelf-life",
            data: {data: formdata},
            dataType: 'html',
            success: function(data) {
                $('#product_ledger').html(data);
                Metronic.stopPageLoading();
             
            }
        });
    }

});