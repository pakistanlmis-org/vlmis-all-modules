$(function() {
    var province_id = $.GetUrlParam["province_id"];
    var district_id = $.GetUrlParam["district_id"];
    var campaign_id = $.GetUrlParam["campaign_id"];
    var day = $.GetUrlParam["day"];
    var item_id = $.GetUrlParam["item_id"];

    var role_id = $('#role_id').val();
    var warehouse_id = $('#warehouse_id').val();

//    if (district_id !== "" || campaign_id !== "") {
//        showProvinces(province_id);
//        showDistricts(district_id);
//        showDates(day);
//        showItems(item_id);
//    }
//    
    if (campaign_id) {
        showProvinces(province_id);
        showDistricts(district_id);
        showDates(day);
        showItems(item_id);
    }

// Turn off autocomplete
    $(document).on('focus', ':input', function() {
        $(this).attr('autocomplete', 'off');
    });
    // Search Form validation
    $("#search_form").validate({
        rules: {
            province_id: 'required',
            district_id: 'required',
            campaign_id: 'required',
            day: 'required',
            item_id: 'required'
        }
    });


    $('#campaign_id').change(function(e) {

        var item_id = $.GetUrlParam["item_id"];

        $('#province_id').val('');
        $('#district_id').val('');
        $('#wh_id').val('');
        showProvinces('');
        showItems(item_id);
        if (role_id == 8 && warehouse_id != "") {
            $('#district_id').val('');
        }
        showDates('');
    });

    function showProvinces(province_id) {
        var campaign_id = $('#campaign_id').val();
        if (campaign_id != '') {
            $.ajax({
                url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
                type: 'POST',
                data: {condition: '007', province_id: province_id, campaign_id: campaign_id, provinces: ''},
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

    // Show districts
    $('#province_id').change(function(e) {
        showDistricts('');
    });

});

function showDates(day) {
    var campaign_id = $('#campaign_id').val();
    if (campaign_id != '') {
        $.ajax({
            url: appName + "/campaign/manage-campaigns/ajax-for-campaign",
            type: 'POST',
            data: {condition: '006', campaign_id: campaign_id, day: day, show_all: 1},
            success: function(data) {
                $('#day').html(data);
            }
        })
    } else {
        $('#day').html('<option value="">Select Campaign</option>');
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
            }
        })
    } else {
        $('#item_id').html('<option value="">Select Campaign</option>');
    }
}
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
        });
    }
    else {
        $('#district_id').empty();
    }
}
// Edt function
function doEdit(campaign_id, day, distId, id, wh_id, item_id, type) {
    var newURL = appName + '/campaign/manage-campaigns/edit-data-entry?campaign_id=' + campaign_id + '&day=' + day + '&id=' + id + '&district_id=' + distId + '&wh=' + wh_id + '&item_id=' + item_id + '&type=' + type;
    window.location = newURL;
}
// Edt function
function doDel(val) {
    if (confirm('Are you sure, you want to delete this record?'))
    {
        var newURL = appName + '/campaign/manage-campaigns/data-entry-history?id=' + val;
        window.location = newURL;
    }
}