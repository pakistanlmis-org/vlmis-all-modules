var form_clean;
var interval = null;

$(document).ready(function () {
    //$(".welcm").addClass("user-hide");
    //$(".welcm").removeClass("title");
    //$(".dashboard-header").css('height', '');
    var h = $(".dashboard-header").css('height');
    if (h != "51px") {
        $(".sidebar-toggler").trigger("click");
    }


    $("#refresh_receive").trigger("click");

    form_clean = $("#dataEntryfrm").serialize();

    // Auto Save function call
    interval = setInterval(autoSave, 20000);

    $('#uc').change(function () {
        $.ajax({
            type: "POST",
            url: appName + "/stock/ajax-uc-centers",
            data: {uc: $('#uc').val()},
            dataType: 'html',
            success: function (data) {
                $('#uc_center_div').show();
                $('#showMonths').hide();
                $('#uc_center').html(data);
            }
        });
    });

    // load month - year reports
    $('#uc_center').change(function () {
        $('#last-report').html('');
        $('#showMonths').show();
        show3Months();
        showCombos();
    });

    // load month - year reports
    $('#uc_center_update').change(function () {
        $('#last-report').html('');
        show3MonthsUpdate();
    });

    $("input[id$='-expiry']").datepicker({dateFormat: 'dd/mm/yy'});

    $("input[id$='-opening_balance'],input[id$='-received']").change(function () {

        if ($(this).val() == '') {
            $(this).val('0');
        }
    });

    $("input[id$='-vials_used'],input[id$='-opening_balance'],input[id$='-received'],input[id$='-dispensed'],input[id$='-unusable'],input[id$='-pregenant_women'],input[id$='-outside_pregenant_women'],input[id$='-non_pregenant_women'],input[id$='-outside_non_pregenant_women'],input[id$='-closing_balance']").focus(function (e) {
        var val = $(this).val();
        if (val == 0) {
            $(this).val('');
        }


    });

    $("input[id$='-fixed_inuc_m_11'],input[id$='-fixed_inuc_f_11'],input[id$='-fixed_outuc_m_11'],input[id$='-fixed_outuc_f_11'],input[id$='-outreach_m_11'],input[id$='-outreach_f_11'],input[id$='-outreach_outuc_m_11'],input[id$='-outreach_outuc_f_11'],input[id$='-fixed_inuc_m_23'],input[id$='-fixed_inuc_f_23'],input[id$='-fixed_outuc_m_23'],input[id$='-fixed_outuc_f_23'],input[id$='-outreach_m_23'],input[id$='-outreach_f_23'],input[id$='-outreach_outuc_m_23'],input[id$='-outreach_outuc_f_23'],input[id$='-fixed_inuc_m_24'],input[id$='-fixed_inuc_f_24'],input[id$='-fixed_outuc_m_24'],input[id$='-fixed_outuc_f_24'],input[id$='-outreach_m_24'],input[id$='-outreach_f_24'],input[id$='-outreach_outuc_m_24'],input[id$='-outreach_outuc_f_24']").focus(function (e) {
        var val = $(this).val();
        if (val == 0) {
            $(this).val('');
        }

    });

    $("input[id$='-fixed_inuc_m_11'],input[id$='-fixed_inuc_f_11'],input[id$='-fixed_outuc_m_11'],input[id$='-fixed_outuc_f_11'],input[id$='-outreach_m_11'],input[id$='-outreach_f_11'],input[id$='-outreach_outuc_m_11'],input[id$='-outreach_outuc_f_11'],input[id$='-fixed_inuc_m_23'],input[id$='-fixed_inuc_f_23'],input[id$='-fixed_outuc_m_23'],input[id$='-fixed_outuc_f_23'],input[id$='-outreach_m_23'],input[id$='-outreach_f_23'],input[id$='-outreach_outuc_m_23'],input[id$='-outreach_outuc_f_23'],input[id$='-fixed_inuc_m_24'],input[id$='-fixed_inuc_f_24'],input[id$='-fixed_outuc_m_24'],input[id$='-fixed_outuc_f_24'],input[id$='-outreach_m_24'],input[id$='-outreach_f_24'],input[id$='-outreach_outuc_m_24'],input[id$='-outreach_outuc_f_24']").focusout(function (e) {

        if ($(this).val() == '' || isNaN($(this).val())) {
            $(this).val('0');
            return false;
        }
    });

    $("input[id$='-unusable_vials'],input[id$='-dispensed'],input[id$='-closing_balance'],input[id$='-unusable']").focusout(function (e) {
        if ($(this).val() == '' || isNaN($(this).val())) {
            $(this).val('0');
        }
        if ($(this).val() < 0) {
            $(this).val(Math.abs($(this).val()));
        }
    });
    $("input[id$='-fixed_inuc_m_11'],input[id$='-fixed_inuc_f_11'],input[id$='-fixed_outuc_m_11'],input[id$='-fixed_outuc_f_11'],input[id$='-outreach_m_11'],input[id$='-outreach_f_11'],input[id$='-outreach_outuc_m_11'],input[id$='-outreach_outuc_f_11'],input[id$='-fixed_inuc_m_23'],input[id$='-fixed_inuc_f_23'],input[id$='-fixed_outuc_m_23'],input[id$='-fixed_outuc_f_23'],input[id$='-outreach_m_23'],input[id$='-outreach_f_23'],input[id$='-outreach_outuc_m_23'],input[id$='-outreach_outuc_f_23'],input[id$='-fixed_inuc_m_24'],input[id$='-fixed_inuc_f_24'],input[id$='-fixed_outuc_m_24'],input[id$='-fixed_outuc_f_24'],input[id$='-outreach_m_24'],input[id$='-outreach_f_24'],input[id$='-outreach_outuc_m_24'],input[id$='-outreach_outuc_f_24']").focusout(function (e) {

        if ($(this).val() < 0) {
            $(this).val(Math.abs($(this).val()));
        }

    });
    if ($('#province_id_hdn').val() != 2) {
        $("#fixed_planned_sessions, #fixed_actually_held_sessions, #outreach_planned_sessions, #outreach_actually_held_sessions").focusout(function (e) {
            if ($(this).val() == '' || isNaN($(this).val())) {
                $(this).val('0');
            }
            if ($(this).val() < 0) {
                $(this).val(Math.abs($(this).val()));
            }
        });

        $("#fixed_planned_sessions, #fixed_actually_held_sessions, #outreach_planned_sessions, #outreach_actually_held_sessions").focus(function (e) {
            var val = $(this).val();
            if (val == 0) {
                $(this).val('');
            }
        });
    }

    $(" #children_live_birth, #surviving_children_0_11_M, #children_aged_12_23_M, #2_year_above, #pregnant_women1, #cba").focusout(function (e) {
        if ($(this).val() == '' || isNaN($(this).val())) {
            $(this).val('0');
        }
        if ($(this).val() < 0) {
            $(this).val(Math.abs($(this).val()));
        }
    });

    $(" #children_live_birth, #surviving_children_0_11_M, #children_aged_12_23_M, 2_year_above, #pregnant_women1, #cba").focus(function (e) {
        var val = $(this).val();
        if (val == 0) {
            $(this).val('');
        }
    });

    $("input[id$='-vials_used'],input[id$='-opening_balance'],input[id$='-received'],input[id$='-pregenant_women'],input[id$='-outside_pregenant_women'],input[id$='-non_pregenant_women'],input[id$='-outside_non_pregenant_women']").focusout(function () {
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
        if (c == 'NaN') {
            c = 0;
        }
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
//alert(c);
        // $('#' + itemid + '-closing_balance').val(c);
        // $('#' + itemid + '-cb').val(c);
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

    $("input[id$='-dispensed']").change(function () {
        var value = $(this).attr("id");
        var itemid = value.replace("-dispensed", "");
        var opening = $('#' + itemid + '-opening_balance').val();
        var received = $('#' + itemid + '-received').val();
        var dispensed = parseInt($('#' + itemid + '-dispensed').val());
        var total = (parseInt(opening) + parseInt(received));

        if ($('#' + itemid + '-vials_used').prop('readonly') == true)
        {
            var cb = total - dispensed;
            if (cb == 'NaN') {
                cb = 0;
            }
            //    $('#' + itemid + '-cb').val(cb);
            //    $('#' + itemid + '-closing_balance').val(cb);
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

    $("input[id$='-unusable_vials']").change(function () {
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
        if (c == 'NaN') {
            c = 0;
        }
        if (a > b) {
            alert("Used/Unused Vials must equal or less then opening + receive quantity");
            $(this).focus();
            /*setTimeout(function() {
             $(this).focus();
             }, 0);*/
        }

        //  $('#' + itemid + '-closing_balance').val(c);
        //  $('#' + itemid + '-cb').val(c);
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

    $("#monthly_report").change(function () {

        var action = $(this).val();

        var url = appName + '/stock/monthly-consumption2?do=' + action;
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


    $("#btn-loading").click(function (e) {
        Metronic.startPageLoading('Please wait...');
        e.preventDefault();
        var flag = true;
        var total_consumption = 0;
        /*Closing Balance Validation
         
         */

        $("input[id$='-closing_balance']").each(function () {

            var value = $(this).attr("id");
            var itemid = value.replace("-closing_balance", "");
            var item_category = $('#' + itemid + '-item_category').val();
            var itemname = $('#' + itemid + '-item_name').val();
            var opening = $('#' + itemid + '-opening_balance').val();
            var received = $('#' + itemid + '-received').val();
            var closing = $('#' + itemid + '-closing_balance').val();
            var dispensed = $('#' + itemid + '-dispensed').val();
            var pregenant_women_total = $('#pregenant_women_total').val();
            var non_pregenant_women_total = $('#non_pregenant_women_total').val();
            var start_no = parseInt($('#' + itemid + '-start_no').val());


            var no_of_doses = parseInt($('#' + itemid + '-no_of_doses').val());
            var i;
            var vacine_schedule;
            var nod;
            var consumption = 0;
            if (item_category == 1) {
                for (i = start_no; i <= no_of_doses; i++) {
                    if (i == 0) {
                        nod += 1;
                    }

                    vacine_schedule = (i == 1 && i == nod) ? '' : i;

                    if (parseInt(vacine_schedule.length) == 0) {
                        vacine_schedule = 1;
                    }

                    // Calculating Consumption
                    var v1 = $('#' + vacine_schedule + '-' + itemid + '-fixed_inuc_m_11').val();
                    var v2 = $('#' + vacine_schedule + '-' + itemid + '-fixed_inuc_f_11').val();
                    var v3 = $('#' + vacine_schedule + '-' + itemid + '-fixed_outuc_m_11').val();
                    var v4 = $('#' + vacine_schedule + '-' + itemid + '-fixed_outuc_f_11').val();
                    var v5 = $('#' + vacine_schedule + '-' + itemid + '-outreach_m_11').val();
                    var v6 = $('#' + vacine_schedule + '-' + itemid + '-outreach_f_11').val();
                    var v7 = $('#' + vacine_schedule + '-' + itemid + '-outreach_outuc_m_11').val();
                    var v8 = $('#' + vacine_schedule + '-' + itemid + '-outreach_outuc_f_11').val();
                    var v9 = $('#' + vacine_schedule + '-' + itemid + '-fixed_inuc_m_23').val();
                    var v10 = $('#' + vacine_schedule + '-' + itemid + '-fixed_inuc_f_23').val();
                    var v11 = $('#' + vacine_schedule + '-' + itemid + '-fixed_outuc_m_23').val();
                    var v12 = $('#' + vacine_schedule + '-' + itemid + '-fixed_outuc_f_23').val();
                    var v13 = $('#' + vacine_schedule + '-' + itemid + '-outreach_m_23').val();
                    var v14 = $('#' + vacine_schedule + '-' + itemid + '-outreach_f_23').val();
                    var v15 = $('#' + vacine_schedule + '-' + itemid + '-outreach_outuc_m_23').val();
                    var v16 = $('#' + vacine_schedule + '-' + itemid + '-outreach_outuc_f_23').val();

                    var v17 = $('#' + vacine_schedule + '-' + itemid + '-fixed_inuc_m_24').val();
                    var v18 = $('#' + vacine_schedule + '-' + itemid + '-fixed_inuc_f_24').val();
                    var v19 = $('#' + vacine_schedule + '-' + itemid + '-fixed_outuc_m_24').val();
                    var v20 = $('#' + vacine_schedule + '-' + itemid + '-fixed_outuc_f_24').val();
                    var v21 = $('#' + vacine_schedule + '-' + itemid + '-outreach_m_24').val();
                    var v22 = $('#' + vacine_schedule + '-' + itemid + '-outreach_f_24').val();
                    var v23 = $('#' + vacine_schedule + '-' + itemid + '-outreach_outuc_m_24').val();
                    var v24 = $('#' + vacine_schedule + '-' + itemid + '-outreach_outuc_f_24').val();

                    consumption += parseInt(v1) + parseInt(v2) + parseInt(v3) + parseInt(v4) +
                            parseInt(v5) + parseInt(v6) + parseInt(v7) + parseInt(v8) +
                            parseInt(v9) + parseInt(v10) + parseInt(v11) + parseInt(v12) +
                            parseInt(v13) + parseInt(v14) + parseInt(v15) + parseInt(v16) +
                            parseInt(v17) + parseInt(v18) + parseInt(v19) + parseInt(v20) +
                            parseInt(v21) + parseInt(v22) + parseInt(v23) + parseInt(v24);


                }
            } else if (item_category == 2) {
                consumption = parseInt(pregenant_women_total) + parseInt(non_pregenant_women_total);
            } else if (item_category == 3) {

                consumption = parseInt(dispensed);
            }

            //Validating Closing Balance

            if (closing < 0) {
                alert("Closing balance of " + itemname + " cannot be negative " + '\n' + "Please verify your calculations");
                $(this).focus();

                flag = false;
                return false;
            }

            if (((parseInt(opening) + parseInt(received)) < parseInt(consumption))) {

                alert("Consumption of " + itemname + " cannot be greater than (Opening+Receive) " + ((parseInt(opening) + parseInt(received))) + '\n' + "Please verify your calculations");
                $('#' + vacine_schedule + '-' + itemid + '-fixed_inuc_m_11').focus();

                flag = false;
                return false;
            }

            if (((parseInt(opening) + parseInt(received)) - parseInt(consumption)) < closing) {
                alert("Closing balance of " + itemname + " cannot be greater than " + ((parseInt(opening) + parseInt(received)) - parseInt(consumption)) + '\n' + "Please verify your calculations");
                $(this).focus();

                flag = false;
                return false;
            }
            total_consumption += consumption;

        });
        if (total_consumption == 0) {
            var monthly_report = $('#monthly_report').val();
            window.open('consumption-comments?do=' + monthly_report, '_blank', 'scrollbars=1,width=400,height=230');
            flag = false;
            return false;
        }

        //Closing Balance Validation End


        $("input[id$='-vials_used']").each(function () {
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
            //alert(dispensed);
            // alert();
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

        $("input[id$='-unusable_vials']").each(function () {
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

        $("input[id$='-dispensed']").each(function () {
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

        if ($('#province_id_hdn').val() == 2) {

            if ($('#fixed_planned_sessions').val() == "" || $('#fixed_planned_sessions').val() == 0) {
                alert('Please enter Fixed Planed Sessions');
                 $('#fixed_planned_sessions').focus();
                Metronic.stopPageLoading();
                flag = false;
                return false;
            }
            if ($('#fixed_actually_held_sessions').val() == "" || $('#fixed_actually_held_sessions').val()==0) {
                alert('Please enter Fixed Actually Held Sessions');
                 $('#fixed_actually_held_sessions').focus();
                Metronic.stopPageLoading();
                flag = false;
                return false;
            }
            if ($('#outreach_planned_sessions').val() == "" || $('#outreach_planned_sessions').val()==0) {
                alert('Please enter Outreach Planed Sessions');
                 $('#outreach_planned_sessions').focus();
                Metronic.stopPageLoading();
                flag = false;
                return false;
            }
            if ($('#outreach_actually_held_sessions').val() == "" || $('#outreach_actually_held_sessions').val()==0) {
                alert('Please enter Outreach Actually Sessions');
                $('#outreach_actually_held_sessions').focus();
                Metronic.stopPageLoading();
                flag = false;
                return false;
            }
        }

        if (flag == true) {
            clearInterval(interval);
            $("#dataEntryfrm").submit();
        } else {
            Metronic.stopPageLoading();
        }
    });
    var is_new_report = $('#isNewRpt').val();

    if (is_new_report == 2) {
        calculateSumView('pregenant_women', 'pregenant_women_total_view');
        calculateSumView('outside_pregenant_women', 'outside_pregenant_women_total_view');
        calculateSumView('non_pregenant_women', 'non_pregenant_women_total_view');
        calculateSumView('outside_non_pregenant_women', 'outside_non_pregenant_women_total_view');

    } else {

        calculateSum('pregenant_women', 'pregenant_women_total');
        calculateSum('outside_pregenant_women', 'outside_pregenant_women_total');
        calculateSum('non_pregenant_women', 'non_pregenant_women_total');
        calculateSum('outside_non_pregenant_women', 'outside_non_pregenant_women_total');
        //Total Calculation
        $(".pregenant_women").on("keyup keydown", function () {
            calculateSum('pregenant_women', 'pregenant_women_total');
        });
        $(".outside_pregenant_women").on("keyup keydown", function () {
            calculateSum('outside_pregenant_women', 'outside_pregenant_women_total');
        });
        $(".non_pregenant_women").on("keyup keydown", function () {
            calculateSum('non_pregenant_women', 'non_pregenant_women_total');
        });
        $(".outside_non_pregenant_women").on("keyup keydown", function () {
            calculateSum('outside_non_pregenant_women', 'outside_non_pregenant_women_total');
        });
    }

    // Trigger for updating AMC - Start
    /*$('#updateamc').click(function () {
     var self = $(this);
     $.ajax({
     type: "POST",
     url: appName + "/stock/update-wh-amc",
     data: {date: self.data('date'), item: self.data('item'), wh_id: self.data('wh')},
     success: function () {
     
     }
     });
     });
     
     var is_view = $("#is_view").val();
     
     if (is_view == 1) {
     $('#updateamc').trigger("click");
     }*/
    // Trigger for updating AMC - End

});

function calculateSum(field, total) {
    var sum = 0;

    //var total = 'pregenant_women_total';
    //iterate through each textboxes and add the values
    $("." + field).each(function () {
        var pregenant_women = $(this).val();

        if (!isNaN(pregenant_women) && pregenant_women.length != 0) {
            sum += parseFloat(pregenant_women);
        } else if (pregenant_women.length != 0) {
        }
    });

    $("input#" + total).val(sum);
}

function calculateSumView(field, total) {

    var sum = 0;

    //iterate through each textboxes and add the values
    $("." + field).each(function () {
        var pregenant_women = $(this).val();


        if (!isNaN(pregenant_women) && pregenant_women.length != 0) {
            sum += parseFloat(pregenant_women);
        } else if (pregenant_women.length != 0) {
        }
    });


    $("#" + total).html(sum);
}

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
            success: function (data) {
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
            success: function (data) {
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
            success: function (data) {

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

$('#print-monthly-consumption').click(function () {

    var monthly_report = $('#monthly_report').val();
    window.open('print-monthly-consumption2?do=' + monthly_report, '_blank', 'scrollbars=1,width=860,height=595');

});


function getPreMonthCB(wh_id, month, year, isNewRpt)
{
    if (confirm('Are you sure you refresh Closing Balance from previous month?'))
    {
        $.ajax({
            url: appName + '/stock/ajax-get-pre-month-cb2',
            type: 'post',
            data: {wh_id: wh_id, month: month, year: year, isNewRpt: isNewRpt},
            success: function (data) {
                var returnData = $.parseJSON(data);

                $.each(returnData, function (key, value) {
                    $('#' + key + '-opening_balance').val(value);

                    var doses = $('#' + key + '-doses').val();
                    var dispensed = $('#' + key + '-dispensed').val();
                    var received = $('#' + key + '-received').val();
                    var used = ($('#' + key + '-vials_used').val() * doses);
                    var unused = ($('#' + key + '-unusable_vials').val() * doses);
                    var a = (parseInt(used) + parseInt(unused));

                    var b = (parseInt(value) + parseInt(received));
                    var c = (parseInt(b) - parseInt(a));
                    if (c == 'NaN') {
                        c = 0;
                    }
                    // alert(c);
                    //    $('#' + key + '-cb').val(c);
                    //    $('#' + key + '-closing_balance').val(c);
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
        success: function (data) {
            var returnData = $.parseJSON(data);

            $.each(returnData, function (key, value) {
                $('#' + key + '-opening_balance').val(value);

                var doses = $('#' + key + '-doses').val();
                var dispensed = $('#' + key + '-dispensed').val();
                var received = $('#' + key + '-received').val();
                var used = ($('#' + key + '-vials_used').val() * doses);
                var unused = ($('#' + key + '-unusable_vials').val() * doses);
                var a = (parseInt(used) + parseInt(unused));

                var b = (parseInt(value) + parseInt(received));
                var c = (parseInt(b) - parseInt(a));
                if (c == 'NaN') {
                    c = 0;
                }
                //  $('#' + key + '-cb').val(c);
                //  $('#' + key + '-closing_balance').val(c);
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
            success: function (data) {
                var returnData = $.parseJSON(data);

                $.each(returnData, function (key, value) {
                    $('#' + key + '-received').val(value);

                    var doses = $('#' + key + '-doses').val();
                    var dispensed = $('#' + key + '-dispensed').val();
                    var ob = $('#' + key + '-opening_balance').val();
                    var used = ($('#' + key + '-vials_used').val() * doses);
                    var unused = ($('#' + key + '-unusable_vials').val() * doses);
                    var a = (parseInt(used) + parseInt(unused));

                    var b = (parseInt(ob) + parseInt(value));
                    var c = (parseInt(b) - parseInt(a));
                    if (c === 'NaN') {
                        c = 0;
                    }
                    // $('#' + key + '-cb').val(c);
                    // $('#' + key + '-closing_balance').val(c);
                });
            }
        });
    }
}
$("input[id$='-work_quantity']").keyup(function (e) {
    var value = $(this).attr("id");
    var id = value.replace("-work_quantity", "");
    var qty = $(this).val();
    var ava_qty = $('#' + id + '-total_quantity').val();
    if (parseInt(qty) > parseInt(ava_qty)) {
        alert("Quantity should not be greater than " + ava_qty + ".");
        $(this).focus();

    }
});
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
            url: appName + "/stock/monthly-consumption2-draft",
            data: $('#dataEntryfrm').serialize(),
            cache: false,
            success: function () {
                $('#notific8_show').trigger('click');
                $('#btn-loading').removeAttr('disabled');
            }
        });
        form_clean = form_dirty;
    }
}