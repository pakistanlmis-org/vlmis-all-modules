$(function() {

    $('#campaign_edit_id').change(function(e) {
        $('#province_edit_id').val('');
        $('#district_edit_id').val('');
        $('#uc_edit_id').val('');
        showProvinces('');

    });

    function showProvinces(province_id) {
        var campaign_id = $('#campaign_edit_id').val();
        if (campaign_id != '') {
            $.ajax({
                url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
                type: 'POST',
                data: {condition: '007', province_id: province_id, campaign_id: campaign_id, provinces: 1},
                success: function(data) {
                    $('#province_edit_id').html(data);
                    if (district_id) {
                        showDistricts(district_id);
                    }
                }})
        } else {
            $('#province_edit_id').empty();
        }
    }
    if ($('#campaign_edit_id').val() != "") {
        var campaign_id = $('#campaign_edit_id').val();
        if (campaign_id != '') {
            $.ajax({
                url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
                type: 'POST',
                data: {condition: '007', province_id: '', campaign_id: campaign_id, provinces: 1},
                success: function(data) {
                    $('#province_edit_id').html(data);

                    setTimeout(function() {

                        $('#province_edit_id').val($('#province_id_hidden').val());

                    }, 200);
                }});
        }
    }



    if ($('#campaign_edit_id').val() != "") {

        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-get-campaign-vaccince",
            type: 'POST',
            data: {campaign_id: $('#campaign_edit_id').val()},
            success: function(data) {
                $('#item_id_edit').val(data);

            }
        });

        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-get-campaign-vials-required",
            type: 'POST',
            data: {campaign_id: $('#campaign_edit_id').val()},
            success: function(data) {
                $('#vials_required_edit').val(data);

            }
        });
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-get-campaign-vials-available",
            type: 'POST',
            data: {campaign_id: $('#campaign_edit_id').val()},
            success: function(data) {
                $('#vials_available_edit').val(data);

            }
        });

    }

    // Show districts
    $('#province_edit_id').change(function(e) {
        showDistricts('');
    });

    $("#district_edit_id").change(function() {

        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-get-ucs",
            data: {campaign_id: $('#campaign_edit_id').val(), province_id: $('#province_edit_id').val(), district_id: $('#district_edit_id').val()},
            dataType: 'html',
            success: function(data) {
                $('#uc_edit_id').html(data);
            }
        });

    });



    function showDistricts(dist_id) {
        var province_id = $('#province_edit_id').val();
        var campaign_id_combo = $('#campaign_edit_id').val();
        if (campaign_id_combo != "") {
            campaign_id = campaign_id_combo;
        }
        if (province_id != '') {
            $.ajax({
                url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
                type: 'POST',
                data: {condition: '002', province_id: province_id, dist_id: dist_id, campaign_id: campaign_id},
                success: function(data) {
                    $('#district_edit_id').html(data);
                }
            })
        }
        else {
            $('#district_edit_id').empty();
        }
    }
    if ($('#province_edit_id').val() != "") {
        var province_id = $('#province_id_hidden').val();
        var campaign_id_combo = $('#campaign_edit_id').val();
        if (campaign_id_combo != "") {
            campaign_id = campaign_id_combo;
        }
        if (province_id != '') {
            $.ajax({
                url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
                type: 'POST',
                data: {condition: '002', province_id: province_id, dist_id: '', campaign_id: campaign_id},
                success: function(data) {
                    $('#district_edit_id').html(data);
                    setTimeout(function() {
                        $('#district_edit_id').val($('#district_id_hidden').val());
                    }, 200);
                }
            })
        }

    }


    if ($("#district_edit_id").val() != "") {

        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-get-ucs",
            data: {campaign_id: $('#campaign_edit_id').val(), province_id: $('#province_id_hidden').val(), district_id: $('#district_id_hidden').val()},
            dataType: 'html',
            success: function(data) {
                $('#uc_edit_id').html(data);
                setTimeout(function() {
                    $('#uc_edit_id').val($('#uc_id_hidden').val());
                }, 200);
            }
        });

    }


});