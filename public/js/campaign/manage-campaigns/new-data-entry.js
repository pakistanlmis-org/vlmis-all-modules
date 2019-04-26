$(function() {
    var campaign_id = $.GetUrlParam["campaign_id"];
    var day = $.GetUrlParam["day"];
    var item_id = $.GetUrlParam["item_id"];
    var role_id = $('#role_id').val();
    var warehouse_id = $('#warehouse_id').val();

    if (campaign_id) {
        showDates(day);
        showItems(item_id);
        showUCs(day);
    }

    // Allow only numbers
    $('input[type="text"]').keydown(function(e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) { // if shift, ctrl or alt keys held down
            e.preventDefault();         // Prevent character input
        } else {
            var n = e.keyCode;
            if (!((n == 8)              // backspace
                    || (n == 9)                // Tab
                    || (n == 46)                // delete
                    || (n >= 35 && n <= 40)     // arrow keys/home/end
                    || (n >= 48 && n <= 57)     // numbers on keyboard
                    || (n >= 96 && n <= 105))   // number on keypad
                    ) {
                e.preventDefault();     // Prevent character input
            }
        }
    });
// Turn off autocomplete
    $(document).on('focus', ':input', function() {
        $(this).attr('autocomplete', 'off');
    });

// Form validate
    $("#campaign_form").validate({
        rules: {
            campaign_id: 'required',
            item_id: 'required',
            wh_id: 'required',
            campaign_day: 'required',
            daily_target: 'required',
            target_age_six_months: 'required',
            target_age_sixty_months: 'required',
            vials_given: 'required',
            vials_used: 'required',
            vials_expired: 'required',
            teams_reported: {
                min: 1
            }
        },
        submitHandler: function(form) {
            var totalCoverage = ($('#total_coverage').val() != '') ? parseFloat($('#total_coverage').val()) : 0;
            var vialsGiven = ($('#vials_given').val() != '') ? parseFloat($('#vials_given').val()) : 0;
            var vialsUsed = ($('#vials_used').val() != '') ? parseFloat($('#vials_used').val()) : 0;
            var vialsExpired = ($('#vials_expired').val() != '') ? parseFloat($('#vials_expired').val()) : 0;
            var vialsReturned = ($('#vials_returned').val() != '') ? parseFloat($('#vials_returned').val()) : 0;
            var no_of_doses_hdn = ($('#no_of_doses_hdn').val() != '') ? parseFloat($('#no_of_doses_hdn').val()) : 0;
            var balance = vialsGiven - (vialsUsed + vialsExpired + Math.abs(vialsReturned));
            var item_id = ($('#item_id').val() != '') ? parseFloat($('#item_id').val()) : 0;

            var recon_syr_wasted = ($('#recon_syr_wasted').val() != '') ? parseFloat($('#recon_syr_wasted').val()) : 0;
            var ad_syr_wasted = ($('#ad_syr_wasted').val() != '') ? parseFloat($('#ad_syr_wasted').val()) : 0;
            var total_tcovergae_adsyr = totalCoverage + ad_syr_wasted;
            var total_reconsyr_vialsused = recon_syr_wasted + vialsUsed;
            var vialUsed_doses = vialsUsed * no_of_doses_hdn;

            if (balance != 0) {
                alert('Please enter correct data.');
                //  $('#vials_given').removeClass('valid');
                $('#vial_given').addClass('has-error');
                $('#vial_used').addClass('has-error');
                $('#vial_expired').addClass('has-error');
                return false;
            }else if (item_id == 23 && vialsUsed >= total_tcovergae_adsyr) {
                alert('Please enter Vials Used greater than or equal to (Total Coverage + No. of AD/Disposable Syringe Wasted)');
                $('#vial_used').addClass('has-error');
                $('#total_coverages').addClass('has-error');
                $('#ad_syr_waste').addClass('has-error');
                return false;
            }else if (item_id == 23 && vialsGiven <= total_reconsyr_vialsused) {
                alert('Please enter Vials Given greater than or equal to (No. of Reconstituion Syringe Wasted + Vials Used )');
                $('#vial_given').addClass('has-error');
                $('#recon_syr_waste').addClass('has-error');
                $('#vial_used').addClass('has-error');
                return false;
            }else if (parseInt(totalCoverage) >= parseInt(vialUsed_doses)  ) {
              // alert(totalCoverage + '....' + vialsUsed * no_of_doses_hdn);
                alert('Please enter Total Coverage less than or equal to (Vials Used in Doses )');
                $('#vial_used').addClass('has-error');
                $('#total_coverages').addClass('has-error');
                return false;
            }else{
                $('#vial_given').removeClass('has-error');
                $('#vial_used').removeClass('has-error');
                $('#vial_expired').removeClass('has-error');
                $('#total_coverages').removeClass('has-error');
                $('#ad_syr_waste').removeClass('has-error');
                $(form).submit();
            }

        }
    });


// Total Coverage 
    $('#target_age_six_months, #target_age_sixty_months').keyup(function(e) {
        totalCoverage();
    });

// LMIS calculation
    $('#vials_given, #vials_used, #vials_expired').keyup(function(e) {
        vialsReturned();
    });

    // LMIS calculation

// Show districts
    $('#province_id').change(function(e) {
        showDistricts('');
    });

// On change show entered data
    $('#province_id, #district_id, #day').change(function(e) {
        showCampaignData();
    });

// Campaign
    $('#campaign_id').change(function(e) {
        showDates('');
        showItems('');
    });
// Get UCS bases in the selected day and campaign
    $('#campaign_day').change(function(e) {
        showUCs(this.value);
        getCampDate();
    });
// Show hide syringes wastages on the bases of Items
    $('#item_id').change(function(e) {

        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-get-number-doses",
            type: 'POST',
            data: {item_id: $('#item_id').val()},
            success: function(data) {
                $('#no_of_doses_hdn').val(data);

            }
        });

        showSyrWastages($("#item_id").val());
    });
//
    $('#wh_id').change(function(e) {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-get-uc-target",
            type: 'POST',
            data: {warehouse_id: $(this).val(), campaign_id: $('#campaign_id').val()},
            success: function(data) {
                $('#daily_target').val(data);
            }
        });

    });
    $('input[type="text"]').change(function() {
        if ($(this).val() == '') {
            $(this).val('0');
        }
    })
//
    $('input[type="text"]:not(#wh_name)').focus(function(e) {
        if ($(this).val() == 0) {
            $(this).val('');
        }
    });

    $('input[type="text"]:not(#wh_name)').focusout(function(e) {
        if ($(this).val() == '' || isNaN($(this).val())) {
            $(this).val('0');
        }
    });


    $('#total_coverage').attr("readonly", "readonly");
    $('#vials_returned').attr("readonly", "readonly");
});

function getCampDate() {
    var campDate = $('#campaign_day option:selected').text();
    campDate = campDate.substr(4);
    //$('#campaign_day').val(campDate);
}

function showSyrWastages(itm_id) {
    // var arr = [23];
    //$.inArray(itm_id, arr)
    if (itm_id == 23) {
        $('#age_group1').html("Less than 5 Years");
        $('#age_group2').html("5 to 10 Years");
        $('#recon_syr_wasted_span').show();
        $('#ad_syr_wasted_span').show();
        $('#house_hold').hide();
        $('#inaccessible_children').hide();
        $('#zero_doses').hide();
    } else {
        $('#age_group1').html("Age 0-5 Months");
        $('#age_group2').html("Age 6-59 Months");
        $('#recon_syr_wasted_span').hide();
        $('#ad_syr_wasted_span').hide();
        $('#house_hold').show();
        $('#inaccessible_children').show();
        $('#zero_doses').show();
    }
}

function showUCs(val) {
    var campaign_id = $('#campaign_id').val();
    var district_id = $('#district_id').val();

    if (campaign_id != '' && val != '' && val != 'all') {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            type: 'POST',
            data: {condition: '003', campaign_id: campaign_id, district_id: district_id, day: val},
            success: function(data) {
                $('#wh_id').html(data);
            }
        });
    } else {
        $('#wh_id').html('<option value="">Select</option>');
    }
}

function showDates(day) {
    var campaign_id = $('#campaign_id').val();
    if (campaign_id != '') {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            type: 'POST',
            data: {condition: '006', campaign_id: campaign_id, day: day},
            success: function(data) {
                $('#campaign_day').html(data);
                getCampDate();
            }
        })
    } else {
        $('#campaign_day').html('<option value="">Select</option>');
    }
}

function showItems(item_id) {
    var campaign_id = $('#campaign_id').val();
    if (campaign_id != '') {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            type: 'POST',
            data: {condition: '005', campaign_id: campaign_id, item_id: item_id},
            success: function(data) {
                $('#item_id').html(data);
                showSyrWastages($('#item_id').val());
            }
        })
    } else {
        $('#item_id').html('<option value="">Select</option>');
    }
}

function vialsReturned() {
    var vialsGiven = ($('#vials_given').val() != '') ? parseFloat($('#vials_given').val()) : 0;
    var vialsUsed = ($('#vials_used').val() != '') ? parseFloat($('#vials_used').val()) : 0;
    var vialsExpired = ($('#vials_expired').val() != '') ? parseFloat($('#vials_expired').val()) : 0;
    var vialsReturned = vialsGiven - (vialsUsed + vialsExpired);
    $('#vials_returned').val(vialsReturned);
}

function totalCoverage() {
    var AgeSixMonths = ($('#target_age_six_months').val() != '') ? parseFloat($('#target_age_six_months').val()) : 0;
    var AgeSixtyMonths = ($('#target_age_sixty_months').val() != '') ? parseFloat($('#target_age_sixty_months').val()) : 0;

    var total = AgeSixMonths + AgeSixtyMonths;
    $('#total_coverage').val(total);
}

function showDistricts(dist_id) {
    var province_id = $('#province_id').val();
    if (province_id != '') {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            type: 'POST',
            data: {condition: '002', province_id: province_id, dist_id: dist_id, campaign_id: campaign_id},
            success: function(data) {
                $('#district_id').html(data);
            }
        })
    } else {
        $('#district_id').empty();
    }
}
// Edt function
//function doEdit(val) {
//    
//    var newURL = appName + "/campaign/manage-campaigns/new-data-entry?id="+val;
//    window.location = newURL;
//}
function doEdit(campaign_id, day, distId, id, wh_id, item_id) {
    var newURL = appName + '/campaign/manage-campaigns/edit-data-entry?campaign_id=' + campaign_id + '&day=' + day + '&id=' + id + '&district_id=' + distId + '&wh=' + wh_id + '&item_id=' + item_id;
    window.location = newURL;
}
// Edt function
function doDel(val) {
    if (confirm('Are you sure, you want to delete this record?')) {
        var newURL = appName + "/campaign/manage-campaigns/new-data-entry?id=" + val;
        window.location = newURL;
    }
}

