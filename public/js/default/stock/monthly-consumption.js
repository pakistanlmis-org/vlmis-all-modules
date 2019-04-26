var form_clean;
var interval = null;

$(document).ready(function() {

    $("#refresh_receive").trigger("click");

    form_clean = $("#dataEntryfrm").serialize();

    // Auto Save function call
    interval = setInterval(autoSave, 20000);

    $('#uc').change(function() {
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-uc-centers",
            data: {uc: $('#uc').val()},
            dataType: 'html',
            success: function(data) {
                $('#uc_center_div').show();
                $('#showMonths').hide();
                $('#uc_center').html(data);
            }
        });
    });

    // load month - year reports
    $('#uc_center').change(function() {
        $('#last-report').html('');
        $('#showMonths').show();
        show3Months();
        showCombos();
    });

    // load month - year reports
    $('#uc_center_update').change(function() {
        $('#last-report').html('');
        show3MonthsUpdate();
    });

    $("input[id$='-expiry']").datepicker({dateFormat: 'dd/mm/yy'});

    $("input[id$='-opening_balance'],input[id$='-received']").change(function() {

        if ($(this).val() == '') {
            $(this).val('0');
        }
    })

    $("input[id$='-vials_used'],input[id$='-opening_balance'],input[id$='-received'],input[id$='-dispensed'],input[id$='-unusable_vials']").focus(function(e) {
        var val = $(this).val();
        if (val == 0) {
            $(this).val('');
        }
    });
    $("input[id$='-unusable_vials'],input[id$='-dispensed']").focusout(function(e) {
        if ($(this).val() == '' || isNaN($(this).val())) {
            $(this).val('0');
        }
    });

    $("input[id$='-vials_used'],input[id$='-opening_balance'],input[id$='-received']").focusout(function() {
        if ($(this).val() == '' || isNaN($(this).val())) {
            $(this).val('0');
            return false;
        }
        if ($(this).val() < 0) {
            $(this).val(Math.abs($(this).val()));
        }

        var str = $(this).attr("id");
        var itemid = str.split("-");
        itemid = itemid[0];
        var doses = $('#' + itemid + '-doses').val();
        var opening = $('#' + itemid + '-opening_balance').val();
        var received = $('#' + itemid + '-received').val();
        var used = ($('#' + itemid + '-vials_used').val() * doses);
        var used_vials = $('#' + itemid + '-vials_used').val();
        var unused = ($('#' + itemid + '-unusable_vials').val() * doses);
        var dispensed = parseInt($('#' + itemid + '-dispensed').val());
        var a = (parseInt(used) + parseInt(unused));

        //alert(itemid + ' - ' + doses + ' - ' + opening + ' - ' + received + ' - ' + used + ' - ' + used_vials + ' - ' + unused + ' - ' + dispensed);

        if (used < dispensed) {
            alert("Used Vials must equal or greater then dispensed quantity");
            $(this).focus();
            /*setTimeout(function() {
             $(this).focus();
             }, 0);*/
        }

        if ($('#' + itemid + 'dispensed').val() < 0) {
            $('#' + itemid + 'dispensed').css({
                backgroundColor: '#ed0000'
            });
            flag = true;
        } else {
            $('#' + itemid + 'dispensed').css({
                backgroundColor: ''
            });
        }

        var b = (parseInt(opening) + parseInt(received));
        var c = (parseInt(b) - parseInt(a));

        if (a > b) {
            alert("Used/Unused Vials must equal or less then opening + receive quantity");
            $(this).focus();
            /*setTimeout(function() {
             $(this).focus();
             }, 0);*/
        }
        if (used_vials > dispensed && used < b) {
            alert("Used Vials must not greater then dispensed quantity");
            $(this).focus();
            /*setTimeout(function() {
             $(this).focus();
             }, 0);*/
        }

        $('#' + itemid + '-closing_balance').val(c);
        $('#' + itemid + '-cb').val(c);
        if (/-/i.test(c)) {
            $('#' + itemid + '-cb').css({
                backgroundColor: '#ed0000'
            });
            $("#btn-loading").attr("disabled", true);
        } else {
            $('#' + itemid + '-cb').css({
                backgroundColor: ''
            });
            $("#btn-loading").attr("disabled", false);
        }
    });

    $("input[id$='-dispensed']").change(function() {
        var value = $(this).attr("id");
        var itemid = value.replace("-dispensed", "");
        var opening = $('#' + itemid + '-opening_balance').val();
        var received = $('#' + itemid + '-received').val();
        var dispensed = parseInt($('#' + itemid + '-dispensed').val());
        var total = (parseInt(opening) + parseInt(received));

        if ($('#' + itemid + '-vials_used').prop('readonly') == true)
        {
            var cb = total - dispensed;
            $('#' + itemid + '-cb').val(cb);
            $('#' + itemid + '-closing_balance').val(cb);
        }

        if (total < parseInt($(this).val())) {
            alert('Dispensed value must be smaller then ' + total);
            $('#' + itemid + '-dispensed').css({
                backgroundColor: '#ed0000'
            });
        } else {
            $('#' + itemid + '-dispensed').css({
                backgroundColor: ''
            });
        }
    });

    $("input[id$='-unusable_vials']").change(function() {
        var value = $(this).attr("id");
        var itemid = value.replace("-unusable_vials", "");
        var doses = $('#' + itemid + '-doses').val();
        var opening = $('#' + itemid + '-opening_balance').val();
        var received = $('#' + itemid + '-received').val();
        var used = ($('#' + itemid + '-vials_used').val() * doses);
        var unused = ($(this).val() * doses);
        var a = (parseInt(used) + parseInt(unused));

        if ($(this).val() < 0) {
            $(this).css({
                backgroundColor: '#ed0000'
            });
            flag = true;
        } else {
            $(this).css({
                backgroundColor: ''
            });
        }

        var b = (parseInt(opening) + parseInt(received));
        var c = (parseInt(b) - parseInt(a));

        if (a > b) {
            alert("Used/Unused Vials must equal or less then opening + receive quantity");
            $(this).focus();
            /*setTimeout(function() {
             $(this).focus();
             }, 0);*/
        }

        $('#' + itemid + '-closing_balance').val(c);
        $('#' + itemid + '-cb').val(c);
        if (/-/i.test(c)) {
            $('#' + itemid + '-cb').css({
                backgroundColor: '#ed0000'
            });
            $("#btn-loading").attr("disabled", true);
        } else {
            $('#' + itemid + '-cb').css({
                backgroundColor: ''
            });
            $("#btn-loading").attr("disabled", false);
        }
    });

    $("#monthly_report").change(function() {

        var action = $(this).val();

        var url = appName + '/stock/monthly-consumption?do=' + action;
        window.location.href = url;
    });

    // validate signup form on keyup and submit
    $("#dataEntryfrm").validate({
        rules: {
            'dispensed[]': {
                required: true,
                number: true
            },
            'vials_used[]': {
                required: true,
                number: true
            },
            'unusable_vials[]': {
                required: true,
                number: true
            },
            'nearest_expiry[]': {
                required: true
            }
        },
        messages: {
        }
    });

    $("#btn-loading").click(function(e) {
        Metronic.startPageLoading('Please wait...');
        e.preventDefault();
        var flag = true;
        $("input[id$='-vials_used']").each(function() {
            if ($(this).val() < 0) {
                $(this).css({
                    backgroundColor: '#ed0000'
                });
                alert("Please enter positive value");
                $(this).focus();
                /*setTimeout(function() {
                 $(this).focus();
                 }, 0);*/
                flag = false;
                return false;
            } else {
                $(this).css({
                    backgroundColor: ''
                });
            }
            var value = $(this).attr("id");
            var itemid = value.replace("-vials_used", "");
            var doses = $('#' + itemid + '-doses').val();
            var opening = $('#' + itemid + '-opening_balance').val();
            var received = $('#' + itemid + '-received').val();
            var used = ($(this).val() * doses);
            var used_vials = $(this).val();
            var unused = ($('#' + itemid + '-unusable_vials').val() * doses);
            var dispensed = parseInt($('#' + itemid + '-dispensed').val());
            var a = (parseInt(used) + parseInt(unused));

            if (used < dispensed) {
                alert("Used Vials must equal or greater then dispensed quantity");
                $(this).focus();
                /*setTimeout(function() {
                 $(this).focus();
                 }, 0);*/
                flag = false;
                return false;
            }

            var b = (parseInt(opening) + parseInt(received));
            if (a > b) {
                alert("Used/Unused Vials must equal or less then opening + receive quantity");
                $(this).focus();
                /*setTimeout(function() {
                 $(this).focus();
                 }, 0);*/
                flag = false;
                return false;
            }
            if (used_vials > dispensed && used < b) {
                alert("Used Vials must not greater then dispensed quantity");
                $(this).focus();
                /*setTimeout(function() {
                 $(this).focus();
                 }, 0);*/
                flag = false;
                return false;
            }
        });

        $("input[id$='-unusable_vials']").each(function() {
            if ($(this).val() < 0) {
                $(this).css({
                    backgroundColor: '#ed0000'
                });
                alert("Please enter positive value");
                $(this).focus();
                /*setTimeout(function() {
                 $(this).focus();
                 }, 0);*/
                flag = false;
                return false;
            } else {
                $(this).css({
                    backgroundColor: ''
                });
            }
        });

        $("input[id$='-dispensed']").each(function() {
            var value = $(this).attr("id");
            var itemid = value.replace("-dispensed", "");
            var opening = $('#' + itemid + '-opening_balance').val();
            var received = $('#' + itemid + '-received').val();
            var total = (parseInt(opening) + parseInt(received));
            if (total < parseInt($(this).val())) {
                alert('Dispensed value must be smaller then ' + total);
                $('#' + itemid + '-dispensed').css({
                    backgroundColor: '#ed0000'
                });
                $(this).focus();
                /*setTimeout(function() {
                 $(this).focus();
                 }, 0);*/
                flag = false;
                return false;
            } else {
                $('#' + itemid + '-dispensed').css({
                    backgroundColor: ''
                });
            }
        });

        if (flag == true) {
            clearInterval(interval);
            $("#dataEntryfrm").submit();
        } else {
            Metronic.stopPageLoading();
        }
    });
});

function show3Months() {
    //$('#showGrid').html('');
    var wh_id = $('#uc_center').val();
    var loc_id = $('#uc').val();

    //alert(parseInt(wh_id) + ' - ' + parseInt(loc_id));

    if (!isNaN(parseInt(wh_id)) && !isNaN(parseInt(loc_id))) {
        $.ajax({
            type: "POST",
            url: appName + "/stock/load-last-3months",
            data: {warehouse_id: wh_id, location_id: loc_id},
            success: function(data) {
                $('#showMonths').html(data);
            }
        });
    } else {
        $('#showMonths').html('');
    }
}

function show3MonthsUpdate() {
    //$('#showGrid').html('');
    var wh_id = $('#uc_center_update').val();
    var loc_id = $('#uc-' + wh_id).val();
    if (wh_id != '' && loc_id != '') {
        $.ajax({
            type: "POST",
            url: appName + "/stock/load-last-3months",
            data: {warehouse_id: wh_id, location_id: loc_id, update: 1},
            success: function(data) {
                $('#showMonths').html(data);
            }
        });
    } else {
        $('#showMonths').html('');
    }
}

function showCombos() {
    var wh_id = $('#uc_center').val();
    if (wh_id != '') {
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-report-combo",
            data: {warehouse_id: wh_id},
            success: function(data) {

                $('#monthly_report').html(data);
                $('#monthly_report').val($('#do').val());
            }
        });
    }
//    } else {
//        $('#monthly_report').html('<option value="">Month - Year</option>');
//    }
}

function getValue(val, id) {

    $('#' + id + '-vials_used').val(val);
}

$('#print-monthly-consumption').click(function() {

    var monthly_report = $('#monthly_report').val();
    window.open('print-monthly-consumption?do=' + monthly_report, '_blank', 'scrollbars=1,width=860,height=595');

});


function getPreMonthCB(wh_id, month, year, isNewRpt)
{
    if (confirm('Are you sure you refresh Closing Balance from previous month?'))
    {
        $.ajax({
            url: appName + '/stock/ajax-get-pre-month-cb',
            type: 'post',
            data: {wh_id: wh_id, month: month, year: year, isNewRpt: isNewRpt},
            success: function(data) {
                var returnData = $.parseJSON(data);

                $.each(returnData, function(key, value) {
                    $('#' + key + '-opening_balance').val(value);

                    var doses = $('#' + key + '-doses').val();
                    var dispensed = $('#' + key + '-dispensed').val();
                    var received = $('#' + key + '-received').val();
                    var used = ($('#' + key + '-vials_used').val() * doses);
                    var unused = ($('#' + key + '-unusable_vials').val() * doses);
                    var a = (parseInt(used) + parseInt(unused));

                    var b = (parseInt(value) + parseInt(received));
                    var c = (parseInt(b) - parseInt(a));

                    $('#' + key + '-cb').val(c);
                    $('#' + key + '-closing_balance').val(c);
                });

            }
        });
    }
}

function getAutoPreMonthCB(wh_id, month, year, isNewRpt)
{
    getAutoPreMonthReceive(wh_id, month, year, isNewRpt);

    $.ajax({
        url: appName + '/stock/ajax-get-pre-month-cb',
        type: 'post',
        data: {wh_id: wh_id, month: month, year: year, isNewRpt: isNewRpt},
        success: function(data) {
            var returnData = $.parseJSON(data);

            $.each(returnData, function(key, value) {
                $('#' + key + '-opening_balance').val(value);

                var doses = $('#' + key + '-doses').val();
                var dispensed = $('#' + key + '-dispensed').val();
                var received = $('#' + key + '-received').val();
                var used = ($('#' + key + '-vials_used').val() * doses);
                var unused = ($('#' + key + '-unusable_vials').val() * doses);
                var a = (parseInt(used) + parseInt(unused));

                var b = (parseInt(value) + parseInt(received));
                var c = (parseInt(b) - parseInt(a));

                $('#' + key + '-cb').val(c);
                $('#' + key + '-closing_balance').val(c);
            });

        }
    });
}

function getAutoPreMonthReceive(wh_id, month, year, isNewRpt)
{
    if (isNewRpt == 1) {
        $.ajax({
            url: appName + '/stock/ajax-get-pre-month-receive',
            type: 'post',
            data: {wh_id: wh_id, month: month, year: year},
            success: function(data) {
                var returnData = $.parseJSON(data);

                $.each(returnData, function(key, value) {
                    $('#' + key + '-received').val(value);

                    var doses = $('#' + key + '-doses').val();
                    var dispensed = $('#' + key + '-dispensed').val();
                    var ob = $('#' + key + '-opening_balance').val();
                    var used = ($('#' + key + '-vials_used').val() * doses);
                    var unused = ($('#' + key + '-unusable_vials').val() * doses);
                    var a = (parseInt(used) + parseInt(unused));

                    var b = (parseInt(ob) + parseInt(value));
                    var c = (parseInt(b) - parseInt(a));

                    $('#' + key + '-cb').val(c);
                    $('#' + key + '-closing_balance').val(c);
                });
            }
        });
    }
}

$(".nearest_expiry").datepicker({
    minDate: 0,
    maxDate: "+10Y",
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    defaultDate: $("#defaultdate").val()
});

function autoSave() {
    var form_dirty = $("#dataEntryfrm").serialize();
    if (form_clean != form_dirty)
    {
        $('#saveBtn').attr('disabled', 'disabled');
        $("#eMsg").html('Saving...');
        $.ajax({
            type: "POST",
            url: appName + "/stock/monthly-consumption-draft",
            data: $('#dataEntryfrm').serialize(),
            cache: false,
            success: function() {
                $('#notific8_show').trigger('click');
                $('#btn-loading').removeAttr('disabled');
            }
        });
        form_clean = form_dirty;
    }
}