$(function() {

    if ($("#campaign_edit_id").val() != "") {
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign-uc",
            data: {condition: 003, campaign_id: $('#campaign_edit_id').val()},
            dataType: 'html',
            success: function(data) {

                $('#uc_edit_id').html(data);
                $('#uc_edit_id').val($('#uc_edit_id_hidden').val());
            }
        });
    }

    if ($("#uc_edit_id_hidden").val() != "") {

        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/ajax-campaign-target-uc",
            data: {uc_id: $('#uc_edit_id_hidden').val(), campaign_id: $('#campaign_edit_id').val()},
            dataType: 'html',
            success: function(data) {

                $('#target_uc').val(data);

            }
        });
    }
});