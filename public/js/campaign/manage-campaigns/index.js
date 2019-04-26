$(function() {
    $('#records').change(function() {
        var counter = $(this).val();
        document.location.href = appName + '/campaign/manage-campaigns/index/?counter=' + counter;
    });

    $(document).on("click", "a.closeCampaigns", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/open-campaigns-target",
            data: {id: id},
            dataType: 'html',
            success: function(data) {
                $('#' + id).html("Open");
                $('#' + id).removeClass("closeCampaigns");
                $('#' + id).addClass("openCampaigns");
            }
        });
    });

    $(document).on("click", "a.openCampaigns", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: appName + "/campaign/manage-campaigns/close-campaigns-target",
            data: {id: id},
            dataType: 'html',
            success: function(data) {                
                $('#' + id).html("Close");
                $('#' + id).removeClass("openCampaigns");
                $('#' + id).addClass("closeCampaigns");
            }
        });
    });

});