$(function() {
    $('#records').change(function() {
        var counter = $(this).val();
        document.location.href = appName + '/campaign/manage-campaigns/lqas-data-entry/?counter=' + counter;
    });


    $(".update-user").click(function() {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-edit-lqas",
            data: {lqas_id: $(this).attr('itemid')},
            dataType: 'html',
            success: function(data) {
                $('#modal-body-contents').html(data);
                $('#update-button').show();
            }
        });
    });

    // validate signup form on keyup and submit
    $("#add-user").validate({
        rules: {
            campaign_id: {
                required: true
            },
            province_id: {
                required: true
            },
            district_id: {
                required: true
            },
            uc_id: {
                required: true
            },
            surveyor: {
                required: true,
                alphanumeric: true
            },
            checked: {
                required: true,
                alphanumeric: true
            },
            unvaccinted: {
                required: true,
                alphanumeric: true
            },
            remarks: {
                required: true
            }

        }

    });


    // validate signup form on keyup and submit
    $("#update-user").validate({
        rules: {
            campaign_edit_id: {
                required: true
            },
            province_edit_id: {
                required: true
            },
            district_edit_id: {
                required: true
            },
            uc_edit_id: {
                required: true
            },
            surveyor: {
                required: true,
                alphanumeric: true
            },
            checked: {
                required: true,
                alphanumeric: true
            },
            unvaccinted: {
                required: true,
                alphanumeric: true
            },
            remarks: {
                required: true
            }



        }

    });





    $('#campaign_id').change(function(e) {
        $('#province_id').val('');
        $('#district_id').val('');
        $('#wh_id').val('');
        showProvinces('');

    });

    function showProvinces(province_id) {
        var campaign_id = $('#campaign_id').val();
        if (campaign_id != '') {
            $.ajax({
                url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
                type: 'POST',
                data: {condition: '007', province_id: province_id, campaign_id: campaign_id, provinces: 1},
                success: function(data) {
                    $('#province_id').html(data);
                    if (district_id) {
                        showDistricts(district_id);
                    }
                }})
        } else {
            $('#province_id').empty();
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
                    $('#province_edit_id').val($('#province_id_hidden').val());
                    if (district_id) {
                        showDistricts(district_id);
                    }
                }})
        }
    }

    // Show districts
    $('#province_id').change(function(e) {
        showDistricts('');
    });

    $("#district_id").change(function() {

        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-get-ucs",
            data: {campaign_id: $('#campaign_id').val(), province_id: $('#province_id').val(), district_id: $('#district_id').val()},
            dataType: 'html',
            success: function(data) {
                $('#uc_id').html(data);
            }
        });

    });

})

function showDistricts(dist_id) {
    var province_id = $('#province_id').val();
    var campaign_id_combo = $('#campaign_id').val();
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
        })
    }
    else {
        $('#district_id').empty();
    }
}
if ($('#province_id').val() != "") {
    var province_id = $('#province_id_hidden').val();
    var campaign_id_combo = $('#campaign_id').val();
    if (campaign_id_combo != "") {
        campaign_id = campaign_id_combo;
    }
    if (province_id != '') {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            type: 'POST',
            data: {condition: '002', province_id: province_id, dist_id: '', campaign_id: campaign_id},
            success: function(data) {
                $('#district_id').html(data);
                $('#district_id').val($('#district_id_hidden').val());
            }
        })
    }

}



