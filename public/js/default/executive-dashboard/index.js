
var url1 = "executive-dashboard/d_1";
var url2 = "executive-dashboard/d_2";
var url3 = "executive-dashboard/d_3";
var url4 = "executive-dashboard/d_4";
$.ajax({
    type: "POST",
    url: appName + '/' + url1,
    data: {last_date: $('#last_month').val(), product: $('#products').val()},
    dataType: 'html',
    success: function (data) {
        $("#spinner11").hide();
        $("#d_1").html(data);
    }
});
$.ajax({
    type: "POST",
    url: appName + '/' + url2,
    data: {last_date: $('#last_month').val(), product: $('#products').val()},
    dataType: 'html',
    success: function (data) {
        $("#spinner12").hide();
        $("#d_2").html(data);
    }
});
$.ajax({
    type: "POST",
    url: appName + '/' + url3,
    data: {last_date: $('#last_month').val(), product: $('#products').val()},
    dataType: 'html',
    success: function (data) {
        $("#spinner13").hide();
        $("#d_3").html(data);
    }
});
$.ajax({
    type: "POST",
    url: appName + '/' + url4,
    data: {last_date: $('#last_month').val(), product: $('#products').val()},
    dataType: 'html',
    success: function (data) {
        $("#spinner14").hide();
        $("#d_4").html(data);
    }
});
$("#last_month").datepicker({
    minDate: "-3Y",
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
});
$(document).on('change', 'input:radio[name^="p3"]', function (event) {
    $("#spinner13").show();

    $('#d_3').html("");
    $.ajax({
        type: "POST",
        url: appName + '/' + url3,
        data: {last_date: $('#last_month').val(), product: $(this).val()},
        dataType: 'html',
        success: function (data) {
            $("#spinner13").hide();
            $("#d_3").html(data);
        }
    });
});

$(document).on('change', 'input:radio[name^="p4"]', function (event) {
    $("#spinner14").show();

    $('#d_4').html("");
    $.ajax({
        type: "POST",
        url: appName + '/' + url4,
        data: {last_date: $('#last_month').val(), product: $(this).val()},
        dataType: 'html',
        success: function (data) {
            $("#spinner14").hide();
            $("#d_4").html(data);
        }
    });
});
//$('#products').select2({
//    placeholder: "Select Products",
//    allowClear: true
//});
$(document).ready(function () {
    var h = $(".dashboard-header").css('height');
    if (h != "51px") {
        $(".sidebar-toggler").trigger("click");
    }
});