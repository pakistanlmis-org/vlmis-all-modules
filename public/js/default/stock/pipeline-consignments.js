$('#add-rows').hide();
var form_clean;
var interval = null;

$(function () {

    form_clean = $("#future_arrival").serialize();

    // Auto Save function call
    interval = setInterval(autoSave, 20000);

    $("#expected_arrival_date").datepicker({
        minDate: "0",
        maxDate: "+5Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: new Date()
    });

    refreshDateFields();

    $("#default_counter").priceFormat({
        prefix: '',
        thousandsSeparator: '',
        suffix: '',
        centsLimit: 0,
        limit: 2
    });

    // validate signup form on keyup and submit
    $("#future_arrival").validate({
        rules: {
            'from_warehouse_id': {
                required: true
            },
            'expected_arrival_date': {
                required: true
            },
            'stakeholder_activity_id': {
                required: true
            }
        },
        messages: {
            'from_warehouse_id': {
                required: "Please select source"
            },
            'expected_arrival_date': {
                required: "Please select expected arrival date"
            },
            'stakeholder_activity_id': {
                required: "Please select purpose"
            }
        }
    });

    var prev_val;
    $("#stakeholder_activity_id").click(function () {
        prev_val = $(this).val();
    }).change(function () {
        var sel_opt = $("#stakeholder_activity_id option:selected").text();
        if (confirm('Do you want to continue with ' + sel_opt + ' products.')) {
            Metronic.startPageLoading('Please wait...');
            $.ajax({
                type: "POST",
                url: appName + "/stock/ajax-get-products-by-stakeholder-activity",
                data: {
                    activity_id: $(this).val(), type: 1
                },
                dataType: 'html',
                success: function (data) {
                    data = "<option val=''>Select</option>" + data;
                    $('.products').html(data);
                    $('.manufaturers').html("<option val=''>Select</option>");
                    Metronic.stopPageLoading();
                }
            });
        } else {
            $("#stakeholder_activity_id").val(prev_val);
        }
    });

    $("#add_more").click(function () {
        Metronic.startPageLoading('Please wait...');

        var start = 0;
        var end = 0;
        var default_cntr = $("#default_counter").val();

        $("#add-rows").show();
        start = $('.table tr.dynamic-rows').length;
        end = parseInt(start) + parseInt(default_cntr);

        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-add-more-rows",
            data: {start: start, end: end},
            dataType: 'html',
            success: function (data) {
                $('tbody').append(data);
                $("#counter").val(end);
                $.ajax({
                    type: "POST",
                    url: appName + "/stock/ajax-get-products-by-stakeholder-activity",
                    data: {
                        activity_id: $("#stakeholder_activity_id").val(), type: 1
                    },
                    dataType: 'html',
                    success: function (combodata) {
                        combodata = "<option val=''>Select</option>" + combodata;
                        for (var i = start; i < end; i++) {
                            $("#rows" + i + "-item_pack_size_id").html(combodata);
                        }
                    }
                });
                Metronic.stopPageLoading();
                refreshDateFields();
            }
        });
    });
});

function refreshDateFields() {
    $("input[id$='-expiry_date']").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: new Date()
    });

    $("input[id$='-production_date']").datepicker({
        minDate: "-5Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        defaultDate: new Date()
    });

    $("select[id$='-item_pack_size_id']").change(function () {
        var str = $(this).attr("id");
        var row = str.replace("-item_pack_size_id", "");
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-get-manufacturer-by-product",
            data: {
                item_id: $(this).val()
            },
            dataType: 'html',
            success: function (data) {
                $('#' + row + '-manufacturer_id').html(data);
            }
        });
    });

    $("input[id$='-quantity']").priceFormat({
        prefix: '',
        thousandsSeparator: ',',
        suffix: '',
        centsLimit: 0,
        limit: 10
    });

    $("input[id$='-unit_price']").priceFormat({
        prefix: '',
        thousandsSeparator: '',
        suffix: '',
        centsLimit: 2
    });

    $.validator.addMethod("custom_alphanumeric", function (value, element) {
        return this.optional(element) || value === "NA" || value.match(/^[a-zA-Z0-9-_]+$/);
    }, "Letters, numbers, hyphen and underscores only please");

    $('.number').keyup(function () {
        $(this).val($(this).val().toUpperCase());
    });

    $.validator.addClassRules("number", {
        nowhitespace: true,
        custom_alphanumeric: true
    });

    $("#add_stock").click(function () {
        if ($("#future_arrival").valid()) {
            clearInterval(interval);
        }
    });
}

function autoSave() {
    var form_dirty = $("#future_arrival").serialize();
    if (form_clean != form_dirty)
    {
        $('#add_stock').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-pipeline-consignments-draft",
            data: $('#future_arrival').serialize(),
            cache: false,
            success: function (data) {
                if (data == true) {
                    $('#notific8_show').trigger('click');
                }
                $('#add_stock').removeAttr('disabled');
            }
        });
        form_clean = form_dirty;
    }
}