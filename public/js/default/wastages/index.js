
$(document).ready(function () {
    $("#spinner13").hide();
    $("#spinner14").hide();
    $("#spinner15").hide();
    var h = $(".dashboard-header").css('height');
    if (h != "51px") {
        $(".sidebar-toggler").trigger("click");
    }
});
$("#item").change(function () {
    if ($(this).val() == 'all')
    {
        $('#cost_type').html("");
        $('#cost_type').append($('<option />').val('2').text('Average Cost Price'));


    } else {
        $('#cost_type').html("");
        $('#cost_type').append($('<option />').val('1').text('Actual'));
        $('#cost_type').append($('<option />').val('2').text('Average Cost Price'));
        $('#cost_type').append($('<option />').val('3').text('Manual Price'));

    }
});
$('#item').change(function () {
    if ($('#cost_type').val() == 1) {

        $('#div_cost').show();
        $.ajax({
            type: "POST",
            url: appName + "/wastages/ajax-funding-source",
            data: {item_id: $('#item').val(), province: $('#province').val(), currency: $('#currency').val(), usd: $('#usd').val()},
            dataType: 'html',
            success: function (data) {
                $('#funding_source').html(data);
                $('#un_price').val($("#funding_source option:first").text());
            }
        });
    } else {
        $('#div_cost').hide();
    }
});
$('#funding_source').change(function () {
    $('#un_price').val($("#funding_source option:selected").text());
})
$('#cost_type').change(function () {
    if ($('#cost_type').val() == 1) {

        $('#div_cost').show();
        $.ajax({
            type: "POST",
            url: appName + "/wastages/ajax-funding-source",
            data: {item_id: $('#item').val(), province: $('#province').val(), currency: $('#currency').val(), usd: $('#usd').val()},
            dataType: 'html',
            success: function (data) {
                $('#funding_source').html(data);

                $('#un_price').val($("#funding_source option:first").text());
            }
        });
    } else {
        $('#div_cost').hide();
    }
});
$('#cost_type').change(function () {
    if ($('#cost_type').val() == 3) {
        $('#div_c').hide();
        $('#div_currency').hide();
        $('#div_manual').show();

    } else {
        $('#div_manual').hide();
        $('#div_c').show();
        if ($('#currency').val() == 2) {
            $('#div_currency').show()
        }
    }
});
if ($('#cost_type').val() == 3) {
    $('#div_c').hide();
    $('#div_currency').hide();
    $('#div_manual').show();
} else {
    $('#div_manual').hide();
    $('#div_c').show();

}
$('#year').change(function () {

    if ($('#year').val() != 'all') {

        $('#div_month').show();
    } else {
        $('#div_month').hide();
        $('#ending_month').val("");
    }
});

if ($('#year').val() != 'all') {

    $('#div_month').show();
} else {
    $('#div_month').hide();
}
$('#currency').change(function () {
    $('#cost_type').val(2);
    $('#div_cost').hide();
    if ($('#currency').val() == 2) {

        $('#div_currency').show();
    } else {
        $('#div_currency').hide();
    }
});

if ($('#currency').val() == 2) {

    $('#div_currency').show();
} else {
    $('#div_currency').hide();
}

if ($('#cost_type').val() == 1) {

    $('#div_cost').show();
    $.ajax({
        type: "POST",
        url: appName + "/wastages/ajax-funding-source",
        data: {item_id: $('#item').val(), province: $('#province').val(), currency: $('#currency').val(), usd: $('#usd').val()},
        dataType: 'html',
        success: function (data) {
            $('#funding_source').html(data);
            $('#un_price').val($("#funding_source option:first").text());
        }
    });
} else {
    $('#div_cost').hide();
}
$(document).on('change', 'input:radio[name^="w_c"]', function (event) {


    $("#spinner13").show();
    $("#after-filter13").hide();
    $.ajax({
        type: "POST",
        url: appName + "/wastages/ajax-get-consumption",
        data: {province: $("#province").val(), d_c: $(this).val(), item: $("#item").val(), year: $('#year').val(), ending_month: $('#month').val(), currency: $('#currency').val(), cost_type: $('#cost_type').val(), funding_source: $('#funding_source').val(), manual_price: $('#manual_price').val(), usd: $('#usd').val()},
        dataType: 'html',
        success: function (data) {
            $("#spinner13").hide();
            $("#after-filter13").show();
            $("#after-filter13").html(data);
        }
    });
});

$(document).on('change', 'input:radio[name^="w_b"]', function (event) {


    $("#spinner14").show();
    $("#after-filter14").hide();
    $.ajax({
        type: "POST",
        url: appName + "/wastages/ajax-get-wastage",
        data: {province: $("#province").val(), d_c: $(this).val(), item: $("#item").val(), year: $('#year').val(), ending_month: $('#month').val(), currency: $('#currency').val(), cost_type: $('#cost_type').val(), funding_source: $('#funding_source').val(), manual_price: $('#manual_price').val(), usd: $('#usd').val()},
        dataType: 'html',
        success: function (data) {
            $("#spinner14").hide();
            $("#after-filter14").show();
            $("#after-filter14").html(data);
        }
    });
});


$(document).on('change', 'input:radio[name^="e_u"]', function (event) {


    $("#spinner15").show();
    $("#after-filter15").hide();
    $.ajax({
        type: "POST",
        url: appName + "/wastages/ajax-get-expired",
        data: {province: $("#province").val(), d_c: $(this).val(), item: $("#item").val(), year: $('#year').val(), ending_month: $('#month').val(), currency: $('#currency').val(), cost_type: $('#cost_type').val(), funding_source: $('#funding_source').val(), manual_price: $('#manual_price').val(), usd: $('#usd').val()},
        dataType: 'html',
        success: function (data) {
            $("#spinner15").hide();
            $("#after-filter15").show();
            $("#after-filter15").html(data);
        }
    });
});