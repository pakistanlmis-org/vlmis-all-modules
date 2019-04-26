$(function() {
    $('#records').change(function() {
        var counter = $(this).val();
        document.location.href = appName + '/campaign/manage-campaigns/campaign-readiness/?counter=' + counter;
    });

// Date pickers


    $("#arrival_date_mobiliztion_material").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $("#dpec_meeting_date").datepicker({
        minDate: "-3Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    $('#campaign_add_id').change(function(e) {
        //showProvinces('');

        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-get-campaign-vaccince",
            type: 'POST',
            data: {campaign_id: $('#campaign_add_id').val()},
            success: function(data) {
                $('#item_id').val(data);

            }
        });

        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-get-campaign-vials-required",
            type: 'POST',
            data: {campaign_id: $('#campaign_add_id').val()},
            success: function(data) {
                $('#vials_required').val(data);

            }
        });
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-get-campaign-vials-available",
            type: 'POST',
            data: {campaign_id: $('#campaign_add_id').val()},
            success: function(data) {
                $('#vials_available').val(data);

            }
        });

    });

    if ($('#campaign_add_id').val() !== "") {

        // showProvinces('');
    }

    function showProvinces(province_id) {
        var campaign_id = $('#campaign_add_id').val();
        if (campaign_id != '') {
            $.ajax({
                url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
                type: 'POST',
                data: {condition: '007', province_id: province_id, campaign_id: campaign_id, provinces: 1},
                success: function(data) {
                    $('#province_id').html(data);

                }})
        } else {
            $('#province_id').empty();
        }
    }
    $('#province_id').change(function(e) {
        showDistricts('');
    });

    function showDistricts(dist_id) {
        var province_id = $('#province_id').val();
        var campaign_id_combo = $('#campaign_add_id').val();
        if (campaign_id_combo != "") {
            campaign_id = campaign_id_combo;
        }
        if (province_id != '') {
            $.ajax({
                url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
                type: 'POST',
                data: {condition: '002', province_id: province_id, dist_id: dist_id, campaign_id: campaign_id},
                success: function(data) {
                    $('#district_id').html(data);
                }
            });
        }
        else {
            $('#district_id').empty();
        }
    }
    $(".update-user").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-edit-readiness",
            data: {item_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

});

// validate signup form on keyup and submit
$("#add-user").validate({
    rules: {
        campaign_add_id: {
            required: true
        },
        num_tally_sheets: {
            required: true,
            number: true,
            min: 0
        },
        num_finger_markers: {
            required: true,
            number: true,
            min: 0
        },
        province_id: {
            required: true
        },
        district_id: {
            required: true
        },
        dpec_meeting_date: {
            required: true
        },
        arrival_date_mobiliztion_material: {
            required: true
        }



    }

});


// validate signup form on keyup and submit
$("#update-user").validate({
    rules: {
        campaign_add_id: {
            required: true
        },
        num_tally_sheets: {
            required: true,
            number: true,
            min: 0
        },
        num_finger_markers: {
            required: true,
            number: true,
            min: 0
        },
        province_edit_id: {
            required: true
        },
        district_edit_id: {
            required: true
        },
        dpec_meeting_date: {
            required: true
        },
        arrival_date_mobiliztion_material: {
            required: true
        }
    }

});







